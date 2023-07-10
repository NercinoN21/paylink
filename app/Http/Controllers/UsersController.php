<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\User;

class UsersController extends Controller
{
    private  $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function listAll()
    {
        return $this->user->paginate(10);
    }


    public function listSpecific($id)
    {
        return $this->user->findOrFail($id);
    }


    public function createUser(Request $request)
    {
        $this->user->create($request->all());

        //mensagem de criacao...
        return response()
                    ->json(['data' => [
                                'message' => 'Usuario foi criado com sucesso!']
                           ]);
    }


    public function updateUser($id, Request $request)
    {
        $user = $this->user->find($id);

        $user->update($request->all());

        return response()
            ->json([
                'data' => [
                    'message' => 'Usuario foi atualizado com sucesso!'
                ]
            ]);
    }

    public function deleteUser($id)
    {
        $user = $this->user->find($id);

        $user->delete();

        return response()
            ->json([
                'data' => [
                    'message' => 'Usuario foi removido com sucesso!'
                ]
            ]);
    }
}

