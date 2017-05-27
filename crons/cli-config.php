<?php
require_once 'config.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
return ConsoleRunner::createHelperSet($em);
