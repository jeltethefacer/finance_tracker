<?php
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="transactions")
 */
class Transaction implements JsonSerializable
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var int
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="transactions")
     */
    protected $event;

    /**
     * @ORM\Column(type="integer")
     */
    protected $money = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param int $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param Event $event
     */
    public function setEvent($event)
    {
        $event->getTransactions()->add($this);
        $this->event = $event;
    }

    public function addMoney(int $addMoney)
    {
        $newMoney = $this->money + $addMoney;


        if($newMoney >= 0) {
            // in case that both the new money and the already expense are
            if($this->money >= 0) {
                $this->event->addIncome($addMoney);
            } else if ($this->money < 0) {
                //removes the expense in case the new transaction turns positive and add the
                $this->event->addIncome($newMoney);
                $this->event->removeExpense($this->money);
            }
        } else if ($newMoney <= 0 ) {
            if($this->money <= 0) {
                $this->event->addExpense($addMoney);
            } else if ($this->money > 0) {
                $this->event->removeIncome($this->money);
                $this->event->addExpense($newMoney);
            }
        }

        $this->money = $newMoney;
    }

    public function getFormattedMoney() {
        $euro =  $this->money /100.0;

        return sprintf("€%.2f", $euro);
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "money" => $this->money,
            "event" => $this->event->getId()
        );
    }
}