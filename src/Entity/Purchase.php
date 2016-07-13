<?php
namespace WGFinanzen\Entity;

class Purchase{
    /** @var int */
    protected $id;
    /** @var string */
    protected $title;
    /** @var  string */
    protected $description;
    /** @var  float */
    protected $cost;
    /** @var  DateTime */
    protected $date;
    /** @var  FlatMate */
    protected $boughtBy;
    /** @var  FlatMate[] */
    protected $boughtFor;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return FlatMate
     */
    public function getBoughtBy()
    {
        return $this->boughtBy;
    }

    /**
     * @param FlatMate $boughtBy
     */
    public function setBoughtBy($boughtBy)
    {
        $this->boughtBy = $boughtBy;
    }

    /**
     * @return FlatMate[]
     */
    public function getBoughtFor()
    {
        return $this->boughtFor;
    }

    /**
     * @param FlatMate[] $boughtFor
     */
    public function setBoughtFor($boughtFor)
    {
        $this->boughtFor = $boughtFor;
    }


}