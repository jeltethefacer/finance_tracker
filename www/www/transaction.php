<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";
require_once ROOT."/util/verification.php";

header('Content-Type: application/json');
$entityManager = getEntityManager();


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

switch ($_SERVER{"REQUEST_METHOD"}) {
    case "GET":
        $transactionId = (int) $_GET['transactionId'];
        $transaction = $entityManager->find("Transaction", $transactionId);

        echo json_encode($transaction);
        break;


    case "POST":
        $event = $entityManager->find("Event", $jsonRequest["eventId"]);

        //TODO: solve this later
        if(!$event) {
            echo  json_encode(array("error" => true, "errorCode" => "NOT_AUTHORIZED"));
            die();
        }
        if($event->getGroup()->getUser()->getId() !== $user->getId()){
            echo  json_encode(array("error" => true, "errorCode" => "NOT_AUTHORIZED"));
            die();
        }

        $transaction = new Transaction();

        $transaction->setName($jsonRequest["name"]);
        $transaction->setDescription($jsonRequest["description"]);
        $transaction->setEvent($event);
        $transaction->addMoney($jsonRequest["money"]);

        $entityManager->persist($transaction);
        $entityManager->flush();

        echo json_encode($transaction);
        break;
    case "PUT":
        $transaction = $entityManager->find("Transaction", $jsonRequest["transactionId"]);

        //TODO: solve this later
        if(!$transaction) {
            echo  json_encode(array("error" => true, "errorCode" => "TRANSACTION_NOT_FOUND"));
            die();
        }
        if($transaction->getEvent()->getGroup()->getUser()->getId() !== $user->getId()){
            echo  json_encode(array("error" => true, "errorCode" => "NOT_AUTHORIZED"));
            die();
        }

        if(isset($jsonRequest["name"])){
            $transaction->setName($jsonRequest["name"]);
        }
        if(isset($jsonRequest["description"])){
            $transaction->setDescription($jsonRequest["description"]);
        }
        if(isset($jsonRequest["money"])){
            $change = $jsonRequest["money"] - $transaction->getMoney();
            $transaction->addMoney($change);
        }

        $entityManager->persist($transaction);
        $entityManager->flush();

        echo json_encode($transaction);
        break;
    default:
        echo json_encode(array("error" => true, "errorCode" => "INVALID_METHOD"));
}


