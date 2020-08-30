<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";
require_once ROOT."/util/verification.php";

header('Content-Type: application/json');

$entityManager = getEntityManager();

if ($_SERVER["REQUEST_METHOD"] === 'GET') {
    $jsonBody = file_get_contents('php://input');
    $jsonRequest = json_decode($jsonBody, $assoc = true);

    //creates standard errormessage
    //TODO: make class.
    $incorrectCredentialsMessage = array("error" => true, "errorCode" => "INVALID_CREDENTIALS");


    //checks if the parameters are non empty or else don't bother to query db.
    if(empty($jsonRequest["name"]) || empty($jsonRequest["password"])) {
        //returns the above defined error message
        echo json_encode($incorrectCredentialsMessage);
    } else {
        //fetches user
        $user = $entityManager->getRepository("User")->findOneBy(array("name" => $jsonRequest["name"]));
        //first checks if the user exists and if the password is correct.
        if($user && password_verify($jsonRequest["password"], $user->getPassword())) {
            echo json_encode(array("token" => encodeJWTUser($user->getId())));
            die();
        } else {
            echo json_encode($incorrectCredentialsMessage);
        }
    }
}
