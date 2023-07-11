<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Conta; 
use App\Http\Auth\AuthTokenException;
use App\Http\Auth\AuthTokenService;

class ContaController extends Controller
{
    
    private  $conta;
    private  $authTokenService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Conta $conta, AuthTokenService $authTokenService)
    {
        $this->conta = $conta;
        $this->authTokenService = $authTokenService;
    }


    public function listAll(Request $request)
    {
        try{
            $token = $request->header('Authorization');
            $this->authTokenService->validar($token);
            return $this->conta->paginate(10);
        }catch(AuthTokenException $e){
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}