<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";

$productRepository = $entityManager->getRepository('User');
$users = $productRepository->findAll();

foreach ($users as $user) {
    echo sprintf("name: -%s<br/>", $user->getName());
}