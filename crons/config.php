<?php
/**
 * @author: Robert Mihai Colca
 * @since : 5/27/17 3:52 PM
 */

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\DBAL\Logging\EchoSQLLogger;

// replace with file to your own project bootstrap
require_once "../phpapp/vendor/autoload.php";
$isDevMode = true;
$conn = array(
    'dbname' => 'hacktm',
    'user' => 'hacktm',
    'password' => 'hacktm',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
);

$paths            = array(__DIR__ . '/Models');

$config = Setup::createConfiguration($isDevMode);
$config->setSQLLogger(new EchoSQLLogger());
$driver = new AnnotationDriver(new AnnotationReader(), $paths);

// registering noop annotation autoloader - allow all annotations by default
AnnotationRegistry::registerLoader('class_exists');
$config->setMetadataDriverImpl($driver);

$em = EntityManager::create($conn, $config);
