<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    echo "posting";
    $user = $entityManager->getRepository("User")->findOneBy(array("name" => $_POST["name"]));
    if(!$user){
        echo "no user found";
    } else if(password_verify($_POST["password"], $user->getPassword())) {
        $_SESSION["user"] = $user->getId();
        die();
    }
}
?>

<html lang="en">
<head>
    <title>login</title>
</head>
<h1>Login</h1>
<form action='<?php echo $_SERVER["PHP_SELF"]?>' method="post">
    <label>
        name:
        <input type="text" name="name">
    </label><br/>
    <label>
        password:
        <input type="password" name="password">
    </label><br/>
    <input type="submit">
</form>
</html>