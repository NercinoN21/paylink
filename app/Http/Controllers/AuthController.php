<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User; 

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $credencias = $request->only('email', 'senha');
        $user = User::where('email', $credencias['email'])->first();
        //return $user;

        if (!$user || strcmp($credencias['senha'], $user->senha)) {
            
            return response()->json(['error' => 'Credenciais invalidas'], 401);
        }

        return response()->json(['Sucesso' => 'Credenciais validas'], 200);
    }
}