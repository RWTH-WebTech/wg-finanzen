<?php
namespace WGFinanzen\Page;

require_once(__DIR__.'/PageInterface.php');
require_once(__DIR__.'/../Data.php');

use WGFinanzen\Data;

class ShowPurchases implements PageInterface {

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
        return 'Käufe';
    }

    public function getViewScript()
    {
        return __DIR__.'/../../view/show-purchases.phtml';
    }

    public function getViewVariables()
    {
        return [
            'purchases' => $this->data->getPurchases(),
        ];
    }
}