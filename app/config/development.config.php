<?php

return [
    // Additional modules to include when in development mode
//    'modules' => [
//        'ZendDeveloperTools',
//    ],
//    // Configuration overrides during development mode
//    'module_listener_options' => [
//        'config_glob_paths' => [realpath(__DIR__) . '/autoload/{,*.}{global,local}-development.php'],
//        'config_cache_enabled' => false,
//        'module_map_cache_enabled' => false,
//    ],

    //  Default MongoDB connection for users
    //      and the master connection for web access.
    'mongo_db' => [
        'host' => 'mongodb://127.0.0.1:27017',
        'database' => 'middle_dev',
    ],

];
