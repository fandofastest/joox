<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3918b774142c3082d7ccd8dcf913f894
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'JooxAPIx\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'JooxAPIx\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3918b774142c3082d7ccd8dcf913f894::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3918b774142c3082d7ccd8dcf913f894::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
