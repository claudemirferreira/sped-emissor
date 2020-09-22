<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductIcm extends Model
{
    protected $fillable = [
        'product_id',
        'selecionado',
        'regime',
        'situacao_tributaria',
        'origem',
    ];
}
