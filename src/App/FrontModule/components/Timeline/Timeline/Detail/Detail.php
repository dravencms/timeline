<?php

namespace App\FrontModule\Components\Timeline\Timeline;

use App\Components\BaseControl;
use App\Model\Article\Repository\ArticleRepository;
use Salamek\Cms\ICmsActionOption;

class Detail extends BaseControl
{
    /** @var ICmsActionOption */
    private $cmsActionOption;

    public function __construct(ICmsActionOption $cmsActionOption)
    {
        parent::__construct();
        $this->cmsActionOption = $cmsActionOption;
    }
    
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/detail.latte');
        $template->render();
    }
}
