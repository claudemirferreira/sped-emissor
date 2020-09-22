<?php

use Faker\Generator as Faker;

/* Na pasta do projeto, para criar 100 registros para teste.
 * executar: php artisan tinker
 * factory(App\Product::class, 100)->create();
 * exit
*/
$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'codigo' => $faker->unique()->numberBetween(1, 100),
        'descricao' => $faker->sentence(3)
    ];
});
