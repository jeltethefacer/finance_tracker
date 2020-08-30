<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";
require_once ROOT."/util/verification.php";

header('Content-Type: application/json');
$entityManager = getEntityManager();

$jsonBody = file_get_contents('php://input');
$jsonRequest = json_decode($jsonBody, $assoc = true);




switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $userId = $_GET["userId"];
        $user = null;
        if($_GET["userId"] == 0){
            error_log("in zero field");
            if(isset($jsonRequest["token"])) {
                $user = decodeJWTUser($jsonRequest["token"],  $entityManager);
            }

        } else {
            error_log("else");
            $user = getEntityManager()->getRepository("User")->find( $userId);
        }

        if(!$user) {
            echo json_encode(new ArrayObject());
        }else {
            echo json_encode($user);
        }
        break;
    case "POST":
        $user = new User();
        $password = password_hash($jsonRequest["password"], PASSWORD_BCRYPT);


        $user->setName($jsonRequest["name"]);
        $user->setPassword($password);
        $entityManager->persist($user);
        $entityManager->flush();
        echo json_encode($user);
        break;
    case "DELETE":
        $user = null;
        if(isset($jsonRequest["token"])) {
            $user = decodeJWTUser($jsonRequest["token"],  $entityManager);
        }
        if(!$user) {
            echo json_encode(new ArrayObject());
        } else {
            $entityManager->remove($user);
            $entityManager->flush();
            echo json_encode(["status" => "SUCCES"]);
        }
    default:
}


