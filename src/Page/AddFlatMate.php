<?php
namespace WGFinanzen\Page;

require_once(__DIR__ .'/ProtectedPageInterface.php');
require_once(__DIR__ .'/../Data.php');
require_once(__DIR__ .'/../Data/FlatMate.php');

use WGFinanzen\Data;
use WGFinanzen\Data\FlatMate;

class AddFlatMate implements ProtectedPageInterface{

    /** @var  Data */
    protected $data;

    function __construct(Data $data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getTitle()
    {
        return 'Neuer Mitbewohner';
    }

    public function getViewScript()
    {
        return __DIR__.'/../../view/add-flatmate.phtml';
    }

    public function getViewVariables()
    {
        $created = $this->createFlatMate();
        return [
            'created' => $created
        ];
    }

    protected function createFlatMate(){
        if(!empty($_POST['name'])){
            $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
            $passwordVerify = htmlspecialchars($_POST['password_verify'], ENT_QUOTES, 'UTF-8');
            if($password != $passwordVerify){
                return false;
            }
            $flatMate = new FlatMate();
            $flatMate->setName($name);
            $flatMate->setPassword(password_hash($password, PASSWORD_DEFAULT));
            return $this->getData()->addFlatMate($flatMate);
        }
        return false;
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