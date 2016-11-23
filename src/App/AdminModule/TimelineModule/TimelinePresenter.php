<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\AdminModule\TimelineModule;

use AdminModule\Components\Article\ArticleFormFactory;
use AdminModule\Components\Article\ArticleGridFactory;
use App\AdminModule\SecuredPresenter;
use App\Model\Article\Entities\Article;
use App\Model\Article\Entities\Group;
use App\Model\Article\Repository\ArticleRepository;
use App\Model\Article\Repository\GroupRepository;
use App\Model\Tag\Repository\TagRepository;

/**
 * Description of ArticlePresenter
 *
 * @author Adam Schubert
 */
class ArticlePresenter extends SecuredPresenter
{

    /** @var ArticleRepository @inject */
    public $articleRepository;

    /** @var GroupRepository @inject */
    public $groupRepository;

    /** @var TagRepository @inject */
    public $tagRepository;

    /** @var ArticleGridFactory @inject */
    public $articleGridFactory;

    /** @var ArticleFormFactory @inject */
    public $articleFormFactory;

    /** @var Group */
    private $group;

    /** @var Article|null */
    private $article = null;

    /**
     * @param integer $groupId
     * @isAllowed(article,edit)
     */
    public function actionDefault($groupId)
    {
        $this->group = $this->groupRepository->getOneById($groupId);
        $this->template->group = $this->group;
        $this->template->h1 = 'Articles in group '.$this->group->getName();
    }

    /**
     * @isAllowed(article,edit)
     * @param $groupId
     * @param $id
     * @throws \Nette\Application\BadRequestException
     */
    public function actionEdit($groupId, $id = null)
    {
        $this->group = $this->groupRepository->getOneById($groupId);
        if ($id) {
            $article = $this->articleRepository->getOneById($id);

            if (!$article) {
                $this->error();
            }

            $this->article = $article;

            $this->template->h1 = sprintf('Edit article „%s“', $article->getName());
        } else {
            $this->template->h1 = 'New article in group '.$this->group->getName();
        }
    }

    /**
     * @return \AdminModule\Components\Article\ArticleForm
     */
    protected function createComponentFormArticle()
    {
        $control = $this->articleFormFactory->create($this->group, $this->article);
        $control->onSuccess[] = function(){
            $this->flashMessage('Article has been successfully saved', 'alert-success');
            $this->redirect('Article:', ['groupId' => $this->group->getId()]);
        };
        return $control;
    }

    /**
     * @return \AdminModule\Components\Article\ArticleGrid
     */
    public function createComponentGridArticle()
    {
        $control = $this->articleGridFactory->create($this->group);
        $control->onDelete[] = function()
        {
            $this->flashMessage('Article has been successfully deleted', 'alert-success');
            $this->redirect('Article:', ['groupId' => $this->group->getId()]);
        };
        return $control;
    }
}
