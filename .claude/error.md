# Illuminate\Database\QueryException - Internal Server Error

SQLSTATE[42S02]: Base table or view not found: 1146 Table 'bar_management.servicio_especials' doesn't exist (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: bar_management, SQL: select * from `servicio_especials` where `servicio_especials`.`anfitriona_id` = 3 and `servicio_especials`.`anfitriona_id` is not null)

PHP 8.2.12
Laravel 12.56.0
127.0.0.1:8000

## Stack Trace

0 - vendor\laravel\framework\src\Illuminate\Database\Connection.php:838
1 - vendor\laravel\framework\src\Illuminate\Database\Connection.php:794
2 - vendor\laravel\framework\src\Illuminate\Database\Connection.php:411
3 - vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3505
4 - vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3490
5 - vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:4080
6 - vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3489
7 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:902
8 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:884
9 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\HasMany.php:41
10 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Concerns\HasAttributes.php:637
11 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Concerns\HasAttributes.php:575
12 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Concerns\HasAttributes.php:494
13 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:2423
14 - resources\views\pos\index.blade.php:42
15 - vendor\laravel\framework\src\Illuminate\Filesystem\Filesystem.php:123
16 - vendor\laravel\framework\src\Illuminate\Filesystem\Filesystem.php:124
17 - vendor\laravel\framework\src\Illuminate\View\Engines\PhpEngine.php:57
18 - vendor\laravel\framework\src\Illuminate\View\Engines\CompilerEngine.php:76
19 - vendor\laravel\framework\src\Illuminate\View\View.php:208
20 - vendor\laravel\framework\src\Illuminate\View\View.php:191
21 - vendor\laravel\framework\src\Illuminate\View\View.php:160
22 - vendor\laravel\framework\src\Illuminate\Http\Response.php:78
23 - vendor\laravel\framework\src\Illuminate\Http\Response.php:34
24 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:939
25 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:906
26 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
27 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
28 - vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
29 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
30 - vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php:63
31 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
32 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
33 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
34 - vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
35 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
36 - vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
37 - vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
38 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
39 - vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
40 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
41 - vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
42 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
43 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
44 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
45 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
46 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
47 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
48 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
49 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
50 - app\Http\Middleware\UsuarioActivo.php:18
51 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
52 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
53 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
54 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
55 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
56 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
57 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
58 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
59 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
60 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
61 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
62 - vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:61
63 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
64 - vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
65 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
66 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
67 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
68 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
69 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
70 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
71 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
72 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
73 - vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
74 - public\index.php:20
75 - vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php:23

## Request

GET /pos

## Headers

* **host**: 127.0.0.1:8000
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
* **accept-language**: es-ES,es;q=0.9,en-US;q=0.8,en;q=0.7
* **accept-encoding**: gzip, deflate, br, zstd
* **connection**: keep-alive
* **referer**: http://127.0.0.1:8000/
* **cookie**: XSRF-TOKEN=eyJpdiI6IkJMNlNMbDZ1Z0RQUDlGREhTZ3pncUE9PSIsInZhbHVlIjoidDYzQ2FnU2F6OVZQa0JkaWR0T3JDY3c2NW9YWEhQR1dFK2NNdk1Cc29qQTBMajNwS2xTVmpSYXBld1FHQi9EWUVyclFuZDBOMGpMRWtPemZDc1lKMHRxZzUyNCt2L2VSN2tuTEcwQlZtUU5Gak44VENJa2NyTDk2TmkvTUdad2siLCJtYWMiOiIzN2RlNmI4OTMwYWVmMDM4ZDBkZTUyNDZjMjc0YWM2ZDUxYTM2ZDIxYmM1MjEzNTIzYmMzMTg0NDEyNWZmMzhkIiwidGFnIjoiIn0%3D; bar-management-session=eyJpdiI6Ikd6dWVGK1JMZjBhYnBsUTFoZVZkS1E9PSIsInZhbHVlIjoicXAxbklsZ2JxVmVWTEhqVkM0MVZOb29IY3JVa2E2Q2lwSEpEang3L3BLREhNR1QrVkhqLzc3YjBNQ3Z3aXpNSlRYdGdjQjF0b3lEb2x6MU5TUmYxbWswcVdQZE9RWVIxcUpZWUVOQkQxWkNCcHV1dzIrZWhwaGdzY0VyR0JzRnYiLCJtYWMiOiIwYTAzMzRmNTVhODY3ZDM2ODYxM2RmNzYyY2IwZGQyNGRmYmMxMWQ2NzlkZGFiOWFiYTUxNWVkMTAzZDA5MzQ3IiwidGFnIjoiIn0%3D
* **upgrade-insecure-requests**: 1
* **sec-fetch-dest**: document
* **sec-fetch-mode**: navigate
* **sec-fetch-site**: same-origin
* **sec-fetch-user**: ?1
* **priority**: u=0, i

## Route Context

controller: App\Http\Controllers\PosController@index
route name: pos.index
middleware: web, auth

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `sessions` where `id` = 'aZhZ7j4z7BDEwmdUCx5G1LLADkFsNBZUhs6Er6lf' limit 1 (1.44 ms)
* mysql - select * from `users` where `id` = 2 limit 1 (0.36 ms)
* mysql - select * from `anfitrionas` where `activa` = 1 order by `alias` asc (0.29 ms)
* mysql - select * from `productos` where `activo` = 1 order by `categoria` asc, `nombre` asc (0.26 ms)
