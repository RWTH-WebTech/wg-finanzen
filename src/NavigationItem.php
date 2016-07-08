<?php
namespace WGFinanzen;

require_once(__DIR__.'/Application.php');

class NavigationItem{
    /** @var  string */
    protected $name;

    /** @var  string */
    protected $pageId;

    function __construct($name, $pageId)
    {
        $this->name = $name;
        $this->pageId = $pageId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * @param string $pageId
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }

    public function getLink(){
        return '?'.Application::PAGE_PARAMETER.'='.$this->getPageId();
    }
}