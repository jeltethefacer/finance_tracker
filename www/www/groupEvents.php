<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";
require_once ROOT."/util/verification.php";

$entityManager = getEntityManager();

header('Content-Type: application/json');

$jsonBody = file_get_contents('php://input');
$jsonRequest = json_decode($jsonBody, $assoc = true);

$user = null;
if(isset($jsonRequest["token"])) {
    $user = decodeJWTUser($jsonRequest["token"],  $entityManager);
}

if(!$user){
    echo  json_encode(array("error" => true, "errorCode" => "NOT_LOGGED_IN"));
    die();
}

//TODO: secure the request for unauthorized access
switch ($_SERVER["REQUEST_METHOD"]){
    case "GET":
        $groupId = $_GET["groupId"];

        $group = $entityManager->getRepository("Group")->find($groupId);
        if(!$group) {
            echo json_encode(new ArrayObject());
            die();
        }

        if($group->getUser()->getId() !== $user->getId()){
            echo  json_encode(array("error" => true, "errorCode" => "NOT_AUTHORIZED"));
            die();
        }
        $requestedEvents = $entityManager->getRepository("Event")->findBy(["group" => $groupId]);
        echo json_encode($requestedEvents);
        break;
    default:
        json_encode(new ArrayObject());
}