<?php

namespace App\Model\Timeline\Repository;

use App\Model\BaseRepository;
use Salamek\Cms\ICmsComponentRepository;
use Salamek\Cms\Models\ILocale;

/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */
class TimelineRepository extends BaseRepository implements ICmsComponentRepository
{
    public function getActionOption($componentAction, array $parameters, ILocale $locale)
    {
        // TODO: Implement getActionOption() method.
    }

    public function getActionOptions($componentAction)
    {
        // TODO: Implement getActionOptions() method.
    }

    public function getTest()
    {
        return 'AHOJ';
    }
}