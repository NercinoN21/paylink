<?php
namespace App\Http\Controllers;

use App\Http\Auth\AuthTokenException;
use App\Http\Auth\AuthTokenService;
use Illuminate\Http\Request;


use App\Models\User;

class UsersController extends Controller
{
    private  $user;
    private  $authTokenService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, AuthTokenService $authTokenService)
    {
        $this->user = $user;
        $this->authTokenService = $authTokenService;
    }


    public function listAll(Request $request)
    {
        try{
            $token = $request->header('Authorization');
            $this->authTokenService->validar($token);
            return $this->user->paginate(10);
        }catch(AuthTokenException $e){
            return response()->json(['error' => $e->getMessage()], 401);
        }
        
    }


    public function listSpecific($id, Request $request)
    {
        try{
            $token = $request->header('Authorization');
            $this->authTokenService->validar($token);
            return $this->user->findOrFail($id);
        }catch(AuthTokenException $e){
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }


    public function createUser(Request $request)
    {
        try{
            $token = $request->header('Authorization');
            $this->authTokenService->validar($token);

            $request['senha'] = password_hash($request['senha'], PASSWORD_BCRYPT);
            $this->user->create($request->all());

            //mensagem de criacao...
            return response()
                        ->json(['data' => [
                                    'message' => 'Usuario foi criado com sucesso!']
                           ]);
        }catch(AuthTokenException $e){
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }


    public function updateUser($id, Request $request)
    {
        try{
            $token = $request->header('Authorization');
            $this->authTokenService->validar($token);
            $user = $this->user->find($id);

            $user->update($request->all());

            return response()
                ->json([
                    'data' => [
                        'message' => 'Usuario foi atualizado com sucesso!'
                    ]
                ]);
        }catch(AuthTokenException $e){
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function deleteUser($id, Request $request)
    {
        try{
            $token = $request->header('Authorization');
            $this->authTokenService->validar($token);
            $user = $this->user->find($id);

            $user->delete();

            return response()
                ->json([
                    'data' => [
                        'message' => 'Usuario foi removido com sucesso!'
                    ]
            ]);
        }catch(AuthTokenException $e){
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}

