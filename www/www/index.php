<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";
session_start();
$message = "no user logged in.";
$groups = [];
if(isset($_SESSION["user"])){
    $user = $entityManager->find("User", $_SESSION["user"]);
    $name = $user->getName();
    $groups =  $user->getGroups()->toArray();
    $message = "hallo $name!";
}
?>

<html>
    <head>
        
    </head>
    <body>
        <?php echo $message ?>
        <?php foreach ($groups as $group) {
            $groupName = $group->getName();
            $groupMoney = $group->getFormattedMoney();
            echo "$groupName: $groupMoney <br/>";
        }
        ?>
    </body>

</html>