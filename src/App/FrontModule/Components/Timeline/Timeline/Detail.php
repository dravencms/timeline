<?php

namespace App\FrontModule\Components\Timeline\Timeline;

use App\Components\BaseControl;
use App\Model\Article\Repository\ArticleRepository;
use App\Model\Timeline\Repository\TimelineRepository;
use Salamek\Cms\ICmsActionOption;

class Detail extends BaseControl
{
    /** @var ICmsActionOption */
    private $cmsActionOption;

    /** @var TimelineRepository */
    private $timelineRepository;

    public function __construct(ICmsActionOption $cmsActionOption, TimelineRepository $timelineRepository)
    {
        parent::__construct();
        $this->cmsActionOption = $cmsActionOption;
        $this->timelineRepository = $timelineRepository;
    }
    
    public function render()
    {
        $template = $this->template;
        $template->test = $this->timelineRepository->getTest();
        $template->setFile(__DIR__ . '/detail.latte');
        $template->render();
    }
}
