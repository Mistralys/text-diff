<?php

declare(strict_types=1);

namespace DiffUnitTests\Renderers;

use DiffUnitTests\DiffTestCase;
use Mistrals\Diff\Renderer\HTMLTable;
use Mistralys\Diff\Diff;

final class HTMLTableTests extends DiffTestCase
{
    public function test_parse() : void
    {
        $string1 = "Hello world";
        $string2 = "Hello word";

        $renderer = new HTMLTable(Diff::compareStrings($string1, $string2));

        $sep = $renderer->getSeparator();
        $indent = $renderer->getIndentation();
        $tab = $renderer->getTab();
        $nl = $renderer->getNewlineCharacter();

        $expected =
            $indent . '<table class="text-diff-container">' . $nl .
            $indent . $tab . '<tr>' . $nl .
            $indent . $tab . $tab . '<td class="text-diff-del"><del>Hello world</del>' . $sep . '</td>' . $nl .
            $indent . $tab . $tab . '<td class="text-diff-ins"><ins>Hello word</ins>' . $sep . '</td>' . $nl .
            $indent . $tab . '</tr>' . $nl .
            $indent . '</table>' . $nl;

        $this->assertEquals($expected, $renderer->render());
    }
}
