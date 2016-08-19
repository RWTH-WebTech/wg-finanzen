<?php
namespace WGFinanzen;

use WGFinanzen\Page\PageInterface;


class Renderer {

    public function render(PageInterface $page){
        ob_start();
        $this->showViewScript(
            $page->getViewScript(),
            $page->getViewVariables()
        );
        $content = ob_get_clean();
        return $content;
    }

    public function showViewScript($viewScript, $variables){
        require($viewScript);
    }

}