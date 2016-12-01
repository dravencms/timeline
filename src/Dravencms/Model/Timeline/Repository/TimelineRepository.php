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
class TimelineRepository
{
    use TLocalizedRepository;

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
     * @return array|mixed
     */
    public function getActive()
    {
        return $this->timelineRepository->findBy(['isActive' => true]);
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
}