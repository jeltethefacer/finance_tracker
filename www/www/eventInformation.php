<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";

session_start();
$eventId = (int) $_GET['eventId'];

if(!$eventId) {
    echo "no event id given";
    die();
}

$event =  $entityManager->find("Event", $eventId);
//checks if the group exists and the session id is equal to the user id if the group
if(!isset($event) && !$event && $_SESSION["user"] != $event->getGroup()->getUser()->getId()) {
    echo "group does not exist";
    die();
}

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $money = (int) ((double) $_POST["money"] * 100);

    $transaction = new Transaction();
    $transaction->setName($_POST["name"]);
    $transaction->setDescription($_POST["description"]);
    $transaction->setEvent($event);
    $transaction->addMoney($money);

    $entityManager->persist($transaction);
    $entityManager->flush();
}

?>
<html lang="en">
<head>
    <title><?php echo $event->getName()?> information</title>
</head>

<body>

<?php
     $name = $event->getName();
    echo "<h1>$name</h1>";
    $budget = $event->getFormattedBudget();
    $money = ($event->getExpense() + $event->getIncome() ) / 100;
    $money = sprintf("€%.2f", $money);
    echo "<h2>$budget : $money</h2>";
?>

<?php
foreach ($event->getTransactions() as $transaction) {
    $name = $transaction->getName();
    $money = $transaction->getFormattedMoney();
    echo "transaction: $name, $money <br/>";
}
?>


<h1>Create Transaction</h1>
<form action='<?php echo "$eventId"?>' method="post">
    <label>
        name:
        <input type="text" name="name">
    </label><br/>
    <label>
        description:
        <textarea name="description"> </textarea>
    </label><br/>
    <label>
        money (euros):
        <input type="number" name="money" step="0.01">
    </label><br/>
    <input type="submit">
</form>
</body>
</html>