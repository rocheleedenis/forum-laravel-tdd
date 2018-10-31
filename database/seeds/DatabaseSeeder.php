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

        $threads = factory('App\Thread', 50)->create();
        $threads->each(function ($thread) { factory('App\Reply', 10)->create(['thread_id' => $thread->id]); });
    }
}
