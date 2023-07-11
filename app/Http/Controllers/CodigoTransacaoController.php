<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CodigosTransacoes; 
use App\Http\Auth\AuthTokenException;
use App\Http\Auth\AuthTokenService;

class CodigoTransacaoController extends Controller
{
    
    private  $codigosTransacoes;
    private  $authTokenService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CodigosTransacoes $codigosTransacoes,AuthTokenService $authTokenService)
    {
        $this->codigosTransacoes = $codigosTransacoes;
        $this->authTokenService = $authTokenService;
    }


    public function listAll(Request $request)
    {
        try{
            $token = $request->header('Authorization');
            $this->authTokenService->validar($token);
            return $this->codigosTransacoes->paginate(10);
        }catch(AuthTokenException $e){
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}