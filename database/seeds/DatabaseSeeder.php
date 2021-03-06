<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        create('App\User', [
            'name'  => 'Rochele',
            'email' => 'rochele@gmail.com'
        ]);
        create('App\User', [
            'name'  => 'JohnDoe',
            'email' => 'john@gmail.com'
        ]);
        create('App\User', [
            'name'  => 'JaneDoe',
            'email' => 'jane@gmail.com'
        ]);

        $threads = factory('App\Thread', 10)->create();
        $threads->each(function ($thread) {
            factory('App\Reply', 5)->create(['thread_id' => $thread->id]);
        });
    }
}
