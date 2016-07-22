<?php
namespace WGFinanzen\Page;

require_once(__DIR__.'/PageInterface.php');
require_once(__DIR__.'/../Data.php');

use WGFinanzen\Data;

class AddPurchase implements PageInterface{

    /** @var  Data */
    protected $data;

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getTitle()
    {
        return 'Neuer Kauf';
    }

    public function getViewScript()
    {
        return __DIR__.'/../../view/add-purchase.phtml';
    }

    public function getViewVariables()
    {
        return [];
    }
}