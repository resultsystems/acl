# ResultSystems ACL

ACL é um package ACL para Laravel 5 que utiliza filial, grupos e permissões.
Este projeto tem como objetivo prover o controle de acesso da sua aplicação.
Foi desenvolvido pensando em multi-empresa/filiais.

## Instalação

ACL pode ser instalado através do composer. Para que o package seja adicionado automaticamente ao seu arquivo composer.json execute o seguinte comando:

```
composer require resultsystems/acl
```
ou se preferir, adicione o seguinte trecho manualmente:

```
{
    "require": {
        "resultsystems/acl": "~0.21"
    }
}
```

## 2. Provider

Para usar o ACL em sua aplicação Laravel, é necessário registrar o package no seu arquivo config/app.php. Adicione o seguinte código no fim da seção providers

```
// inicio do arquivo foi omitido
    'providers' => [
        //Outras entradas omitidas
        ResultSystems\Acl\AclServiceProvider::class, //Laravel 5.1 em diante
    ],
// fim do arquivo foi omitido
```

## 3. User Class

Na sua classe de usuário, adicione a trait `ResultSystems\Acl\Traits\PermissionTrait` para disponibilizar os métodos para checagem de permissões:

## 4. Publicando o arquivo de configuração e as migrations

Para publicar o arquivo de configuração padrão e as migrations que acompanham o package, execute o seguinte comando:

```
php artisan vendor:publish
ou
php artisan vendor:publish --provider="ResultSystems\Acl\AclServiceProvider"
```

Execute as migrations, para que sejam criadas as tabelas no banco de dados:

php artisan migrate

## 5. Middleware do ACL

Caso você tenha a necessidade de realizar o controle de acesso diretamente nas rotas, o ACL possui um middleware (nativos) que abordam os casos mais comuns. Para utilizá-los é necessário registrá-los no seu arquivo app/Http/Kernel.php.

### Laravel 5.1
```
protected $routeMiddleware = [
    'auth'            => 'App\Http\Middleware\Authenticate',
    'auth.basic'      => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
    'guest'           => 'App\Http\Middleware\RedirectIfAuthenticated',

    // Controle de acesso usando permissões
    'needsPermission' => \ResultSystems\Acl\Middlewares\NeedsPermissionMiddleware::class,
];
```

### Laravel 5.2
```

protected $routeMiddleware = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            // Controle de acesso usando permissões para WEB
            'needsPermission' => \ResultSystems\Acl\Middlewares\NeedsPermissionMiddleware::class,
        ],
        'api' => [
            'throttle:60,1',
            // Controle de acesso usando permissões para API
            'needsPermission' => \ResultSystems\Acl\Middlewares\NeedsPermissionMiddleware::class,
        ],

];
```

## Usando o ACL

Configure o arquivo `config/acl.php`

A utilização desses middlewares é explicada na próxima seção.

#Como usar nas rotas
```
Route::post('/users', ['middleware' => ['auth', 'needsPermission'],
    'permission'               => ['user.read', 'user.create'],
    'any'                      => false, //usuário precisará ter as duas permissões
    /**
     * Caso a configuração em `config/acl.php`
     * middleware->autoload for true,
     * você poderá omitir a informação da branch_id
     */
     'branch_id'                => 'middleware',
    function () {
        dd('Tenho permissão');
    }]);
```

```
Route::post('/users', ['middleware' => ['auth', 'needsPermission'],
    'permission'               => ['user.read', 'user.create'],
    'any'                      => false, //usuário precisará ter as duas permissões
    'branch_id'                => 1, // Empresa/filial (opcional)
    function () {
        dd('Tenho permissão');
    }]);
```

```
Route::get('/users', [
    'middleware' => ['auth', 'needsPermission:user.read|user.create,true,5'],

    function () {
        dd('Tenho permissão');
    }]);

    //needsPermission=middleware
    //user.read=permissão user.read
    //user.create=permissão user.create
    //any=true Usuário pode ter qualquer das permissões informadas
    //5=Filial/Empresa (opcional)
```

## Buscar a filial/branch automaticamente
Configura o middleware a ser usado em `config/acl.php`
```
    'middleware' => [
        //Usar com Auth
        'branch' => ResultSystems\Acl\Middlewares\AuthBranchMiddleware::class,
        //Usar com Jwt
        'branch' => ResultSystems\Acl\Middlewares\JwtBranchMiddleware::class,
        /**
         * Campo no auth/jwt que contém o id da filial
         */
        'branch_id' => 'branch_id',
```

## Usando com a middleware com busca automática
```
Route::post('/users', ['middleware' => ['auth', 'needsPermission'],
    'permission'               => ['user.read', 'user.create'],
    'any'                      => false, //usuário precisará ter as duas permissões
    'branch_id'                => 'middleware',
    function () {
        dd('Tenho permissão');
    }]);
```
#Usar em qualquer lugar com o Auth

Você pode usar em qualquer lugar que o usuário esteja autenticado, usando o Auth.

Exemplos:

```
if (Auth::user()->hasPermission('user.create')) {
    echo 'tenho permissão';
}

if (Auth::user()->hasPermission(['user.create', 'user.update'])) {
    echo 'tenho pelo menos uma das permissões';
}

if (Auth::user()->hasPermission(['user.create', 'user.update'], false)) {
    echo 'tenho ambas as permissões';
}

if (Auth::user()->hasPermission(['user.create', 'user.update'], false, 1)) {
    echo 'tenho ambas as permissões na filial 1';
}

if (Auth::user()->hasPermission(['user.create', 'user.update'], true, 1)) {
    echo 'tenho pelo menos uma das permissões na filial 1';
}
```

### Créditos

Inspirado no [Artesaos/Defender](https://github.com/artesaos/defender).
