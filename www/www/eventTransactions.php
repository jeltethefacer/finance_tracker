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
        $eventId = $_GET["eventId"];
        $event = $entityManager->getRepository("Event")->find($eventId);

        if(!$event) {
            echo json_encode(new ArrayObject());
            die();
        }

        if($event->getGroup()->getUser()->getId() !== $user->getId()){
            echo  json_encode(array("error" => true, "errorCode" => "NOT_AUTHORIZED"));
            die();
        }
        $requestedTransactions = $entityManager->getRepository("Transaction")->findBy(["event" => $eventId]);
        echo json_encode($requestedTransactions);
        break;
    default:
        json_encode(new ArrayObject());
}