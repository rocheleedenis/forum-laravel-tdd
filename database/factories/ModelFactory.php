<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name'           => $faker->firstName,
        'email'          => $faker->unique()->safeEmail,
        'password'       => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'confirmed'      => true
    ];
});

$factory->state(App\User::class, 'unconfirmed', function () {
    return [
        'confirmed' => false
    ];
});

$factory->define(App\Thread::class, function ($faker) {
    $title = $faker->sentence;

    return [
        'title'      => $title,
        'body'       => $faker->paragraph,
        'visits'     => 0,
        'slug'       => str_slug($title),
        'channel_id' => function () {
            return factory('App\Channel')->create()->id;
        },
        'user_id'    => function () {
            return factory('App\User')->create()->id;
        }
    ];
});

$factory->define(App\Reply::class, function ($faker) {
    return [
        'body'      => $faker->paragraph,
        'user_id'   => function () {
            return factory('App\User')->create()->id;
        },
        'thread_id' => function () {
            return factory('App\Thread')->create()->id;
        }
    ];
});

$factory->define(App\Channel::class, function ($faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => $name
    ];
});

$factory->define(Illuminate\Notifications\DatabaseNotification::class, function ($faker) {
    return [
        'id'              => Illuminate\Support\Str::uuid()->toString(),
        'type'            => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id'   => function () {
            return auth()->id() ?: factory('App\User')->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data'            => ['foo' => 'bar']
    ];
});