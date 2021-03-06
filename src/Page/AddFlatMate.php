<?php
namespace WGFinanzen\Page;

require_once(__DIR__ .'/PageInterface.php');
require_once(__DIR__ .'/../Data.php');
require_once(__DIR__ .'/../Data/FlatMate.php');

use WGFinanzen\Data;
use WGFinanzen\Data\FlatMate;

class AddFlatMate implements PageInterface{

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
            $flatMate = new FlatMate();
            $flatMate->setName($name);
            $this->getData()->addFlatMate($flatMate);
            return true;
        }
        return false;
    }
}