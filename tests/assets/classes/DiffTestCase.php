<?php

declare(strict_types=1);

namespace DiffUnitTests;

use PHPUnit\Framework\TestCase;

abstract class DiffTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $filesFolder = '';

    protected function setUp() : void
    {
        $this->filesFolder = __DIR__ . '/../files';
    }
}
