<?php

declare(strict_types=1);

namespace DiffUnitTests\Core;

use DiffUnitTests\DiffTestCase;
use Mistralys\Diff\Diff;
use Mistralys\Diff\DiffException;

final class ParseTests extends DiffTestCase
{
    public function test_unmodified() : void
    {
        $string1 = "Hello world";
        $string2 = "Hello world";

        $array = Diff::compareStrings($string1, $string2)->toArray();

        $this->assertEquals($array[0][1], Diff::UNMODIFIED);
    }

    public function test_insert() : void
    {
        $string1 = "Hello word";
        $string2 = "Hello world";

        $array = Diff::compareStrings($string1, $string2)->toArray();

        $this->assertEquals($array[1][1], Diff::INSERTED);
    }

    public function test_delete() : void
    {
        $string1 = "Hello world";
        $string2 = "Hello word";

        $array = Diff::compareStrings($string1, $string2)->toArray();

        $this->assertEquals($array[0][1], Diff::DELETED);
        $this->assertEquals($array[1][1], Diff::INSERTED);
    }

    public function test_dispose() : void
    {
        $diff = Diff::compareStrings('Hello', 'Hello');

        $diff->dispose();

        $this->expectException(DiffException::class);

        $diff->toArray();
    }

    public function test_compareFiles() : void
    {
        $file1 = $this->filesFolder . '/string1.txt';
        $file2 = $this->filesFolder . '/string2.txt';

        $array = Diff::compareFiles($file1, $file2)->toArray();

        $this->assertEquals($array[1][1], Diff::INSERTED);
    }

    public function test_splitUnicodeString() : void
    {
        $split = Diff::splitCharacters('öäéç€');

        $this->assertCount(5, $split);
        $this->assertSame('ö', $split[0]);
        $this->assertSame('ä', $split[1]);
        $this->assertSame('é', $split[2]);
        $this->assertSame('ç', $split[3]);
        $this->assertSame('€', $split[4]);
    }

    /**
     * @link https://github.com/Mistralys/text-diff/issues/1
     */
    public function test_unicodeCharacters() : void
    {
        $diff = Diff::compareStrings(
            "Géhu – jun02 (LM).",
            "Géhu et al. 1984 (31) – jun02 (LM).",
            true
        );

        $this->assertStringContainsString('<span>é</span>', $diff->toHTML());
    }
}
