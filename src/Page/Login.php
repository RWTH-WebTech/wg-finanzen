<?php
namespace WGFinanzen\Page;
require_once(__DIR__ .'/ProtectedPageInterface.php');
require_once(__DIR__ .'/../Session.php');
// require, use Session
use WGFinanzen\Data\FlatMate;
use WGFinanzen\Session;
class Login implements ProtectedPageInterface{
    protected $sessionManager;

    function __construct(Session $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    public function getTitle()
    {
        return 'Login';
    }

    public function getViewScript()
    {
        return __DIR__.'/../../view/login.phtml';
    }

    public function getViewVariables()
    {
        $isLoggedIn = $this->sessionManager->getCurrentUser() != null;
        if(!$isLoggedIn && !empty($_POST['name'])){
            $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
            $this->sessionManager->login($name, $password);
        }
        $currentUser = $this->sessionManager->getCurrentUser();
        $isLoggedIn = $currentUser != null;
        return ['isLoggedIn' => $isLoggedIn, 'currentUser' => $currentUser];
    }

    /**
     * Returns true if the passed FlatMate is allowed to access the page. If null is passed, no FlatMate is logged in.
     * @param FlatMate $flatMate
     * @return boolean
     */
    public function accessAllowed(FlatMate $flatMate = null)
    {
        return $flatMate === null;
    }
}