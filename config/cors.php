<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Aquí configuramos los ajustes para compartir recursos entre orígenes.
    | Ajustado para permitir credenciales (sesiones/cookies) con Sanctum.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],

    'allowed_methods' => ['*'],

    // Cambiamos '*' por tu puerto específico de React para mayor seguridad
    'allowed_origins' => ['http://localhost:5173'], 

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // ESTO ES LO QUE HACE QUE EL LOGIN FUNCIONE:
    'supports_credentials' => true, 

];