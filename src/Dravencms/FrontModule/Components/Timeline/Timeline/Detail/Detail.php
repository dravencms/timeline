<?php

namespace Dravencms\FrontModule\Components\Timeline\Timeline\Detail;

use Dravencms\Components\BaseControl;
use Dravencms\Model\Timeline\Repository\TimelineRepository;
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

        $template->test = 'TTTT';
        $template->setFile(__DIR__ . '/detail.latte');
        $template->render();
    }
}
