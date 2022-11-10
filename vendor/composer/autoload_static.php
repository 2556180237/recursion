<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitacd9ca3e26cc3d41387b1d622b921921
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Ostanin\\Recursion\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ostanin\\Recursion\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitacd9ca3e26cc3d41387b1d622b921921::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitacd9ca3e26cc3d41387b1d622b921921::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitacd9ca3e26cc3d41387b1d622b921921::$classMap;

        }, null, ClassLoader::class);
    }
}
