<?php
namespace WGFinanzen\Page;

use WGFinanzen\Data;
use WGFinanzen\Data\FlatMate;

class Balances implements ProtectedPageInterface{

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
        return 'Bilanzen';
    }

    public function getViewScript()
    {
        return __DIR__.'/../../view/balances.phtml';
    }

    public function getViewVariables()
    {
        return [
            'balances' => $this->getBalances(),
            'flatMates' => $this->getData()->getAllFlatMates()
        ];
    }

    protected function getBalances(){
        $purchases = $this->getData()->getAllPurchases();
        $flatMates = $this->getData()->getAllFlatMates();
        $balances = [];
        foreach($flatMates as $flatMate){
            $sum = 0;
            foreach($purchases as $purchase){
                if($purchase->getBoughtBy() == $flatMate && !empty($purchase->getBoughtFor())){
                    $sum += $purchase->getCost();
                }
                if(in_array($flatMate, $purchase->getBoughtFor())){
                    $sum -= $purchase->getCost() / count($purchase->getBoughtFor());
                }
            }
            $balances[$flatMate->getId()] = $sum;
        }
        return $balances;
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