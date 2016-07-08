<?php
namespace WGFinanzen\Page;

require_once(__DIR__ .'/PageInterface.php');

class Example implements PageInterface {
    public function getTitle() {
        return 'Hallo Welt';
    }

    public function getViewScript() {
        return __DIR__.'/../../view/example.phtml';
    }

    public function getViewVariables() {
        return ['name' => 'Alice'];
    }
}