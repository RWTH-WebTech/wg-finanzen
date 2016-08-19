<?php
namespace WGFinanzen\Page;

require_once(__DIR__ .'/ProtectedPageInterface.php');
require_once(__DIR__.'/../Data/FlatMate.php');

use WGFinanzen\Data\FlatMate;

class Example implements ProtectedPageInterface {
    public function getTitle() {
        return 'Hallo Welt';
    }

    public function getViewScript() {
        return __DIR__.'/../../view/example.phtml';
    }

    public function getViewVariables() {
        return ['name' => 'Alice'];
    }

    /**
     * Returns true if the passed FlatMate is allowed to access the page. If null is passed, no FlatMate is logged in.
     * @param FlatMate $flatMate
     * @return boolean
     */
    public function accessAllowed(FlatMate $flatMate = null)
    {
        return $flatMate !== null;
    }
}