<?php
namespace WGFinanzen;

require_once(__DIR__.'/Data/FlatMate.php');
require_once(__DIR__.'/Data/Purchase.php');

use WGFinanzen\Data\Purchase;
use WGFinanzen\Data\FlatMate;
use DateTime;
use PDO;

class Data {
    const DATA_DIR = __DIR__.'/../data';
    const DATABASE_FILE = self::DATA_DIR.'/wgfinanzen.sqlite';
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var  Purchase[] */
    protected $purchases;
    /** @var  FlatMate[] */
    protected $flatMates;
    /** @var  PDO */
    protected $db;

    function __construct(){
        $this->db = new PDO(self::DATABASE_FILE);
        $this->readFlatMates();
        $this->readPurchases();
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
        $flatMates = $this->db->query('SELECT * FROM flatmates');
        while($flatMateData = $flatMates->fetch()){
            $flatMate = new FlatMate();
            $flatMate->setId((int) $flatMateData['id']);
            $flatMate->setName($flatMateData['name']);
            $this->flatMates[] = $flatMate;
        }
        $flatMates->closeCursor();
    }

    protected function readPurchases(){
        $this->purchases = [];
        $purchases = $this->db->query('SELECT * FROM purchase');
        while($purchaseData = $purchases->fetch()){
            $purchase = new Purchase();
            $purchase->setId((int) $purchaseData['id']);
            $purchase->setTitle($purchaseData['title']);
            $purchase->setDescription($purchaseData['description']);
            $purchase->setCost((float) $purchaseData['cost']);
            $purchase->setDate(DateTime::createFromFormat(self::DATE_FORMAT, $purchaseData['date']));
            $purchase->setBoughtBy($this->getFlatMate($purchaseData['purchased_by']));
            $this->purchases[] = $purchase;
        }
        $stmt = $this->db->prepare('SELECT * FROM purchased_for WHERE purchase_id = :purchase_id');
        $stmt->bindParam(':purchase_id', $purchaseId);
        foreach($this->purchases as $purchase){
            $purchaseId = $purchase->getId();
            $stmt->execute();
            $boughtFor = [];
            while($purchasedForData = $stmt->fetch()) {
                $boughtFor[] = $this->getFlatMate($purchaseData['flatmate_id']);
            }
            $purchase->setBoughtFor($boughtFor);
        }
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
        $stmt = $this->db->prepare('INSERT INTO flatmates (name) VALUES (:name)');
        $stmt->bindParam(':name', $name);
        $name = $flatMate->getName();
        $result = $stmt->execute();
        if($result === false){
            return false;
        }
        $flatMate->setId($this->db->lastInsertId());
        $this->flatMates[] = $flatMate;
    }

    public function removeFlatMate($id){
        $stmt = $this->db->prepare('DELETE FROM flatmates WHERE id = :id');
        $stmt->bindParam(':id', $flatMateId);
        $flatMateId = $id;
        $result = $stmt->execute();
        if($result === false) {
            return false;
        }
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
        $stmt = $this->db->prepare('INSERT INTO purchases (title, description, date, cost, purchased_by) VALUES (:title, :description, :date, :cost, :purchased_by)');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':cost', $cost);
        $stmt->bindParam(':purchased_by', $purchased_by);

        $title = $purchase->getTitle();
        $description = $purchase->getDescription();
        $date = $purchase->getDate()->format(self::DATE_FORMAT);
        $cost = $purchase->getCost();
        $purchased_by = $purchase->getBoughtBy()->getId();
        $result = $stmt->execute();
        if($result === false){
            return false;
        }
        $purchase->setId($this->db->lastInsertId());

        $stmt = $this->db->prepare('INSERT INTO purchased_for (purchase_id, flatmate_id) VALUES (:purchase_id, :flatmate_id)');
        $stmt->bindParam(':purchase_id', $purchaseId);
        $stmt->bindParam(':flatmate_id', $flatmateId);
        $purchaseId = $purchase->getId();
        foreach($purchase->getBoughtFor() as $flatMate){
            $flatmateId = $flatMate->getId();
            $result = $stmt->execute();
            if(!$result){
                return false;
            }
        }

        $this->purchases[] = $purchase;
    }

    public function removePurchase($id){
        $stmt = $this->db->prepare('DELETE FROM purchases WHERE id = :id');
        $stmt->bindParam(':id', $purchaseId);
        $purchaseId = $id;
        $result = $stmt->execute();
        if($result === false) {
            return false;
        }
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

    public static function escapeXSS($str){
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}