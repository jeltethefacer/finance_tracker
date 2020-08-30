<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";
require_once ROOT."/util/verification.php";


session_start();
//the returns object is always a json object.
header('Content-Type: application/json');
$entityManager = getEntityManager();

//verify user
$jsonBody = file_get_contents('php://input');
$jsonRequest = json_decode($jsonBody, $assoc = true);
$user = decodeJWTUser($jsonRequest["token"],  $entityManager);


if(!$user){
    echo  json_encode(array("error" => true, "errorCode" => "NOT_LOGGED_IN"));
    die();
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $groupId = (int) $_GET['groupId'];
    $group = $entityManager->find("Group", $groupId);
    if($group && $group->getUser()->getId() === $user->getId()) {

        echo json_encode($group);
    } else {
        //send empty json object.
        echo json_encode(new ArrayObject());
    }
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $money = (int) ((double) $jsonRequest["money"] * 100);

        $group = new Group();

        $group->setName($jsonRequest["name"]);
        $group->setMoney($money);
        $group->setUser($user);

        $entityManager->persist($group);
        $entityManager->flush();

        echo json_encode($group);
}
