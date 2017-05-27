<?php
/**
 * @author: Robert Mihai Colca
 * @since : 5/27/17 12:34 AM
 */
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use \Models\Packages;
use \Models\Scrapers;

require_once "../phpapp/vendor/autoload.php";
require_once "config.php";
require_once 'autoload.php';

try {
    $scriptName = "script_file";
    $inputFileExists = true;

    if (!isset($argv[1]) || !isset($argv[2])) {
        throw new Exception("Invalid arguments");
    }

    // If no input file exists
    if (@file_get_contents($argv[1] . ".json") === false) {
        $outputFile = @file_get_contents($argv[2] . ".json");
        file_put_contents($argv[1] . ".json", $outputFile);

        $inputData = json_decode($outputFile, true);

        if (empty($inputData) || !count($inputData) || !is_array($inputData)) {
            throw new Exception("Invalid json");
        }

        // $DB->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        foreach ($inputData['packages'] as $value) {
            if (!isset($value['name']) || !isset($value['price'])) {
                echo "[SKIPPED] No name or price defined for this package" . PHP_EOL;
                continue;
            }

            $scrapersRepo = $em->getRepository("Models\Scrapers");
            $scrapers = $scrapersRepo->findAll();  /** @var Scrapers[] $scrapers */

            foreach ($scrapers as $scraper) {
                $scriptName = $scraper->getScriptName();

                /** @var Models\CompanyService[] $companyServices */
                $companyService = $scraper->getCompanyService();

                $newPackage = new Packages($value['name'], $value['price'], $companyService,null);

                $em->persist($newPackage);
                $em->flush();

                if (!file_exists($scriptName)) {
                    throw new Exception("script_file not found");
                }

                if (!file_exists($argv[2] . ".json")) {
                    echo "[WARNING] No input file found, created one ({$argv[2]}.json)" . PHP_EOL;
                    $inputFileExists = false;
                    file_put_contents($argv[2], '');
                }

                if (!file_exists($argv[3] . ".json")) {
                    throw new Exception("No output");
                }

                if (!system("{$scriptName} {$data['url']} {$argv[3]}", $out)) {
                    throw new Exception("Execution failed");
                }
            }
        }
    } else {

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