<?php

namespace Dravencms\Model\Timeline\Repository;

use Dravencms\Locale\TLocalizedRepository;
use Dravencms\Model\Timeline\Entities\Group;
use Dravencms\Model\Timeline\Entities\Timeline;
use Gedmo\Translatable\TranslatableListener;
use Kdyby\Doctrine\EntityManager;
use Salamek\Cms\CmsActionOption;
use Salamek\Cms\ICmsActionOption;
use Salamek\Cms\ICmsComponentRepository;
use Salamek\Cms\Models\ILocale;

/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */
class TimelineCmsRepository implements ICmsComponentRepository
{
    /** @var TimelineRepository */
    private $timelineRepository;

    public function __construct(TimelineRepository $timelineRepository)
    {
        $this->timelineRepository = $timelineRepository;
    }

    /**
     * @param string $componentAction
     * @return ICmsActionOption[]
     */
    public function getActionOptions($componentAction)
    {
        switch ($componentAction) {
            case 'Detail':
            case 'OverviewDetail':
                $return = [];
                /** @var Timeline $timeline */
                foreach ($this->timelineRepository->getActive() AS $timeline) {
                    $return[] = new CmsActionOption($timeline->getName(), ['id' => $timeline->getId()]);
                }
                break;

            case 'Overview':
            case 'SimpleOverview':
            case 'Navigation':
                return null;
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
     * @param ILocale $locale
     * @return null|CmsActionOption
     */
    public function getActionOption($componentAction, array $parameters, ILocale $locale)
    {
        /** @var Timeline $found */
        $found = $this->timelineRepository->findTranslatedOneBy($this->timelineRepository, $locale, $parameters + ['isActive' => true]);

        if ($found) {
            return new CmsActionOption($found->getName(), $parameters);
        }

        return null;
    }
}