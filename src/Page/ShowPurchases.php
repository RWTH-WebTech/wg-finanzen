<?php
namespace WGFinanzen\Page;

require_once(__DIR__ .'/ProtectedPageInterface.php');
require_once(__DIR__.'/../Data.php');
require_once(__DIR__.'/../Data/FlatMate.php');

use WGFinanzen\Data;
use WGFinanzen\Data\FlatMate;

class ShowPurchases implements ProtectedPageInterface {

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