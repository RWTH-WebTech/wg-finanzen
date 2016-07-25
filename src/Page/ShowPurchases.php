<?php
namespace WGFinanzen\Page;

require_once(__DIR__.'/PageInterface.php');
require_once(__DIR__.'/../Data.php');

use WGFinanzen\Data;

class ShowPurchases implements PageInterface {

    /** @var  Data */
    protected $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
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
        $removed = $this->deletePurchase();
        return [
            'removed' => $removed,
            'purchases' => $this->data->getAllPurchases()
        ];
    }

    public function deletePurchase(){
        if(!empty($_POST['id'])){
            $purchase = $this->getData()->getPurchase((int) $_POST['id']);
            if($purchase == null){
                return 0;
            }
            $this->getData()->removePurchase((int) $_POST['id']);
            return 1;
        }
        return -1;
    }
}