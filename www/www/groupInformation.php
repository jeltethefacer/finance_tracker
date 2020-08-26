<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";

session_start();
$groupId = (int) $_GET['groupId'];

if(!$groupId) {
    echo "no group id given";
    die();
}

$group = $entityManager->find("Group", $groupId);
//checks if the group exists and the session id is equal to the user id if the group
if(!isset($group) && !$group && $_SESSION["user"] != $group->getUser()->getId()) {
    echo "group does not exist";
    die();
}

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $money = (int) ((double) $_POST["money"] * 100);

    $event = new Event();
    $event->setName($_POST["name"]);
    $event->setBudget($money);
    $event->setGroup($group);

    $entityManager->persist($event);
    $entityManager->flush();
}

?>
<html lang="en">
<head>
    <title><?php echo $group->getName()?> information</title>
</head>

<body>
<?php
    foreach ($group->getEvents() as $event) {
        $name = $event->getName();
        echo "Event: $name <br/>";
    }
?>
<h1>Create Event</h1>
<form action='<?php echo "$groupId"?>' method="post">
    <label>
        name:
        <input type="text" name="name">
    </label><br/>
    <label>
        budget (euros):
        <input type="number" name="money" step="0.01">
    </label><br/>
    <input type="submit">
</form>
</body>
</html>