<h1 align="center">Forum With Laravel and TDD</h1>

[![Build Status](https://travis-ci.org/rocheleedenis/forum-laravel-with-tdd.svg?branch=master)](https://travis-ci.org/rocheleedenis/forum-laravel-with-tdd)

Aplicação criada a partir do curso [<b>Let's Build A Forum with Laravel and TDD</b>](https://laracasts.com/series/lets-build-a-forum-with-laravel).

## Instalação
### Configuração

``` bash
# Instalar dependências do projeto
composer install
# Atualizar lista de classes
composer dump-autoload

# Configurar variáveis de ambiente
cp .env.example .env
php artisan key:generate

# Gerar banco de dados
php artisan migrate:refresh --seed
```

#### Instale o Redis

É importante que você tenha o Redis instalado em sua máquina. Mais detalhes sobre ele em https://laravel.com/docs/5.6/redis.

## Login
O usuário de teste é:
```
email   : rochele@gmail.com
password: secret
```

## Funções especiais

``` bash
# Autentica um usuário qualquer
$this->signIn();

# Salva registro fake no banco de dados
create('App\Model', ['attr' => $value], $quantidade);

# Cria registro fake
make('App\Model', ['attr' => $value], $quantidade);
```
