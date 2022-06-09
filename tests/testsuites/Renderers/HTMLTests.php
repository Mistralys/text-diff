<?php

declare(strict_types=1);

namespace DiffUnitTests\Renderers;

use DiffUnitTests\DiffTestCase;
use Mistralys\Diff\Diff;
use Mistrals\Diff\Renderer\HTML;

final class HTMLTests extends DiffTestCase
{
    public function test_parse() : void
    {
        $string1 = "Hello world";
        $string2 = "Hello word";

        $renderer = new HTML(Diff::compareStrings($string1, $string2));

        $sep = $renderer->getSeparator();
        $container = $renderer->getContainer();

        $expected = sprintf(
            $container,
            '<del>' . $string1 . '</del>' . $sep .
            '<ins>' . $string2 . '</ins>' . $sep
        );

        $this->assertEquals($expected, $renderer->render());
    }
}
