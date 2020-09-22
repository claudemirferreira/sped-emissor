@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Produtos
                    <a href="{{ url('products/edit') }}" class="btn btn-primary float-right ml-2" data-toggle="tooltip" data-placement="top" title="Novo"><span class="oi oi-pencil"></span></a>
                    <span class="float-right">
                        <form class="float-right justify-content-end" method="post" action="{{ url('products') }}">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" id="search" value="{{ Session::get('Product.search') }}" placeholder="Pesquisar..." autocomplete="off">
                                <div class="input-group-append">
                                    <button class="btn btn-warning" type="submit">
                                        <span class="oi oi-magnifying-glass"></span>
                                    </button>
                                    <button class="btn btn-danger input-group-btn" type="submit" form="resetForm">
                                        <span class="oi oi-fire"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </span>
                    

                    <form method="post" id="resetForm" action="{{ url('products') }}">
                        @csrf
                        <input type="hidden" name="search" value="">
                    </form>

                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12 mb-4">

                        </div>
                    </div>


                    <div class="table-responsive">
                        <div class="card">
                            <table class="table table-sm table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Código</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produtos as $produto)
                                    <tr>
                                        <td class="text-center">
                                            <a href="{{ url('products/edit/' . $produto->id) }}" class="btn btn-link" data-toggle="tooltip" data-placement="right" title="Editar"><span class="oi oi-pencil"></span></a>
                                            <a href="{{ url('products/show/' . $produto->id) }}" class="btn btn-link" data-toggle="tooltip" data-placement="right" title="Visualizar"><span class="oi oi-file"></span></a>
                                        </td>
                                        <td>{{ $produto->codigo }}</td>
                                        <td>{{ $produto->descricao }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mt-4">
                            {{ $produtos->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
