<?php
define("ROOT_VENDOR", dirname(__DIR__));

// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once ROOT_VENDOR."/vendor/autoload.php";
//all classes for import and use on the relevant pages.
require_once "src/User.php";
require_once "src/Group.php";
require_once "src/Event.php";
require_once "src/Transaction.php";

function getEntityManager(): EntityManager {
    // Create a simple "default" Doctrine ORM configuration for Annotations
    $isDevMode = true;
    $proxyDir = null;
    $cache = null;
    $useSimpleAnnotationReader = false;
    $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
    // or if you prefer yaml or XML
    //$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
    //$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

    // database configuration parameters
        $conn = array(
            'dbname' => 'doctrine',
            'user' => 'dev',
            'password' => 'dev',
            'host' => 'host.docker.internal',
            'driver' => 'pdo_mysql',
        );

    // obtaining the entity manager
    return EntityManager::create($conn, $config);
}


