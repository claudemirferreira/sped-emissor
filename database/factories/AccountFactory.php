<?php

use Faker\Generator as Faker;

/* Na pasta do projeto, para criar 100 registros para teste.
 * executar: php artisan tinker
 * factory(App\Account::class, 100)->create();
 * exit
*/
$factory->define(App\Account::class, function (Faker $faker) {
    $tipo = $faker->randomElement(['CNPJ', 'CPF']);
    if ($tipo == 'CNPJ') {
        $nome = $faker->company;
        $doc  = $faker->unique()->regexify('([0-9]{2})([0-9]{3})([0-9]{3})(0001)([0-9]{2})');
    } else {
        $gender = $faker->randomElement(['male', 'female']);
        $nome   = $faker->firstName($gender) . ' ' . $faker->lastName;
        $doc    = $faker->unique()->regexify('([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})');
    }
    return [
        'razao_social' => $nome,
        'documento' => $doc,
        'documento_tipo' => $tipo
    ];
});
