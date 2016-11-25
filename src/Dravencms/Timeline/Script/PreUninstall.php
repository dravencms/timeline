<?php
namespace Dravencms\Timeline\Script;

use Dravencms\Model\User\Repository\AclResourceRepository;
use Dravencms\Packager\IPackage;
use Dravencms\Packager\IScript;
use Kdyby\Doctrine\EntityManager;

/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */
class PreInstall implements IScript
{
    private $aclResourceRepository;

    private $entityManager;

    public function __construct(EntityManager $entityManager, AclResourceRepository $aclResourceRepository)
    {
        $this->entityManager = $entityManager;
        $this->aclResourceRepository = $aclResourceRepository;
    }

    public function run(IPackage $package)
    {
        $aclResource = $this->aclResourceRepository->getOneByName('timeline');

        foreach($aclResource->getAclOperations() AS $aclOperation)
        {
            foreach ($aclOperation->getGroups() AS $group)
            {
                $aclOperation->removeGroup($group);
            }

            foreach ($aclOperation->getAdminMenus() AS $adminMenu)
            {
                $this->entityManager->remove($adminMenu);
            }

            $this->entityManager->remove($aclOperation);
        }

        $this->entityManager->remove($aclResource);
        $this->entityManager->flush();
    }
}