<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transacoes; 

use App\Models\Conta;

use App\Models\User; 

use App\Models\CodigosTransacoes;

use App\Models\Agencia;

use App\Enums\TipoTransacao;

use Carbon\Carbon;


class TransacaoController extends Controller
{
    
    private  $transacao;
    private $codigoTransacao;

    const QTDE_MAX_TENTAIVA = 50;
    const TEMPO_EXPIRACAO_CODIGO_MINUTOS = 30;
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct(Transacoes $transacao, CodigosTransacoes $codigoTransacao)
    {
        $this->transacao = $transacao;
        $this->codigoTransacao = $codigoTransacao;
    }



    public function listAll()
    {
        return $this->transacao->paginate(10);
    }

    public function createCodigo(Request $request)
    {
        $resquestCreateCodigo = $request->only(
            ['email', 'senha', 
            'tipoTrasacao', 'numeroContaOrigem']);

        $user = User::where('email', $resquestCreateCodigo['email'])->first();


        if (!$user || strcmp($resquestCreateCodigo['senha'], $user->senha)) {
            
            return response()->json(['error' => 'Credenciais invalidas'], 401);
        }

        $contaOrigem = Conta::where('numero', $resquestCreateCodigo['numeroContaOrigem'])->first();

        if(!$contaOrigem)
        {
            return response()->json(['error' => 'Numero da conta invalido'], 401);
        }

        // Gerar código de 4 dígitos
        $tentativas  = 0;
        $codigo = '';
        $currentDate = Carbon::now();
        do {
            $codigo = mt_rand(1000, 9999);

            $exists = CodigosTransacoes
            ::where('codigo', $codigo)
            ->where('data_expiracao', '<', $currentDate)
            ->exists();
            $tentativas++;
        } while ($exists && $tentativas <=  self::QTDE_MAX_TENTAIVA);

        if($tentativas >=  self::QTDE_MAX_TENTAIVA){
            return response()->json(['Error' => 'Codigo temporariamente indisponivel. Tente mais tarde!'], 200);
        }

        if($resquestCreateCodigo['tipoTrasacao'] == TipoTransacao::Deposito)
        {
            $codigo = 'DEP'.$codigo;
        } else if ($resquestCreateCodigo['tipoTrasacao'] == TipoTransacao::Transferencia){
            $codigo = 'TRANSF'.$codigo;
        }

        $dataExpiracao = $currentDate->addMinutes(self::TEMPO_EXPIRACAO_CODIGO_MINUTOS);
      
        $novoCodigoTrasacao['tipo'] = $resquestCreateCodigo['tipoTrasacao'];
        $novoCodigoTrasacao['codigo'] = $codigo;
        $novoCodigoTrasacao['numero_conta_origem'] = $contaOrigem['numero'];
        $novoCodigoTrasacao['data_expiracao'] = $dataExpiracao;
        
        $this->codigoTransacao->create($novoCodigoTrasacao);

        return response()->json(['Codigo' => $codigo], 200);
    }


