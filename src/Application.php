<?php
namespace WGFinanzen;

use WGFinanzen\Page\PageInterface;
use WGFinanzen\Page\ProtectedPageInterface;

class Application{
    public static $DISABLE_LAYOUT = false;

    const PAGE_PARAMETER = 'page';
    const NO_LAYOUT_VARIABLE = '__NO_LAYOUT__';
    const NOSTRAP = false;

    /** @var Renderer  */
    protected $renderer;
    /** @var PageInterface[] */
    protected $pages;
    /** @var NavigationItem[] */
    protected $navigation;
    /** @var Data */
    protected $data;
    /** @var Session */
    protected $sessionManager;

    public function __construct($pages = [], $navigation = []){
        $this->renderer = new Renderer();
        $this->data = new Data();
        $this->pages = $pages;
        $this->navigation = $navigation;
        $this->sessionManager = new Session($this->data);
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

    /**
     * @return Session
     */
    public function getSessionManager(){
        return $this->sessionManager;
    }

    public function run(){
        $pageId = $this->getStandardPageId();
        if(
            isset($_GET[self::PAGE_PARAMETER]) &&
            $this->accessAllowed($_GET[self::PAGE_PARAMETER])
        ){
            $pageId = $_GET[self::PAGE_PARAMETER];
        }
        $variables = $this->getPageVariables($pageId);
        if(self::$DISABLE_LAYOUT){
            echo $variables['pageContent'];
            return;
        }
        $this->renderer->showViewScript(self::NOSTRAP ? __DIR__.'/../view/nostrap.phtml' : __DIR__.'/../view/layout.phtml', $variables);
    }

    protected function accessAllowed($pageId){
        if($pageId === null){
            return false;
        }
        if(!isset($this->pages[$pageId])){
            return false;
        }
        if(!($this->pages[$pageId] instanceof ProtectedPageInterface)){
            return true;
        }
        return $this->pages[$pageId]->accessAllowed($this->getSessionManager()->getCurrentUser());
    }

    protected function getStandardPageId(){
        foreach(array_keys($this->pages) as $id){
            if($this->accessAllowed($id)){
                return $id;
                break;
            }
        }
        return null;
    }


    protected function getPageVariables($pageId){
        if(!$this->accessAllowed($pageId)){
            return [ 'pageTitle' => '404 - Page not found', 'pageContent' => '<h1>404 - Page not found</h1>',
                'activePageId' => null, 'navigation' => [] ];
        }
        $page = $this->pages[$pageId];
        $content = $this->renderer->render($page);
        $title = $page->getTitle();
        $navigation = [];
        foreach($this->navigation as $navItem){
            if($this->accessAllowed($navItem->getPageId())){
                $navigation[] = $navItem;
            }
        }
        return [ 'pageTitle' => $title, 'pageContent' => $content,
            'activePageId' => $pageId, 'navigation' => $navigation ];
    }
}