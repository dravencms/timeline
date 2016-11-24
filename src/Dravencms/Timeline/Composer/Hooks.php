<?php

namespace Dravencms\Timeline\Composer;

/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */
class Hooks
{
    public static function postPackageInstall()
    {
        echo 'CALLED Install'.PHP_EOL;
    }

    public static function prePackageUninstall()
    {
        echo 'CALLED Uninstall'.PHP_EOL;
    }
}