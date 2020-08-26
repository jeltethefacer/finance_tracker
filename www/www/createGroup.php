<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";

session_start();
if(!isset($_SESSION["user"])){
    header("location: /");
    die();
}

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $user = $entityManager->find("User", $_SESSION["user"]);
    $money = (int) ((double) $_POST["money"] * 100);

    $group = new Group();
    $group->setName($_POST["name"]);
    $group->setMoney($money);
    $group->setUser($user);
    $entityManager->persist($group);
    $entityManager->flush();
}
?>

<html lang="en">
<head>
    <title>create group</title>
</head>
<body>
    <h1>Create Group</h1>

    <form action='<?php echo $_SERVER["PHP_SELF"]?>' method="post">
        <label>
            name:
            <input type="text" name="name">
        </label><br/>
        <label>
            money (euros):
            <input type="number" name="money" step="0.01">
        </label><br/>
        <input type="submit">
    </form>
</body>
</html>
