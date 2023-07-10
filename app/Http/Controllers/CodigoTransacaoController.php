<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CodigosTransacoes; 

class CodigoTransacaoController extends Controller
{
    
    private  $codigosTransacoes;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CodigosTransacoes $codigosTransacoes)
    {
        $this->codigosTransacoes = $codigosTransacoes;
    }


    public function listAll()
    {
        return $this->codigosTransacoes->paginate(10);
    }
}