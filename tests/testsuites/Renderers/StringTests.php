<?php

declare(strict_types=1);

namespace DiffUnitTests\Renderers;

use DiffUnitTests\DiffTestCase;
use Mistralys\Diff\Diff;
use Mistrals\Diff\Renderer\PlainText;

final class StringTests extends DiffTestCase
{
    public function test_parse() : void
    {
        $string1 = "Hello world";
        $string2 = "Hello word";

        $renderer = new PlainText(Diff::compareStrings($string1, $string2));
        $sep = $renderer->getSeparator();

        $expected =
            '- ' . $string1 .
            $sep .
            '+ ' . $string2 .
            $sep;

        $this->assertEquals($expected, $renderer->render());
    }
}
