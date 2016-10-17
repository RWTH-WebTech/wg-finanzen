<?php
namespace WGFinanzen\Page;

use WGFinanzen\Application;
use WGFinanzen\Data\Purchase;
use WGFinanzen\Data;

class ReadShoppingList implements PageInterface{

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
        return "Read Shopping List";
    }

    public function getViewScript()
    {
        return __DIR__.'/../../view/readShoppingList.phtml';
    }

    public function getViewVariables()
    {
        Application::$DISABLE_LAYOUT = true;
        $headings = ['title', 'description', 'cost', 'date', 'boughtBy', 'boughtFor'];
        $fp = fopen("php://input", 'r');
        $success = true;
        $errors = [];
        $i = 1;
        while(($line = fgetcsv($fp)) !== false){
            $data = array_combine($headings, $line);
            $res = $this->addPurchase($data);
            if(!$res){
                $success = false;
                $errors[] = $i;
            }
            $i++;
        }
        fclose($fp);
        return ['success' => $success, 'errors' => $errors ];
    }
    
    protected function addPurchase($data){
        if(empty($data['title'])){
            return false;
        }
        $date = DateTime::createFromFormat('Y-m-d\TH:i', $data['date']);
        if(DateTime::getLastErrors()['error_count'] != 0){
            return false;
        }
        $boughtBy = $this->getData()->getFlatMate((int) $data['boughtBy']);
        if($boughtBy === null){
            return false;
        }
        $boughtFor = [];
        foreach($data['boughtFor'] as $flatMateId){
            $flatMate = $this->getData()->getFlatMate($flatMateId);
            if($flatMate === null){
                return false;
            }
            $boughtFor[] = $flatMate;
        }

        $purchase = new Purchase();
        $purchase->setTitle(Data::escapeXSS($data['title']));
        $purchase->setDescription(Data::escapeXSS($data['description']));
        $purchase->setCost((float) $data['cost']);
        $purchase->setDate($date);
        $purchase->setBoughtBy($boughtBy);
        $purchase->setBoughtFor($boughtFor);
        $this->getData()->addPurchase($purchase);
        return true;
    }
}