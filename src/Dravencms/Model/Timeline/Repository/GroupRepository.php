<?php

namespace Dravencms\Model\Timeline\Repository;

use Dravencms\Model\Timeline\Entities\Group;
use Kdyby\Doctrine\EntityManager;
use Salamek\Cms\CmsActionOption;
use Salamek\Cms\ICmsActionOption;
use Salamek\Cms\ICmsComponentRepository;
use Salamek\Cms\Models\ILocale;

/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */
class GroupRepository
{
    /** @var \Kdyby\Doctrine\EntityRepository */
    private $groupRepository;

    /** @var EntityManager */
    private $entityManager;

    /**
     * MenuRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->groupRepository = $entityManager->getRepository(Group::class);
    }

    /**
     * @param $id
     * @return mixed|null|Group
     */
    public function getOneById($id)
    {
        return $this->groupRepository->find($id);
    }

    /**
     * @param $id
     * @return Group[]
     */
    public function getById($id)
    {
        return $this->groupRepository->findBy(['id' => $id]);
    }

    /**
     * @return Group[]
     */
    public function getAll()
    {
        return $this->groupRepository->findAll();
    }

    /**
     * @param $name
     * @param Group|null $groupIgnore
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isNameFree($name, Group $groupIgnore = null)
    {
        $qb = $this->groupRepository->createQueryBuilder('g')
            ->select('g')
            ->where('g.name = :name')
            ->setParameters([
                'name' => $name
            ]);

        if ($groupIgnore)
        {
            $qb->andWhere('g != :groupIgnore')
                ->setParameter('groupIgnore', $groupIgnore);
        }

        return (is_null($qb->getQuery()->getOneOrNullResult()));
    }

    /**
     * @return \Kdyby\Doctrine\QueryBuilder
     */
    public function getGroupQueryBuilder()
    {
        $qb = $this->groupRepository->createQueryBuilder('g')
            ->select('g');
        return $qb;
    }
}