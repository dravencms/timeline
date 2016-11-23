<?php

namespace App\Model\Timeline\Repository;

use App\Model\BaseRepository;
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
class TimelineRepository extends BaseRepository implements ICmsComponentRepository
{
    /** @var \Kdyby\Doctrine\EntityRepository */
    private $timelineRepository;

    /** @var EntityManager */
    private $entityManager;

    /**
     * MenuRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->timelineRepository = $entityManager->getRepository(Timeline::class);
    }

    /**
     * @param $id
     * @return mixed|null|Timeline
     */
    public function getOneById($id)
    {
        return $this->timelineRepository->find($id);
    }

    /**
     * @param $id
     * @return Timeline[]
     */
    public function getById($id)
    {
        return $this->timelineRepository->findBy(['id' => $id]);
    }

    /**
     * @param $name
     * @param ILocale $locale
     * @param Group $group
     * @param Timeline|null $timelineIgnore
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isNameFree($name, ILocale $locale, Group $group, Timeline $timelineIgnore = null)
    {
        $qb = $this->timelineRepository->createQueryBuilder('t')
            ->select('t')
            ->where('t.name = :name')
            ->andWhere('t.group = :group')
            ->setParameters([
                'name' => $name,
                'group' => $group
            ]);

        if ($timelineIgnore) {
            $qb->andWhere('t != :timelineIgnore')
                ->setParameter('timelineIgnore', $timelineIgnore);
        }

        $query = $qb->getQuery();

        $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale->getLanguageCode());

        return (is_null($query->getOneOrNullResult()));
    }

    /**
     * @param Group $group
     * @return \Kdyby\Doctrine\QueryBuilder
     */
    public function getTimelineQueryBuilder(Group $group)
    {
        $qb = $this->timelineRepository->createQueryBuilder('t')
            ->select('t')
            ->where('t.group = :group')
            ->setParameter('group', $group);
        return $qb;
    }

    /**
     * @param integer $id
     * @param bool $isActive
     * @return null|Timeline
     */
    public function getOneByIdAndActive($id, $isActive = true)
    {
        return $this->timelineRepository->findOneBy(['id' => $id, 'isActive' => $isActive]);
    }

    /**
     * @param bool $isActive
     * @return Timeline[]
     * @deprecated do filtering in Repository
     */
    public function getAllByActive($isActive = true)
    {
        return $this->timelineRepository->findBy(['isActive' => $isActive]);
    }

    /**
     * @param bool $isActive
     * @param array $parameters
     * @return Timeline
     * @deprecated
     */
    public function getOneByActiveAndParameters($isActive = true, array $parameters = [])
    {
        $parameters['isActive'] = $isActive;
        return $this->timelineRepository->findOneBy($parameters);
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
                foreach ($this->timelineRepository->findBy(['isActive' => true]) AS $timeline) {
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
        $found = $this->findTranslatedOneBy($this->timelineRepository, $locale, $parameters + ['isActive' => true]);

        if ($found) {
            return new CmsActionOption($found->getName(), $parameters);
        }

        return null;
    }
}