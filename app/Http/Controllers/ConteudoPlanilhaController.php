<?php

namespace App\Http\Controllers;

use App\Conteudo;
use App\Helpers\Autocomplete;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\FileSystemLogic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\ConteudoPlanilha;

class ConteudoPlanilhaController extends ApiController
{
    use FileSystemLogic;

    public function __construct(Request $request)
    {
        $this->middleware('auth:api')->except([
            'buscarJsonFaculdadesDaBahiaNoGoogleSpreadsheets',
            'conteudos',
            'buscarJsonRotinaDeEstudosNoGoogleSpreadsheets'
        ]);
        $request = $request;
    }

    public function buscarJsonFaculdadesDaBahiaNoGoogleSpreadsheets($googleKey)
    {
        $conteudoPlanilha = new ConteudoPlanilha();
        $this->create($conteudoPlanilha->formatarJsonFaculdadesDaBahia(
            $conteudoPlanilha->buscarJsonNoGoogleSpreadsheets($googleKey)
        ));
    }

    public function buscarJsonRotinaDeEstudosNoGoogleSpreadsheets($googleKey)
    {
        $conteudoPlanilha = new ConteudoPlanilha();
        $dados = $conteudoPlanilha->buscarJsonNoGoogleSpreadsheets($googleKey);

        $json = $conteudoPlanilha->formatarJsonRotinasDeEstudo($dados);

        dd($json);
        
    }

    public function create($dados)
    {
        foreach ($dados as $dado) {
            $conteudoPlanilha = new ConteudoPlanilha();
            $conteudoPlanilha->name = $dado['name'];
            $conteudoPlanilha->document = ['actions' => $dado['actions']];

            $conteudoPlanilha->save();
        }
    }

    public function conteudos(Request $request)
    {
        $conteudoPlanilha = new ConteudoPlanilha();
        echo json_encode($conteudoPlanilha->conteudos()->paginate($request->query('page')));
    }
}