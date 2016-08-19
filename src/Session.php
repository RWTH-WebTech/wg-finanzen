<?php
namespace WGFinanzen;

use WGFinanzen\Data\FlatMate;

class Session {

    /** @var  Data */
    protected $data;
    /** @var  FlatMate */
    protected $currentUser = null;

    const USER_ID_KEY = 'userId';

    /**
     * @return Data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return FlatMate
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    public function __construct(Data $data){
        $this->data = $data;
        session_start();
        if(!empty($_SESSION[self::USER_ID_KEY])){
            $this->currentUser = $this->getData()->getFlatMate($_SESSION[self::USER_ID_KEY]);
        }
    }

    public function login($name, $password){
        $flatMate = $this->getData()->getFlatMateByName($name);
        if(!$flatMate){
            return false;
        }
        if(!password_verify($password, $flatMate->getPassword())){
            return false;
        }
        $_SESSION[self::USER_ID_KEY] = $flatMate->getId();
        $this->currentUser = $flatMate;
        return true;
    }

    public function logout(){
        $_SESSION[self::USER_ID_KEY] = null;
        $this->currentUser = null;
        session_destroy();
    }

}