@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Produtos 
                    <span class="float-right">
                        <button type="submit" form="productEdit" class="btn btn-primary"><span class="oi oi-check"></span></button>
                        <a href="{{ url('products') }}" class="btn btn-danger ml-1" data-toggle="tooltip" data-placement="top" title="Fechar"><span class="oi oi-x"></span></a>
                    </span>
                </div>
                <div class="card-body">
                    
                    <form action="{{ url('products/update') }}" method="post" id="productEdit">
                    
                        @csrf
                
                        @isset($id)
                            <input type="hidden" name="id" value="{{ $id }}">
                        @endisset
                
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Código</label>
                                    <input type="text" class="form-control" name="codigo" value="{{ $produto->codigo }}" required>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Descrição</label>
                                    <input type="text" class="form-control" name="descricao" value="{{ $produto->descricao }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>EAN</label>
                                    <input type="text" class="form-control" name="ean" value="{{ $produto->ean }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>EAN Unidade Tributável</label>
                                    <input type="text" class="form-control" name="ean_unidade_tributavel" value="{{ $produto->ean_unidade_tributavel }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Código Exceção da Tabela de IPI</label>
                                    <input type="text" class="form-control" name="ex_tipi" value="{{ $produto->ex_tipi }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Gênero</label>
                                    <input type="text" class="form-control" name="genero" value="{{ $produto->genero }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>NCM</label>
                                    <input type="text" class="form-control" name="ncm" value="{{ $produto->ncm }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CEST</label>
                                    <input type="text" class="form-control" name="cest" value="{{ $produto->cest }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Unidade Comercial</label>
                                    <input type="text" class="form-control" name="unidade_comercial" value="{{ $produto->unidade_comercial }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor Unitário Comercial</label>
                                    <input type="number" class="form-control" name="valor_unitario_comercial" value="{{ $produto->valor_unitario_comercial }}">
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Unidade Tributável</label>
                                    <input type="text" class="form-control" name="unidade_tributavel" value="{{ $produto->unidade_tributavel }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Quantidade Tributável</label>
                                    <input type="number" class="form-control" name="quantidade_tributavel" value="{{ $produto->quantidade_tributavel }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor Unitário Tributável</label>
                                    <input type="number" class="form-control" name="valor_unitario_tributavel" value="{{ $produto->valor_unitario_tributavel }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>IPI Código Enquad. Legal</label>
                                    <input type="text" class="form-control" name="ipi_codigo_enquadramento_legal" value="{{ $produto->ipi_codigo_enquadramento_legal }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>IPI CNPJ Produtor</label>
                                    <input type="text" class="form-control" name="ipi_cnpj_produtor" value="{{ $produto->ipi_cnpj_produtor }}">
                                </div>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection