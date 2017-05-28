<?php
/**
 * @author: Robert Mihai Colca
 * @since : 5/27/17 12:34 AM
 */
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use \Models\Packages;
use \Models\Scrapers;

require_once "../phpapp/vendor/autoload.php";
require_once "config.php";
require_once 'autoload.php';

try {
    $rsm = new ResultSetMapping(); // Fancy native query runner :3

    $scriptName = "script_file";
    $inputFileExists = true;

    if (!isset($argv[1]) || !isset($argv[2])) {
        throw new Exception("Invalid arguments");
    }

    $previousCompanyId = 0;

    $scrapersRepo = $em->getRepository("Models\Scrapers");
    $scrapers = $scrapersRepo->findAll();
    /** @var Scrapers[] $scrapers */

    $outputFilename = $argv[2] . ".json";
    foreach ($scrapers as $scraper) {
        $scriptName = $scraper->getScriptName();
        $scriptName = "../scrapers/{$scriptName}";
        echo "Running scraper {$scraper->getName()} / {$scriptName} \"{$scraper->getUrl()}\" $outputFilename" . PHP_EOL;

        /** @var Models\CompanyService $companyService */
        $companyService = $scraper->getCompanyService();

        if (!file_exists($scriptName)) {
            throw new Exception("script_file not found");
        }

        if (!file_exists($outputFilename)) {
            echo "[WARNING] No input file found, created one ({$outputFilename})" . PHP_EOL;
            $inputFileExists = false;
            file_put_contents($outputFilename, '');
        }

        if (system("\"{$scriptName}\" \"{$scraper->getUrl()}\" {$outputFilename}", $out) === FALSE) {
            echo $out;
            throw new Exception("Execution failed ");
        }

        if (!file_exists($outputFilename)) {
            throw new Exception("No output");
        }

        $outputFileContents = @file_get_contents($outputFilename);
        $newPackages = json_decode($outputFileContents, true)['packages'];

        $sql = 'DELETE FROM Models\Packages p WHERE p.companyService = ?1';
        $query = $em->createQuery($sql);
        $query->setParameter(1, $companyService);

        $res = $query->execute();
        $em->persist($companyService);

        foreach ($newPackages as $packageJson) {
            $em->flush();

            $name = $packageJson['name'];
            $price = $packageJson['price'][0];
            $hint = isset($packageJson['scraper_id_hint']) ? $packageJson['scraper_id_hint'] : null;
            $newPackage = new Packages($name, $price, $companyService, $hint);
            foreach ($packageJson['characteristics'] as $alias => $val) {
                $value = is_array($val) ? $val[0] : $val;
                $units = isset($val[1]) ? $val[1] : '';
                echo "characteristic " . $alias . " " . $value . PHP_EOL;
                $newPackage->setCharacteristic($alias, $value, $units);
            }
            $em->persist($newPackage);
            $em->flush();
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();

    if ($error == "Invalid arguments") {
        echo '[INFO] Please run: run.php [input_packages_json] [output_packages_json]' . PHP_EOL;
    } else if ($error == "Execution failed") {
        echo "[FATAL ERROR] The execution of the scraper has failed!" . PHP_EOL;
    } else if ($error == "No output") {
        echo "[FATAL ERROR] No output file has been generated!" . PHP_EOL;
    } else if ($error == "script_file not found") {
        echo "[FATAL ERROR] The file {$scriptName} was not found !" . PHP_EOL;
    } else if ($error == "Connection error") {
        echo "[FATAL ERROR] A connection with the MySQL Server cannot be realised !" . PHP_EOL;
    } else if ($error == "Invalid json") {
        echo "[FATAL ERROR] An invalid json has been provided !" . PHP_EOL;
    } else if ($error == "Insert error") {
        echo "[FATAL ERROR] Inserting packages has failed" . PHP_EOL;
    } else if ($error == "Scraper result error") {
        echo "[FATAL ERROR] No Scrapers can be fetched !" . PHP_EOL;
    } else {
        echo "[FATAL ERROR] An unknown error has been encountered ! Detail: {$error}" . PHP_EOL;
    }
}