<?php
namespace WGFinanzen;

use WGFinanzen\Entity\Purchase;
use WGFinanzen\Entity\FlatMate;
use Exception;


class Data {
    const DATA_FILE = __DIR__.'/../data/finanz_data.dat';

    /** @var  Purchase[] */
    protected $purchases;
    /** @var  FlatMate[] */
    protected $flatMates;

    function __construct(){
        $data = unserialize(file_get_contents(self::DATA_FILE));
        $this->purchases = $data['purchases'];
        $this->flatMates = $data['flatMates'];
    }

    function __destruct(){
        $data = ['purchases' => $this->purchases, 'flatMates' => $this->flatMates];
        file_put_contents(self::DATA_FILE, serialize($data));
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
}