<?php
namespace Dravencms\Timeline\Script;

use Dravencms\Model\Admin\Repository\MenuRepository;
use Dravencms\Packager\IPackage;
use Dravencms\Packager\IScript;

/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */
class PreInstall implements IScript
{
    public function __construct(MenuRepository $menuRepository)
    {
        dump($menuRepository->getOneByName('Users')->getName());
    }

    public function run(IPackage $package)
    {
        // TODO: Implement run() method.
    }
}