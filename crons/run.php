<?php
/**
 * @author: Robert Mihai Colca
 * @since : 5/27/17 12:34 AM
 */
$mysqlData = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'mydb'
];

try {
    $DB = new MySQLi($mysqlData['host'], $mysqlData['username'], $mysqlData['password'], $mysqlData['password']);
    $DB->set_charset('UTF-8');

    $scriptName = "script_file";
    $inputFileExists = true;

    if (!isset($argv[1]) || !isset($argv[2]) || !isset($argv[3])) {
        throw new Exception("Invalid arguments");
    }

    if (!file_exists($scriptName)) {
        throw new Exception("script_file not found");
    }

    if (!system("{$scriptName} {$argv[1]} {$argv[3]}", $out)) {
        throw new Exception("Execution failed");
    }

    if (!file_exists($argv[2] . ".json")) {
        echo "[WARNING] No input file found, created one ({$argv[2]}.json)" . PHP_EOL;
        $inputFileExists = false;
        file_put_contents($argv[2], '');
    }

    if (file_exists($argv[3] . ".json")) {
        throw new Exception("No output");
    }

    if ($inputFileExists === false)  {
        file_put_contents($argv[2] . ".json", file_get_contents($argv[3] . ".json"));
    } else {

    }

} catch (Exception $e) {
    $error = $e->getMessage();
    if ($error == "Invalid arguments") {
        echo '[INFO] Please run: run.php [scrape_url] [input_packages_json] [output_packages_json]' . PHP_EOL;
    } else if ($error == "Execution failed") {
        echo "[FATAL ERROR] The execution of the scraper has failed!" . PHP_EOL;
    } else if ($error == "No output") {
        echo "[FATAL ERROR] No output file has been generated!" . PHP_EOL;
    } else if ($error = "script_file not found") {
        echo "[FATAL ERROR] The file {$scriptName} was not found !" . PHP_EOL;
    } else {
        echo "[FATAL ERROR] An unknown error has been encountered !" . PHP_EOL;
    }
}