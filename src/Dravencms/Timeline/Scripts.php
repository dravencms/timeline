<?php

namespace Dravencms\Timeline;

/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */
class Scripts
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