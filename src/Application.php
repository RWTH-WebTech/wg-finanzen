<?php
namespace WGFinanzen;

require_once(__DIR__.'/Page/PageInterface.php');
require_once(__DIR__.'/Renderer.php');
require_once(__DIR__.'/NavigationItem.php');

use WGFinanzen\Page\PageInterface;

class Application{

    const PAGE_PARAMETER = 'page';

    /** @var Renderer  */
    protected $renderer;

    protected $pages;

    protected $navigation;

    public function __construct($pages = [], $navigation = []){
        $this->renderer = new Renderer();
        $this->pages = $pages;
        $this->navigation = $navigation;
    }

    public function addPage($id, PageInterface $page){
        $this->pages[$id] = $page;
    }

    public function addNavigationItem(NavigationItem $item){
        $this->navigation[] = $item;
    }

    public function run(){
        $pageId = $_GET[self::PAGE_PARAMETER];
        /* @var PageInterface $page */
        $page = array_shift(array_values($this->pages));
        if(isset($this->pages[$pageId])){
            $page = $this->pages[$pageId];
        }
        $variables = array(
            'pageTitle' => $page->getTitle(),
            'pageContent' => $this->renderer->render($page),
            'activePageId' => $pageId,
            'navigation' => $this->navigation
        );
        $this->renderer->showViewScript(__DIR__.'/../view/', $variables);
    }
}