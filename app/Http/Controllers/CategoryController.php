<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use App\Traits\FileSystemLogic;
use App\Canal;

class CategoryController extends ApiController
{
    /**
     * Cria, atualiza e deleta informacoes no banco de dados
     * 
     * @param int $id identificador único
     * @param  \App\Controller\CategoryController\ApiResponser
     * retorna json
     */
    use FileSystemLogic;
    protected $request;

    public function rules()
    {
        return [
            'canal_id' => 'required',
            'name'=> 'required|min:2|max:255',
            'imagemAssociada' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
            'videoDestaque' => 'mimes:webpM,mp4|max:51200|nullable'
        ];
    }
    /**
     * Undocumented function
     * Método Construtor
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->middleware('jwt.auth')->except([
            'index', 'search', 'getById', 'getCategoryByCanalId'
        ]);
        $this->request = $request;
    }

    public function index()
    {
        $limit = $this->request->get('limit', 15);
        $categories = Category::whereNull('parent_id')
            ->where('options->is_active', 'true')
            ->with('subCategories')
            ->limit($limit)
            ->orderBy('name', 'asc')
            ->paginate($limit);
        return $this->showAsPaginator($categories);
    }
   /**
    * Undocumented function
    * Criando e validando informações para ser adicionadas no banco de dados
    * @return void
    */
    public function create()
    {
        $validator = Validator::make($this->request->all(), $this->rules());
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), "Não foi possível criar a categoria", 422);
        }
        $category           = new Category;
        $category->name     = $this->request->name;
        $category->canal_id = $this->request->canal_id;
        $category->options  = json_decode($this->request->options);

        if (!$category->save()) {
            return $this->errorResponse([], 'Não foi possível cadastrar a categoria', 422);
        }
        $fileImg = $this->saveFile($category->id, [$this->request->imagemAssociada], 'imagem-associada/categorias');
        if(!$fileImg)
        {
            return $this->errorResponse([], 'Não foi possível salvar imagem associada', 422);
        }   
        if ($this->request->videoDestaque) {
            $fileVideo = $this->saveFile($category->id, [$this->request->videoDestaque], 'visualizacao');
            if (!$fileVideo) {
                return $this->errorResponse([], 'Não foi possível salvar video de destaque', 422);
            }
        }
        return $this->successResponse($category, 'Categoria criada com sucesso!', 200);
    }

    /**
     * Undocumented function
     * Método que atualiza informações da categoria por id listando informações do banco de dados
     * Usando as respectivas exception com seus status code de seguranca
     * @param [type] $id
     * @return void
     */
    public function update($id)
    {
        $validator = Validator::make($this->request->all(), $this->rules());
        if ($validator->fails())
        {
            return $this->errorResponse($validator->errors(), "Não foi possível atualizar a categoria", 422);
        }
        $category = Category::findOrFail($id);
        $category->name = $this->request->name;
        $category->canal_id = $this->request->canal_id;
        $category->options = json_decode($this->request->options);
        if ($this->request->imagemAssociada)
        {
            if($category->refenciaImagemAssociada())
            unlink($category->refenciaImagemAssociada());
            $file = $this->saveFile($category->id, [$this->request->imagemAssociada], 'imagem-associada/categorias');
            if(!$file)
            {
                return $this->errorResponse([], 'Não foi possível salvar imagem associada', 422);
            }
        }
        if ($this->request->videoDestaque) 
        {
            if($category->refenciaVideoDestaque())
            unlink($category->refenciaVideoDestaque());
            $fileVideo = $this->saveFile($category->id, [$this->request->videoDestaque], 'visualizacao');
            if (!$fileVideo) {
                return $this->errorResponse([], 'Não foi possível salvar imagem associada', 422);
            }
        }
        if (!$category->update()) {
            return $this->errorResponse([], 'Não foi possível editar', 422);
        }
        return $this->successResponse($category, 'Categoria atualizada com sucesso!', 200);
    }
    /**
     * Metodo delete informacoces que podem ser deletadas no banco de dados
     */

    public function delete($id)
    {
        $validator = Validator::make($this->request->all(), [
            'delete_confirmation' => ['required', new \App\Rules\ValidBoolean]
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), "Não foi possível deletar.", 422);
        }
        $category = Category::findOrFail($id);
        if (!$category->delete()) {
            return $this->errorResponse([], 'Não foi possível deletar a categoria', 422);
        }
        if($category->refenciaImagemAssociada())
            unlink($category->refenciaImagemAssociada());
        if($category->refenciaVideoDestaque())
        unlink($category->refenciaVideoDestaque());
        return $this->successResponse($category, 'Categoria deletada com sucesso!', 200);
    }

    public function getById($id)
    {
        $category = Category::findOrFail($id);
        $category->canal;
        return  $category;
    }

    public function getCategoryByCanalId($id)
    {
        $categories = Category::with('subCategories')->where('canal_id', $id)
        ->where('options->is_active', 'true')
        ->whereNull('parent_id')
        ->orderBy('name')
        ->get();
        
        $canal = Canal::where('id', $id)->get();
        
        $data = collect([
            'category_name' => $canal->pluck('category_name')->first(),
            'categories' => $categories
        ]);

        return $this->showAll($data);
    }

    /**
     * Procura categoria pelo nome
     * @param $request \Illuminate\Http\Request
     * @param $termo string de busca
     * @return App\Traits\ApiResponser
     */
    public function search(Request $request, $termo)
    {
        $limit = ($request->has('limit')) ? $request->query('limit') : 20;
        $search = "%{$termo}%";
        $paginator = Category::whereRaw('unaccent(lower(name)) ILIKE unaccent(lower(?))', [$search])->paginate($limit);
        $paginator->setPath("/categorias/search/{$termo}?limit={$limit}");
        return $this->showAsPaginator($paginator, '', 200);
    }
}
