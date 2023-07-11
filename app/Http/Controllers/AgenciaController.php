<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Agencia; 
use App\Http\Auth\AuthTokenException;
use App\Http\Auth\AuthTokenService;

class AgenciaController extends Controller
{
    
    private  $agencia;
    private  $authTokenService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Agencia $agencia, AuthTokenService $authTokenService)
    {
        $this->agencia = $agencia;
        $this->authTokenService = $authTokenService;
    }


    public function listAll(Request $request)
    {
        try{
            $token = $request->header('Authorization');
            $this->authTokenService->validar($token);
            return $this->agencia->paginate(10);
        }catch(AuthTokenException $e){
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}