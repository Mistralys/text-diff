<?php
/**
 * Main bootstrapper used to set up the testsuites environment.
 * 
 * @package Diff
 * @subpackage Tests
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 */

    /**
     * The tests root folder (this file's location)
     * @var string
     */
    define('TESTS_ROOT', __DIR__ );

    $autoloader = realpath(TESTS_ROOT.'/../vendor/autoload.php');
    
    if($autoloader === false) 
    {
        die('ERROR: The autoloader is not present. Run composer install first.');
    }

   /**
    * The composer autoloader
    */
    require_once $autoloader;
    
   /**
    * The abstract test case class
    */
    require_once TESTS_ROOT.'/assets/classes/TestCase.php';