# Forum

1. Thread
2. Reply
3. User

A. Thread is created by a user
B. A reply belongs to a thread, and belongs to a user

===================================================

# First lesson
php artisan make:model Thread -mr // cria o model, controller e migration de uma vez
php artisan make:model Reply -mr
mysql -uroot -p // create database forum
php artisan migrate:refresh
php artisan tinker
$threads = factory('App\Thread', 50)->create();
$threads->each(function ($thread) { factory('App\Reply', 10)->create(['thread_id' => $thread->id]); });

$faker = Faker\Factory::create();

===================================================

# 07 Lesson
On composer.json file add
...
"autoload-dev": {
	...,
	"files": [
		"tests/utilities/functions.php"
	]
}

And run
composer dump-autoload

See TestCase.php

====================================================

Case: Module build failed: Error: No parser and no file path given, couldn't infer a parser.

Go to: node_modules\vue-loader\lib\template-compiler
Open index.js and look for:

// prettify render fn
if (!isProduction) {
	code = prettier.format(code, { semi: false})
}

and change the lines to:

// prettify render fn
if (!isProduction) {
	code = prettier.format(code, { semi: false, parser: 'babylon' })
}
