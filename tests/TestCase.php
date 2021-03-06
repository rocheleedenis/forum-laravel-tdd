<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Configuração padrão para os testes.
     */
    protected function setUp()
    {
        parent::setUp();

        \DB::statement('PRAGMA foreign_keys=on');

        $this->withoutExceptionHandling();
    }

    /**
     * Autentica um usuário.
     *
     * @param \App\User|null $user
     * @return TestCase
     */
    protected function signIn($user = null)
    {
        $user = $user ?: create('App\User');

        $this->actingAs($user);

        return $this;
    }
}
