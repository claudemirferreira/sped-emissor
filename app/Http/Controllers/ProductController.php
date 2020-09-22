<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /*
     * Listagem de produtos com opção de pesquisa por código e/ou descrição
     */
    public function index(Request $request)
    {
        /*
         * Search options
         */
        if ($request->isMethod('post')) { // <- Se for Post, formulário de busca foi submetido
            $request->session()->put('Product.search', $request->search); // <- Grave a busca na sessão
        } else {// <- Se for Get
            if(!$request->session()->exists('Product.search')) { // <- Verifique se existe a sessão
                $request->session()->put('Product.search', null); // <- Se não existir, crie com valor null
            }
        }
        $search = $request->session()->get('Product.search'); // <- Por fim, leia a sessão
        
        /*
         * Query options
         */
        $query = Product::select('codigo', 'descricao', 'id');
        if($search) {
            $query = $query->orWhere('codigo', 'like', '%' . $search . '%');
            $query = $query->orWhere('descricao', 'like', '%' . $search . '%');
        }
        $produtos = $query->orderBy('descricao')->paginate(20);

        return view('products.index', compact('produtos', 'search'));
    }

    /*
     * Visualiza um produto específico pelo id
     */
    public function show($id)
    {
        $produto = Product::findOrfail($id);
        return view('products.show', compact('produto'));
    }

    /*
     * Editar/Criar produto
     * Se existir $id, trata-se de edição, se id for null, estará criando um novo produto
     */
    public function edit($id = null)
    {
        if($id) {
            $produto = Product::findOrfail($id);
        } else {
            $produto = new Product();
        }
        
        return view('products.edit', compact('id', 'produto'));
    }

    /*
     * Update produto
     * Se existir $request->id, trata-se de edição, senão, estará criando um novo produto
     */
    public function update(Request $request)
    {
        $request->validate([
            'codigo' => 'required', // <- Este campo é requerido
            'descricao' => 'required', // <- Este campo é requerido
        ]);
        
        $input = $request->all(); // <- Pegue os valores do post

        if(isset($request->id)) { // <- Produto existe, faremos update
            $produto = Product::findOrfail($request->id); // <- Recupere o produto
            $produto->fill($input); // <- Substitua os valores recuperados pelos valores do post 
        } else { // <- Produto não existe, faremos insert
            $produto = new Product($input); // <- Crie o produto e atribua os valores do post
        }
        
        if($produto->save()) {
            return redirect('products')->with('success', 'Produto salvo');
        } else {
            return redirect()->back()->withInput()->with('error', 'Falha ao salvar produto');
        }
    }

    /*
     * Delete o produto pelo id
     */
    public function destroy($id)
    {
        $produto = Produto::findOrfail($id);
        
        if($produto->delete()) {
            return redirect('products')->with('success', 'Produto removido');
        } else {
            return redirect('products')->with('error', 'Não foi possível remover o Produto');
        }
    }
}
