<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Enderecos; 

use Illuminate\Support\Facades\Http;

class EnderecoController extends Controller
{
    
    private  $endereco;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Enderecos $endereco)
    {
        $this->endereco = $endereco;
    }


    public function listAll()
    {
        return $this->endereco->paginate(10);
    }

    public function createEndereco(Request $request)
    {
        $endereco = $request->only('cep', 'complemento', 'numero');

        try {
            $response = Http::get("https://viacep.com.br/ws/{$endereco['cep']}/json/");
            $data = $response->json();
            
            if($data){
            // Acesso aos dados de endereço
                $endereco['logradouro'] = $data['logradouro'];
                $endereco['bairro'] = $data['bairro'];
                $endereco['cidade'] = $data['localidade'];
                $endereco['uf'] = $data['uf'];
                
                $this->endereco->create($endereco);

                return response()
                ->json(['data' => [
                            'message' => 'Endereco foi criado com sucesso!']
                       ]);
            } else {
                return response()
                    ->json(['data' => [
                        'message' => "Endereco com o cep  {$endereco['cep']} nao encontrado"]
                ]);
            }
        
        } catch (\Exception $e) {
            // Erros de requisição ou de processamento
            $error = $e->getMessage();
        
            return response()->json(['error' => $error], 500);
        }

    }
}