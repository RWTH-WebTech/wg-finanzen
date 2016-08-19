<?php
namespace WGFinanzen\Page;

require_once(__DIR__ .'/ProtectedPageInterface.php');
require_once(__DIR__.'/../Data.php');
require_once(__DIR__.'/../Data/Purchase.php');
require_once(__DIR__.'/../Data/FlatMate.php');

use WGFinanzen\Data;
use WGFinanzen\Data\Purchase;
use WGFinanzen\Data\FlatMate;
use DateTime;

class AddPurchase implements ProtectedPageInterface{

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
        return 'Neuer Kauf';
    }

    public function getViewScript()
    {
        return __DIR__.'/../../view/add-purchase.phtml';
    }

    public function getViewVariables()
    {
        $created = $this->addPurchase();
        return [
            'created' => $created,
            'flatMates' => $this->getData()->getAllFlatMates(),
        ];
    }
    // addPurchase
    protected function addPurchase(){
        if(empty($_POST['title'])){
            return false;
        }
        $date = DateTime::createFromFormat('Y-m-d\TH:i', $_POST['date']);
        if(DateTime::getLastErrors()['error_count'] != 0){
            return false;
        }
        $boughtBy = $this->getData()->getFlatMate((int) $_POST['boughtBy']);
        if($boughtBy === null){
            return false;
        }
        $boughtFor = [];
        foreach($_POST['boughtFor'] as $flatMateId){
            $flatMate = $this->getData()->getFlatMate($flatMateId);
            if($flatMate === null){
                return false;
            }
            $boughtFor[] = $flatMate;
        }

        $purchase = new Purchase();
        $purchase->setTitle(Data::escapeXSS($_POST['title']));
        $purchase->setDescription(Data::escapeXSS($_POST['description']));
        $purchase->setCost((float) $_POST['cost']);
        $purchase->setDate($date);
        $purchase->setBoughtBy($boughtBy);
        $purchase->setBoughtFor($boughtFor);
        $this->getData()->addPurchase($purchase);
        return true;
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