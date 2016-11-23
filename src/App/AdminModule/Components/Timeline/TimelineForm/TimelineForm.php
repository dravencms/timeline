<?php
/*
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
 */

namespace App\AdminModule\Components\Timeline\TimelineForm;

use App\Components\BaseFormFactory;
use App\Model\File\Repository\StructureFileRepository;
use App\Model\Locale\Repository\LocaleRepository;
use App\Model\Timeline\Repository\TimelineRepository;
use Dravencms\Model\Timeline\Entities\Group;
use Dravencms\Model\Timeline\Entities\Timeline;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

/**
 * Description of TimelineForm
 *
 * @author Adam Schubert <adam.schubert@sg1-game.net>
 */
class TimelineForm extends Control
{
    /** @var BaseFormFactory */
    private $baseFormFactory;

    /** @var EntityManager */
    private $entityManager;

    /** @var TimelineRepository */
    private $timelineRepository;

    /** @var StructureFileRepository */
    private $structureFileRepository;

    /** @var LocaleRepository */
    private $localeRepository;

    /** @var Group */
    private $group;

    /** @var Timeline|null */
    private $timeline = null;

    /** @var array */
    public $onSuccess = [];

    /**
     * TimelineForm constructor.
     * @param Group $group
     * @param BaseFormFactory $baseFormFactory
     * @param EntityManager $entityManager
     * @param TimelineRepository $timelineRepository
     * @param StructureFileRepository $structureFileRepository
     * @param LocaleRepository $localeRepository
     * @param Timeline|null $timeline
     */
    public function __construct(
        Group $group,
        BaseFormFactory $baseFormFactory,
        EntityManager $entityManager,
        TimelineRepository $timelineRepository,
        StructureFileRepository $structureFileRepository,
        LocaleRepository $localeRepository,
        Timeline $timeline = null
    ) {
        parent::__construct();

        $this->group = $group;
        $this->timeline = $timeline;

        $this->baseFormFactory = $baseFormFactory;
        $this->entityManager = $entityManager;
        $this->timelineRepository = $timelineRepository;
        $this->structureFileRepository = $structureFileRepository;
        $this->localeRepository = $localeRepository;


        if ($this->timeline) {
            $defaults = [
                'structureFile' => ($this->timeline->getStructureFile() ? $this->timeline->getStructureFile()->getId() : null),
                'isActive' => $this->timeline->isActive(),
                'isShowName' => $this->timeline->isShowName()
            ];

            $repository = $this->entityManager->getRepository('Gedmo\Translatable\Entity\Translation');
            $defaults += $repository->findTranslations($this->timeline);

            $defaultLocale = $this->localeRepository->getDefault();
            if ($defaultLocale) {
                $defaults[$defaultLocale->getLanguageCode()]['name'] = $this->timeline->getName();
                $defaults[$defaultLocale->getLanguageCode()]['text'] = $this->timeline->getText();
            }
        } else {
            $defaults = [
                'isActive' => true,
            ];
        }

        $this['form']->setDefaults($defaults);
    }

    protected function createComponentForm()
    {
        $form = $this->baseFormFactory->create();

        foreach ($this->localeRepository->getActive() AS $activeLocale) {
            $container = $form->addContainer($activeLocale->getLanguageCode());

            $container->addText('name')
                ->setRequired('Please enter article name.')
                ->addRule(Form::MAX_LENGTH, 'Article name is too long.', 255);

            $container->addTextArea('text');
        }

        $form->addText('structureFile');

        $form->addCheckbox('isActive');

        $form->addSubmit('send');

        $form->onValidate[] = [$this, 'editFormValidate'];
        $form->onSuccess[] = [$this, 'editFormSucceeded'];

        return $form;
    }

    public function editFormValidate(Form $form)
    {
        $values = $form->getValues();

        foreach ($this->localeRepository->getActive() AS $activeLocale) {
            if (!$this->timelineRepository->isNameFree($values->{$activeLocale->getLanguageCode()}->name, $activeLocale, $this->group, $this->timeline)) {
                $form->addError('Tento název je již zabrán.');
            }
        }

        if (!$this->presenter->isAllowed('timeline', 'edit')) {
            $form->addError('Nemáte oprávění editovat article.');
        }
    }

    public function editFormSucceeded(Form $form)
    {
        $values = $form->getValues();

        if ($values->structureFile) {
            $structureFile = $this->structureFileRepository->getOneById($values->structureFile);
        } else {
            $structureFile = null;
        }

        if ($this->timeline) {
            $timeline = $this->timeline;
            $timeline->setStructureFile($structureFile);
            $timeline->setIsActive($values->isActive);
            $timeline->setIsShowName($values->isShowName);
        } else {

            $defaultLocale = $this->localeRepository->getDefault();

            $timeline = new Timeline($this->group, $values->{$defaultLocale->getLanguageCode()}->name, $values->{$defaultLocale->getLanguageCode()}->text,  $values->isActive,
                $structureFile);
        }

        $repository = $this->entityManager->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        foreach ($this->localeRepository->getActive() AS $activeLocale) {
            $repository->translate($timeline, 'name', $activeLocale->getLanguageCode(), $values->{$activeLocale->getLanguageCode()}->name)
                ->translate($timeline, 'text', $activeLocale->getLanguageCode(), $values->{$activeLocale->getLanguageCode()}->text);
        }

        $this->entityManager->persist($timeline);

        $this->entityManager->flush();

        $this->onSuccess();
    }

    public function render()
    {
        $template = $this->template;
        $template->activeLocales = $this->localeRepository->getActive();
        $template->setFile(__DIR__ . '/TimelineForm.latte');
        $template->render();
    }
}