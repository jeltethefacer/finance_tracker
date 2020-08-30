<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";
$entityManager = getEntityManager();
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
   echo "posting";
    $user = new User();
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);


    $user->setName($_POST["name"]);
    $user->setPassword($password);
    $entityManager->persist($user);
    $entityManager->flush();
}
?>

<html>
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