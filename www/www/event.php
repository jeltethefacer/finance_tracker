<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";
$entityManager = getEntityManager();


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $eventId = (int) $_GET['eventId'];
    $event = $entityManager->find("Event", $eventId);

    header('Content-Type: application/json');
    echo json_encode($event);

} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jsonBody = file_get_contents('php://input');
    $jsonRequest = json_decode($jsonBody, $assoc = true);

    $money = (int) ((double) $jsonRequest["budget"] * 100);

    $group = $entityManager->find("Group", $jsonRequest["groupId"]);

    $event = new Event();
    $event->setName($jsonRequest["name"]);
    $event->setBudget($money);
    $event->setGroup($group);

    $entityManager->persist($event);
    $entityManager->flush();

    header('Content-Type: application/json');
    echo json_encode($event);
}
