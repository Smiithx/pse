<?php

use Faker\Generator as Faker;

$factory->define(\App\Person::class, function (Faker $faker) {
    return [
        "document" =>  $faker->numerify('#########'),
        "documentType" => $faker->randomElement(["CC","CE","TI","PPN","NIT","SSN"]),
        "firstName" => "$faker->firstName $faker->firstName",
        "lastName" => "$faker->lastName $faker->lastName",
        "company" => $faker->company,
        "emailAddress" => $faker->unique()->safeEmail,
        "address" => $faker->address,
        "city" => $faker->city,
        "province" => $faker->city,
        "country" => $faker->randomElement((new \League\ISO3166\ISO3166)->all())["alpha2"],
        "phone" => $faker->phoneNumber,
        "mobile" => $faker->e164PhoneNumber,
    ];
});
