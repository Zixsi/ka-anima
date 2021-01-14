<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit41d159b8f6880dd4968c54f6ce355d95
{
    public static $files = array (
        '427321f7ed64127c72d54c0c43673e11' => __DIR__ . '/../..' . '/modules/main/models/pay/PayData.php',
    );

    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'Valitron\\' => 9,
        ),
        'C' => 
        array (
            'Core\\' => 5,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Valitron\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/valitron/src/Valitron',
        ),
        'Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/../system',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit41d159b8f6880dd4968c54f6ce355d95::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit41d159b8f6880dd4968c54f6ce355d95::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
