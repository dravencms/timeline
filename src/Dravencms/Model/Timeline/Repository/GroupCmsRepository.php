<?php

namespace Dravencms\Model\Timeline\Repository;

use Dravencms\Locale\TLocalizedRepository;
use Dravencms\Model\Timeline\Entities\Group;
use Kdyby\Doctrine\EntityManager;
use Salamek\Cms\CmsActionOption;
use Salamek\Cms\ICmsActionOption;
use Salamek\Cms\ICmsComponentRepository;
use Salamek\Cms\Models\ILocale;

/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */
class GroupCmsRepository implements ICmsComponentRepository
{
    /** @var GroupRepository */
    private $groupRepository;

    /**
     * GroupCmsRepository constructor.
     * @param GroupRepository $groupRepository
     */
    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * @param string $componentAction
     * @return ICmsActionOption[]
     */
    public function getActionOptions($componentAction)
    {
        switch ($componentAction)
        {
            case 'Detail':
            case 'SimpleDetail':
                $return = [];
                /** @var Group $group */
                foreach ($this->groupRepository->getAll() AS $group) {
                    $return[] = new CmsActionOption($group->getName(), ['id' => $group->getId()]);
                }
                break;

            default:
                return false;
                break;
        }


        return $return;
    }

    /**
     * @param string $componentAction
     * @param array $parameters
     * @return null|CmsActionOption
     */
    public function getActionOption($componentAction, array $parameters)
    {
        //$found = $this->groupRepository->findTranslatedOneBy($this->groupRepository, $locale, $parameters);
        $found = null; //!FIXME 
        if ($found)
        {
            return new CmsActionOption($found->getName(), $parameters);
        }

        return null;
    }
}
