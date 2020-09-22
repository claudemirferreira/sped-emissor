@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Produtos 
                    <a href="{{ url('products') }}" class="btn btn-danger float-right"><span class="oi oi-x"></span></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="card">
                            <table class="table table-sm table-hover">
                                <tbody>
                                    <tr>
                                        <th>Código</th>
                                        <td>{{ $produto->codigo }}</td>
                                    </tr>
                                    <tr>
                                        <th>Descrição</th>
                                        <td>{{ $produto->descricao }}</td>
                                    </tr>
                                    <tr>
                                        <th>EAN</th>
                                        <td>{{ $produto->ean }}</td>
                                    </tr>
                                    <tr>
                                        <th>EAN Unidade Tributável</th>
                                        <td>{{ $produto->ean_unidade_tributavel }}</td>
                                    </tr>
                                    <tr>
                                        <th>Código Exceção da Tabela de IPI</th>
                                        <td>{{ $produto->ex_tipi }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gênero</th>
                                        <td>{{ $produto->genero }}</td>
                                    </tr>
                                    <tr>
                                        <th>NCM</th>
                                        <td>{{ $produto->ncm }}</td>
                                    </tr>
                                    <tr>
                                        <th>CEST</th>
                                        <td>{{ $produto->cest }}</td>
                                    </tr>
                                    <tr>
                                        <th>Unidade Comercial</th>
                                        <td>{{ $produto->unidade_comercial }}</td>
                                    </tr>
                                    <tr>
                                        <th>Valor Unitário Comercial</th>
                                        <td>{{ $produto->valor_unitario_comercial }}</td>
                                    </tr>
                                    <tr>
                                        <th>Unidade Tributável</th>
                                        <td>{{ $produto->unidade_tributavel }}</td>
                                    </tr>
                                    <tr>
                                        <th>Quantidade Tributável</th>
                                        <td>{{ $produto->quantidade_tributavel }}</td>
                                    </tr>
                                    <tr>
                                        <th>Valor Unitário Tributável</th>
                                        <td>{{ $produto->valor_unitario_tributavel }}</td>
                                    </tr>
                                    <tr>
                                        <th>IPI Código Enquad. Legal</th>
                                        <td>{{ $produto->ipi_codigo_enquadramento_legal }}</td>
                                    </tr>
                                    <tr>
                                        <th>IPI CNPJ Produtor</th>
                                        <td>{{ $produto->ipi_cnpj_produtor }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection