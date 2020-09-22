<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class NFeController extends Controller
{

    protected static $db;

    /*
     * Listagem de produtos com opção de pesquisa por código e/ou descrição
     */
    public function listNotasPendentes() 
        {
            $db = new Connection();
            $notas = $service->listNotasPendentes();
            return $notas;

        }
        
        

}
