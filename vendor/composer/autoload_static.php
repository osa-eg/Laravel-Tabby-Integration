<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbade36846bfa78fc1b511144c227acc5
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Osama\\LaravelTabbyIntegration\\' => 30,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Osama\\LaravelTabbyIntegration\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitbade36846bfa78fc1b511144c227acc5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbade36846bfa78fc1b511144c227acc5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbade36846bfa78fc1b511144c227acc5::$classMap;

        }, null, ClassLoader::class);
    }
}
