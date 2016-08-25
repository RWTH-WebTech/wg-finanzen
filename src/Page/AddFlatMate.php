<?php
namespace WGFinanzen\Page;

use WGFinanzen\Data;
use WGFinanzen\Data\FlatMate;
use Hackzilla\PasswordGenerator\Generator\HumanPasswordGenerator;

class AddFlatMate implements ProtectedPageInterface{

    /** @var  Data */
    protected $data;
    /** @var  HumanPasswordGenerator */
    protected $generator;

    function __construct(Data $data)
    {
        $this->data = $data;
        $this->generator = new HumanPasswordGenerator();
        $this->generator->setWordList('/usr/share/dict/words');
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
            'created' => $created['success'],
            'password' => $created['password']
        ];
    }

    protected function createFlatMate(){
        $result = [ 'success' => false, 'password' => ''];
        if(!empty($_POST['name'])){
            $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
            if(!empty($_POST['password'])){
                $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
                $passwordVerify = htmlspecialchars($_POST['password_verify'], ENT_QUOTES, 'UTF-8');
                if($password != $passwordVerify){ $result['success'] = false; return $result; }
            } else {
                $password = $this->generator->generatePassword();
                $result['password'] = $password;
            }
            $flatMate = new FlatMate(); $flatMate->setName($name);
            $flatMate->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $result['success'] = $this->getData()->addFlatMate($flatMate);
        }
        return $result;
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