<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Auth\AuthTokenService;

use App\Models\User; 

class AuthController extends Controller
{
    private  $authTokenService;
    
    public function __construct(AuthTokenService $authTokenService)
    {
        $this->authTokenService = $authTokenService;
    }

    public function login(Request $request)
    {

        $credencias = $request->only('email', 'senha');
        $user = User::where('email', $credencias['email'])->first();

        if (!$user || !password_verify($credencias['senha'], $user->senha)) {
            
            return response()->json(['error' => 'Credenciais invalidas'], 401);
        }

        $token = $this->authTokenService->criarToken($user);

        return response()->json(['token' => $token], 200);
    }
}