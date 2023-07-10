<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Conta; 

class ContaController extends Controller
{
    
    private  $conta;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Conta $conta)
    {
        $this->conta = $conta;
    }


    public function listAll()
    {
        return $this->conta->paginate(10);
    }
}