<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $groupId = (int) $_GET['groupId'];
    $group = $entityManager->find("Group", $groupId);

    header('Content-Type: application/json');
    echo json_encode($group);

} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $jsonBody = file_get_contents('php://input');
        $jsonRequest = json_decode($jsonBody, $assoc = true);

        $user = $entityManager->find("User", $jsonRequest["userId"]);
        $money = (int) ((double) $jsonRequest["money"] * 100);

        $group = new Group();
        $group->setName($jsonRequest["name"]);
        $group->setMoney($money);
        $group->setUser($user);

        $entityManager->persist($group);
        $entityManager->flush();

        header('Content-Type: application/json');
        echo json_encode($group);
}
