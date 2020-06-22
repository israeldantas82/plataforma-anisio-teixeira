<?php

namespace App\Http\Controllers;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\ConteudoLike;

class ConteudoLikeController extends ApiController
{
    private $conteudoLike;
    private $request;

	public function __construct(Request $request, ConteudoLike $conteudoLike)
    {
        $this->conteudoLike = $conteudoLike; 
        $this->request = $request;

        $this->middleware('auth:api')->except([
            'like', 
            'deslike'
        ]);
        $request = $request;
    }

    public function like()
    {
    	try {
    		$this->conteudoLike->like($this->request);
    		return $this->successResponse([], 'Ação realizada com sucesso!', 200);

    	} catch(\Exception $e) {
    		return $this->errorResponse([], 'Não foi possível criar o comentário!', 422);
    	}
    }

    public function deslike()
    {

    }
}
