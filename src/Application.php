<?php
namespace WGFinanzen;

require_once(__DIR__.'/Page/PageInterface.php');
require_once(__DIR__.'/Renderer.php');
require_once(__DIR__.'/Data.php');
require_once(__DIR__.'/NavigationItem.php');

use WGFinanzen\Page\PageInterface;

class Application{

    const PAGE_PARAMETER = 'page';
    const NOSTRAP = false;

    /** @var Renderer  */
    protected $renderer;

    protected $pages;

    protected $navigation;

    protected $data;

    public function __construct($pages = [], $navigation = []){
        $this->renderer = new Renderer();
        $this->data = new Data();
        $this->pages = $pages;
        $this->navigation = $navigation;
    }

    public function addPage($id, PageInterface $page){
        $this->pages[$id] = $page;
    }

    public function addNavigationItem(NavigationItem $item){
        $this->navigation[] = $item;
    }

    public function getData(){
        return $this->data;
    }

    public function run(){
        $pageId = array_keys($this->pages)[0];
        if(isset($_GET[self::PAGE_PARAMETER]) && isset($this->pages[$pageId])){
            $pageId = $_GET[self::PAGE_PARAMETER];
        }
        /* @var PageInterface $page */
        $page = $this->pages[$pageId];
        $variables = array(
            'pageTitle' => $page->getTitle(),
            'pageContent' => $this->renderer->render($page),
            'activePageId' => $pageId,
            'navigation' => $this->navigation
        );
        $this->renderer->showViewScript(self::NOSTRAP ? __DIR__.'/../view/nostrap.phtml' : __DIR__.'/../view/layout.phtml', $variables);
    }
}