    public function createDeposito(Request $request) {
        $requestCreateDeposito = $request->only([
            'numeroContaDestino', 'valor', 'codigoTrasacao'
        ]);
        $pattern = "/^DEP\d{4}$/";
        $reqCodigoTransacao = $requestCreateDeposito['codigoTrasacao'];

        if (!self::validarCodigo($pattern, $reqCodigoTransacao)) {
            return response()->json(['error' => 'Formato de codigo invalido!'], 401);
        }

        $codigoTransacao = CodigosTransacoes
            ::where('codigo', $reqCodigoTransacao)->first();

        if(!$codigoTransacao)
        {
            return response()->json(['error' => 'Codigo da transacao invalido!'], 500);
        }

        $currentDate = Carbon::now();
        $codigoDateExpiracao = $codigoTransacao['data_expiracao'];

        if($currentDate > $codigoDateExpiracao)
        {
            return response()->json(['error' => 'Codigo expirado!'], 401);
        }

        $contaDestino = Conta
            ::where('numero', $requestCreateDeposito['numeroContaDestino'])->first();

        if(!$contaDestino)
        {
                return response()->json(['error' => 'Conta de destino invalida!'], 401);
        }

        $contaOrigem = Conta::
            where('numero', $codigoTransacao['numero_conta_origem'])->first();
  

        $agenciaContaOrigem = Agencia::
            where('id', $contaOrigem['agencia_id'])->first();

        $agenciaContaDestino = Agencia::
            where('id', $contaDestino['agencia_id'])->first();
        

        if($agenciaContaOrigem['numero'] != $agenciaContaDestino['numero'])
        {
                return response()
                ->json(['error' =>
                     'Nao e possivel realizar depositos entre agencias diferentes!']);
        }

        if($contaOrigem['saldo'] < $requestCreateDeposito['valor'])
        {
            return response()
            ->json(['error' =>
                 'Nao e possivel realizar o deposito. Saldo insuficiente!']); 
        }

        // Salvando antes de debitar
        $saldoOrigemAnteriorDebito = $contaOrigem['saldo'];

        // Atualizando saldos
        $contaOrigem['saldo'] -= $requestCreateDeposito['valor'];
        $contaDestino['saldo'] += $requestCreateDeposito['valor'];

        // Criando objeto transação
        $transacao['conta_origem_id'] = $contaOrigem['id'];
        $transacao['conta_destino_id'] = $contaDestino['id'];
        $transacao['tipo'] = $codigoTransacao['tipo'];
        $transacao['valor'] = $requestCreateDeposito['valor'];;
        $transacao['saldo_origem_anterior'] = $saldoOrigemAnteriorDebito;
        $transacao['saldo_origem_posterior']= $contaOrigem['saldo'];
        
        // Debitando
        Conta::where('numero', $contaOrigem['numero'])->first()
        ->update(['saldo' => $contaOrigem['saldo']]);

        // Creditando
        Conta::where('numero', $contaDestino['numero'])->first()
        ->update(['saldo' => $contaDestino['saldo']]);

        // Salvando
        $this->transacao->create($transacao);
        
        return response()
        ->json(['data' => [
                    'message' => 'Foi depositado com sucesso!']
               ]);
    }


    public function createTransferencia(Request $request) {
        $requestCreateTransferencia = $request->only([
            'numeroContaDestino', 'valor', 'codigoTrasacao'
        ]);
        $pattern = "/^TRANSF\d{4}$/";
        $reqCodigoTransacao = $requestCreateTransferencia['codigoTrasacao'];

        if (!self::validarCodigo($pattern, $reqCodigoTransacao)) {
            return response()->json(['error' => 'Formato de codigo invalido!'], 401);
        }

        $codigoTransacao = CodigosTransacoes
            ::where('codigo', $reqCodigoTransacao)->first();

        if(!$codigoTransacao)
        {
            return response()->json(['error' => 'Numero da conta invalido!'], 500);
        }

        $currentDate = Carbon::now();
        $codigoDateExpiracao = $codigoTransacao['data_expiracao'];

        if($currentDate > $codigoDateExpiracao)
        {
            return response()->json(['error' => 'Codigo expirado!'], 401);
        }

        $contaDestino = Conta
            ::where('numero', $requestCreateTransferencia['numeroContaDestino'])->first();

        if(!$contaDestino)
        {
                return response()->json(['error' => 'Conta de destino invalida!'], 401);
        }

        $contaOrigem = Conta::
            where('numero', $codigoTransacao['numero_conta_origem'])->first();
  

        if($contaOrigem['saldo'] < $requestCreateTransferencia['valor'])
        {
            return response()
            ->json(['error' =>
                 'Nao e possivel realizar o deposito. Saldo insuficiente!']); 
        }

        // Salvando antes de debitar
        $saldoOrigemAnteriorDebito = $contaOrigem['saldo'];

        // Atualizando saldos
        $contaOrigem['saldo'] -= $requestCreateTransferencia['valor'];
        $contaDestino['saldo'] += $requestCreateTransferencia['valor'];

        // Criando objeto transação
        $transacao['conta_origem_id'] = $contaOrigem['id'];
        $transacao['conta_destino_id'] = $contaDestino['id'];
        $transacao['tipo'] =  $codigoTransacao['tipo'];
        $transacao['valor'] = $requestCreateTransferencia['valor'];;
        $transacao['saldo_origem_anterior'] = $saldoOrigemAnteriorDebito;
        $transacao['saldo_origem_posterior']= $contaOrigem['saldo'];
        
        // Debitando
        Conta::where('numero', $contaOrigem['numero'])->first()
        ->update(['saldo' => $contaOrigem['saldo']]);

        // Creditando
        Conta::where('numero', $contaDestino['numero'])->first()
        ->update(['saldo' => $contaDestino['saldo']]);

        // Salvando
        $this->transacao->create($transacao);
        
        return response()
        ->json(['data' => [
                    'message' => 'Transferencia feita com sucesso!']
               ]);
    
    }

    private function validarCodigo($pattern, $codigo){
        if ($codigo && preg_match($pattern, $codigo)) {
            return true;
        }
        return false;
    }
}   