<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transacoes; 

class TransacaoController extends Controller
{
    
    private  $transacao;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Transacoes $transacao)
    {
        $this->transacao = $transacao;
    }


    public function listAll()
    {
        return $this->transacao->paginate(10);
    }
}