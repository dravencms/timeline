<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dravencms\AdminModule\TimelineModule;

use Dravencms\Model\Timeline\Repository\GroupRepository;
use Dravencms\AdminModule\Components\Timeline\GroupForm\GroupFormFactory;
use Dravencms\AdminModule\Components\Timeline\GroupGrid\GroupGridFactory;
use Dravencms\AdminModule\SecuredPresenter;
use Dravencms\Model\Timeline\Entities\Group;

/**
 * Description of GroupPresenter
 *
 * @author Adam Schubert
 */
class GroupPresenter extends SecuredPresenter
{
    /** @var GroupRepository @inject */
    public $groupRepository;

    /** @var GroupGridFactory @inject */
    public $groupGridFactory;

    /** @var GroupFormFactory @inject */
    public $groupFormFactory;

    /** @var Group|null */
    private $group = null;

    /**
     * @isAllowed(timeline,edit)
     */
    public function renderDefault()
    {
        $this->template->h1 = 'Timeline groups';
    }

    /**
     * @isAllowed(timeline,edit)
     * @param $id
     * @throws \Nette\Application\BadRequestException
     */
    public function actionEdit($id)
    {
        if ($id) {
            $group = $this->groupRepository->getOneById($id);

            if (!$group) {
                $this->error();
            }

            $this->group = $group;

            $this->template->h1 = sprintf('Edit timeline group „%s“', $group->getName());
        } else {
            $this->template->h1 = 'New timeline group';
        }
    }

    /**
     * @return \AdminModule\Components\Timeline\GroupForm
     */
    protected function createComponentFormGroup()
    {
        $control = $this->groupFormFactory->create($this->group);
        $control->onSuccess[] = function(){
            $this->flashMessage('Timeline group has been successfully saved', 'alert-success');
            $this->redirect('Group:');
        };
        return $control;
    }

    /**
     * @return \AdminModule\Components\Timeline\GroupGrid
     */
    public function createComponentGridGroup()
    {
        $control = $this->groupGridFactory->create();
        $control->onDelete[] = function()
        {
            $this->flashMessage('Timeline group has been successfully deleted', 'alert-success');
            $this->redirect('Group:');
        };
        return $control;
    }
}
