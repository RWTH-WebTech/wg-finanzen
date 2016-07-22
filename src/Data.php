<?php
namespace WGFinanzen;

require_once(__DIR__.'/Data/FlatMate.php');
require_once(__DIR__.'/Data/Purchase.php');

use WGFinanzen\Data\Purchase;
use WGFinanzen\Data\FlatMate;
use Exception;
use DateTime;

class Data {
    const DATA_DIR = __DIR__.'/../data';
    const FLAT_MATE_DATA = self::DATA_DIR.'/flatmate.dat';
    const PURCHASE_DATA = self::DATA_DIR.'/purchase.dat';
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var  Purchase[] */
    protected $purchases;
    /** @var  FlatMate[] */
    protected $flatMates;

    function __construct(){
        $this->readFlatMates();
        $this->readPurchases();
    }

    function __destruct(){
        $this->saveFlatMates();
        $this->savePurchases();
    }

    protected function saveFlatMates(){
        $fp = fopen(self::FLAT_MATE_DATA, 'w');
        foreach($this->flatMates as $flatMate){
            fputcsv($fp, [
                $flatMate->getId(),
                $flatMate->getName()]
            );
        }
        fclose($fp);
    }

    protected function savePurchases(){
        $fp = fopen(self::PURCHASE_DATA, 'w');
        foreach($this->purchases as $purchase){
            $boughtForIds = [];
            foreach($purchase->getBoughtFor() as $flatMate){
                $boughtForIds[] = $flatMate->getId();
            }
            fputcsv($fp, [
                $purchase->getId(),
                $purchase->getTitle(),
                $purchase->getDescription(),
                $purchase->getCost(),
                $purchase->getDate()->format(self::DATE_FORMAT),
                $purchase->getBoughtBy()->getId(),
                json_encode($boughtForIds)
            ]);
        }
        fclose($fp);
    }

    protected function readFlatMates(){
        $flatMateColumns = ['id', 'name'];
        $this->flatMates = [];

        $fp = fopen(self::FLAT_MATE_DATA, 'r');
        while(($data = fgetcsv($fp)) !== false){
            $flatMateData = array_combine($flatMateColumns, $data);
            $flatMate = new FlatMate();
            $flatMate->setId((int) $flatMateData['id']);
            $flatMate->setName($flatMateData['name']);
            $this->flatMates[] = $flatMate;
        }
        fclose($fp);
    }

    protected function readPurchases(){
        $purchaseColumns = ['id', 'title', 'description', 'cost', 'date', 'boughtBy', 'boughtFor'];
        $this->purchases = [];

        $fp = fopen(self::PURCHASE_DATA, 'r');
        while(($data = fgetcsv($fp)) !== false){
            $purchaseData = array_combine($purchaseColumns, $data);
            $purchase = new Purchase();
            $purchase->setId((int) $purchaseData['id']);
            $purchase->setTitle($purchaseData['title']);
            $purchase->setDescription($purchaseData['description']);
            $purchase->setCost((float) $purchaseData['cost']);
            $purchase->setDate(DateTime::createFromFormat(self::DATE_FORMAT, $purchaseData['date']));
            $purchase->setBoughtBy($this->getFlatMate($purchaseData['boughtBy']));
            $boughtForIds = json_decode($purchaseData['boughtFor']);
            $boughtFor = [];
            foreach($boughtForIds as $id){
                $boughtFor[] = $this->getFlatMate($id);
            }
            $purchase->setBoughtFor($boughtFor);
            $this->purchases[] = $purchase;
        }
        fclose($fp);
    }

    protected function getNextId($array){
        $maxId = 0;
        foreach($array as $entity){
            if($entity->getId() > $maxId) {
                $maxId = $entity->getId();
            }
        }
        return $maxId + 1;
    }

    public function addFlatMate(FlatMate $flatMate){
        $flatMate->setId($this->getNextId($this->flatMates));
        $this->flatMates[] = $flatMate;
    }

    public function removeFlatMate($id){
        foreach($this->flatMates as $key => $mate){
            if($mate->getId() == $id){
                unset($this->flatMates[$key]);
            }
        }
        $this->flatMates = array_values($this->flatMates);
    }

    public function getFlatMate($id){
        foreach($this->flatMates as $key => $mate){
            if($mate->getId() == $id){
                return $mate;
            }
        }
        return null;
    }

    public function getAllFlatMates(){
        return $this->flatMates;
    }

    public function addPurchase(Purchase $purchase){
        $purchase->setId($this->getNextId($this->purchases));
        $this->purchases[] = $purchase;
    }

    public function removePurchase($id){
        foreach($this->purchases as $key => $purchase){
            if($purchase->getId() == $id){
                unset($this->purchases[$key]);
            }
        }
        $this->purchases = array_values($this->purchases);
    }

    public function getPurchase($id){
        foreach($this->purchases as $key => $purchase){
            if($purchase->getId() == $id){
                return $purchase;
            }
        }
        return null;
    }

    public function getAllPurchases(){
        return $this->purchases;
    }
}