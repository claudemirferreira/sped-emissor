<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'codigo',
        'descricao',
        'ean',
        'ean_unidade_tributavel',
        'ex_tipi',
        'genero',
        'ncm',
        'cest',
        'unidade_comercial',
        'valor_unitario_comercial',
        'unidade_tributavel',
        'quantidade_tributavel',
        'valor_unitario_tributavel',
        'ipi_codigo_enquadramento_legal',
        'ipi_cnpj_produtor',
    ];
}
