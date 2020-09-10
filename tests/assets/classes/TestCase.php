<?php

use PHPUnit\Framework\TestCase;

abstract class DiffTestCase extends TestCase
{
   /**
    * @var string
    */
    protected $filesFolder = ''; 
    
    protected function setUp() : void
    {
        $this->filesFolder = realpath(__DIR__.'/../files');
    }
}
