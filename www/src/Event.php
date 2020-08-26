<?php
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="events")
 */
class Event implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $budget;

    /**
     * @ORM\Column(type="integer")
     */
    protected $income = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $expense = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="events")
     */
    protected $group;

    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="event")
     */
    protected $transactions;


    public function __construct() {
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getBudget()
    {
        return $this->budget;
    }

    public function getFormattedBudget() {
        $euro =  $this->budget /100.0;
        return sprintf("€%.2f", $euro);
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return int
     */
    public function getExpense()
    {
        return $this->expense;
    }

    /**
     * @return int
     */
    public function getIncome()
    {
        return $this->income;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $budget
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
    }

    /**
     * @param mixed $group
     */
    public function setGroup(Group $group)
    {
        $group->getEvents()->add($this);
        $this->group = $group;
    }

    public function addIncome(int $incomeToAdd)
    {
        $this->income+=$incomeToAdd;
    }

    public function removeIncome(int $incomeToRemove)
    {
        $this->income-=$incomeToRemove;
    }

    public function addExpense(int $expenseToAdd)
    {
        $this->expense+=$expenseToAdd;
    }

    public function removeExpense(int $expenseToRemove)
    {
        $this->expense-=$expenseToRemove;
    }


    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return [
            "id" => $this->id,
            "budget" => $this->budget,
            "name" => $this->name,
            "income" => $this->income,
            "expense" => $this->expense,
            "group" => $this->group->getId()
        ];
    }
}