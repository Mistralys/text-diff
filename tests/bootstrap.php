<?php
/**
 * Main bootstrapper used to set up the testsuite environment.
 * 
 * @package Diff
 * @subpackage Tests
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 */

declare(strict_types=1);

namespace Mistralys\Diff;

const TESTS_ROOT = __DIR__;

$autoloader = TESTS_ROOT.'/../vendor/autoload.php';

if(!file_exists($autoloader))
{
    die('ERROR: The autoloader is not present. Run composer install first.');
}

/**
* The composer autoloader
*/
require_once $autoloader;
