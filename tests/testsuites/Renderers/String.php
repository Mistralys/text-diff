<?php

use Mistralys\Diff\Diff;
use PHPUnit\Framework\TestCase;
use Mistrals\Diff\Renderer\PlainText;

final class Diff_Renderers_StringTest extends TestCase
{
    public function test_parse()
    {
        $string1 = "Hello world";
        $string2 = "Hello word";
        
        $renderer = new PlainText(Diff::compareStrings($string1, $string2));
        $sep = $renderer->getSeparator();
        
        $expected =
        '- '.$string1.
        $sep.
        '+ '.$string2.
        $sep;

        $this->assertEquals($expected, $renderer->render());
    }
}
