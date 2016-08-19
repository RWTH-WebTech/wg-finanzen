<?php
namespace WGFinanzen\Page;
require_once(__DIR__ .'/ProtectedPageInterface.php');
require_once(__DIR__ .'/../Session.php');

use WGFinanzen\Data\FlatMate;
use WGFinanzen\Session;

class Logout implements ProtectedPageInterface{
    protected $sessionManager;

    function __construct(Session $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    public function getTitle()
    {
        return 'Logout';
    }

    public function getViewScript()
    {
        return __DIR__.'/../../view/logout.phtml';
    }

    public function getViewVariables()
    {
        $this->sessionManager->logout();
        return [];
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