<?php

use Mistralys\Diff\Diff;
use Mistrals\Diff\Renderer\HTML;

final class Diff_Renderers_HTMLTest extends DiffTestCase
{
    public function test_parse()
    {
        $string1 = "Hello world";
        $string2 = "Hello word";
        
        $renderer = new HTML(Diff::compareStrings($string1, $string2));
        
        $sep = $renderer->getSeparator();
        $container = $renderer->getContainer();
        
        $expected = sprintf(
            $container,
            '<del>'.$string1.'</del>'.$sep.
            '<ins>'.$string2.'</ins>'.$sep
        );
        
        $this->assertEquals($expected, $renderer->render());
    }
}
