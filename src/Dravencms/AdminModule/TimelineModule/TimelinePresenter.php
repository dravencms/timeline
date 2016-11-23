<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dravencms\AdminModule\TimelineModule;

use Dravencms\dminModule\Components\Timeline\TimelineForm\TimelineFormFactory;
use Dravencms\AdminModule\Components\Timeline\TimelineGrid\TimelineGridFactory;
use Dravencms\AdminModule\SecuredPresenter;
use Dravencms\Model\Timeline\Repository\GroupRepository;
use Dravencms\Model\Timeline\Repository\TimelineRepository;
use Dravencms\Model\Timeline\Entities\Group;
use Dravencms\Model\Timeline\Entities\Timeline;

/**
 * Description of ArticlePresenter
 *
 * @author Adam Schubert
 */
class TimelinePresenter extends SecuredPresenter
{

    /** @var TimelineRepository @inject */
    public $timelineRepository;

    /** @var GroupRepository @inject */
    public $groupRepository;

    /** @var TimelineGridFactory @inject */
    public $timelineGridFactory;

    /** @var TimelineFormFactory @inject */
    public $timelineFormFactory;

    /** @var Group */
    private $group;

    /** @var Timeline|null */
    private $timeline = null;

    /**
     * @param integer $groupId
     * @isAllowed(timeline,edit)
     */
    public function actionDefault($groupId)
    {
        $this->group = $this->groupRepository->getOneById($groupId);
        $this->template->group = $this->group;
        $this->template->h1 = 'Timelines in group '.$this->group->getName();
    }

    /**
     * @isAllowed(timeline,edit)
     * @param $groupId
     * @param $id
     * @throws \Nette\Application\BadRequestException
     */
    public function actionEdit($groupId, $id = null)
    {
        $this->group = $this->groupRepository->getOneById($groupId);
        if ($id) {
            $timeline = $this->timelineRepository->getOneById($id);

            if (!$timeline) {
                $this->error();
            }

            $this->timeline = $timeline;

            $this->template->h1 = sprintf('Edit timeline „%s“', $timeline->getName());
        } else {
            $this->template->h1 = 'New timeline in group '.$this->group->getName();
        }
    }

    /**
     * @return \App\AdminModule\Components\Timeline\TimelineForm\TimelineForm
     */
    protected function createComponentFormTimeline()
    {
        $control = $this->timelineFormFactory->create($this->group, $this->timeline);
        $control->onSuccess[] = function(){
            $this->flashMessage('Timeline has been successfully saved', 'alert-success');
            $this->redirect('Timeline:', ['groupId' => $this->group->getId()]);
        };
        return $control;
    }

    /**
     * @return \App\AdminModule\Components\Timeline\TimelineGrid\TimelineGrid
     */
    public function createComponentGridTimeline()
    {
        $control = $this->timelineGridFactory->create($this->group);
        $control->onDelete[] = function()
        {
            $this->flashMessage('Timeline has been successfully deleted', 'alert-success');
            $this->redirect('Timeline:', ['groupId' => $this->group->getId()]);
        };
        return $control;
    }
}
