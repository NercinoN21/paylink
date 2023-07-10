<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Agencia; 

class AgenciaController extends Controller
{
    
    private  $agencia;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Agencia $agencia)
    {
        $this->agencia = $agencia;
    }


    public function listAll()
    {
        return $this->agencia->paginate(10);
    }
}