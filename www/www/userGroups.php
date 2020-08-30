<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";
require_once ROOT."/util/verification.php";

header('Content-Type: application/json');

$jsonBody = file_get_contents('php://input');
$jsonRequest = json_decode($jsonBody, $assoc = true);

$user = null;
if(isset($jsonRequest["token"])) {
    $user = decodeJWTUser($jsonRequest["token"],  getEntityManager());
}

if(!$user){
    echo  json_encode(array("error" => true, "errorCode" => "NOT_LOGGED_IN"));
    die();
}

//TODO: secure the request for unauthorized access
switch ($_SERVER["REQUEST_METHOD"]){
    case "GET":
        $userId = $_GET["userId"];

        if($_GET["userId"] == 0){
            $requestedGroups = getEntityManager()->getRepository("Group")->findBy(["user" => $user->getId()]);
        } else {
            $requestedGroups = getEntityManager()->getRepository("Group")->findBy(["user" => $userId]);
        }

        if(!$requestedGroups) {
            echo json_encode(new ArrayObject());
        }else {
            echo json_encode((array) $requestedGroups);
        }

        break;
    default:
        json_encode(new ArrayObject());
}