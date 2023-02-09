<?php
/**
 * File containing the {@see Diff} class.
 * 
 * @package Diff
 * @see Diff
 */

declare(strict_types=1);

namespace Mistralys\Diff;

use AppUtils\FileHelper;
use AppUtils\FileHelper_Exception;
use Mistrals\Diff\Renderer\HTMLTable;
use Mistrals\Diff\Renderer\PlainText;
use Mistrals\Diff\Renderer\HTML;
use Mistralys\Diff\Styler\Styler;

/**
 * Class used to analyze and render differences between two
 * strings. Directly derived from Kate Morley's Diff implementation.
 *
 * @package Diff
 * @author Kate Morley
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 * @link http://iamkate.com/
 * @link http://code.iamkate.com/php/diff-implementation/
 * @license CC0-1.0 http://creativecommons.org/publicdomain/zero/1.0/legalcode
 */
class Diff
{
    public const ERROR_DIFF_ALREADY_DISPOSED = 66901;
    public const ERROR_CANNOT_SPLIT_STRING = 66902;
    
    public const UNMODIFIED = 0;
    public const DELETED    = 1;
    public const INSERTED   = 2;
    
    private bool $compareCharacters = false;
    private string $string1;
    private string $string2;
    private bool $disposed = false;

   /**
    * @var string|string[]
    */
    private $sequence1 = '';
    
   /**
    * @var string|string[]
    */
    private $sequence2 = '';
    
    public function __construct(string $string1, string $string2)
    {
        $this->string1 = $string1;
        $this->string2 = $string2;
    }
    
   /**
    * Sets whether to compare single characters. Default is to 
    * compare only lines.
    * 
    * @param bool $compare
    * @return Diff
    */
    public function setCompareCharacters(bool $compare=true) : Diff
    {
        $this->compareCharacters = $compare;
        
        return $this;
    }
    
   /**
    * Returns the diff for two strings. The return value is an array, each of
    * whose values are an array containing two values: a line (or character, if
    * $compareCharacters is true), and one of the constants DIFF::UNMODIFIED (the
    * line or character is in both strings), DIFF::DELETED (the line or character
    * is only in the first string), and DIFF::INSERTED (the line or character is
    * only in the second string).
    * 
    * @param string $string1
    * @param string $string2
    * @param bool $compareCharacters Whether to compare single characters (compares lines otherwise)
    * @return Diff
    */
    public static function compareStrings(string $string1, string $string2, bool $compareCharacters = false) : Diff
    {
        $diff = new Diff($string1, $string2);
        $diff->setCompareCharacters($compareCharacters);
        return $diff;
    }
    
   /**
    * Like {@see Diff::compare()}, but returns the diff for two files.
    *
    * @param string $file1
    * @param string $file2
    * @param bool $compareCharacters Whether to compare single characters (compares lines otherwise)
    * @return Diff
    *
    * @throws FileHelper_Exception If one of the files cannot be found or opened.
    */
    public static function compareFiles(string $file1, string $file2, bool $compareCharacters = false) : Diff
    {
        return self::compareStrings(
            FileHelper::readContents($file1),
            FileHelper::readContents($file2),
            $compareCharacters
        );
    }
    
   /**
    * Creates an instance of the styler class, which
    * is used to access the CSS used for the syntax
    * highlighting in the HTML renderers.
    * 
    * @return Styler
    */
    public static function createStyler() : Styler
    {
        return new Styler();
    }

    /**
     * Retrieves the raw array that contains the diff definitions
     * for the two strings.
     *
     * For example, comparing the following strings:
     *
     * Hello word
     * Hello world
     *
     * Will return the following array:
     *
     * <pre>
     * Array(
     *   [0] => Array
     *   (
     *     [0] => Hello word
     *     [1] => 1
     *   )
     *   [1] => Array
     *   (
     *     [0] => Hello world
     *     [1] => 2
     *   )
     * )
     * </pre>
     *
     * Where the second entry in the sub-array is the status
     * code, e.g. Diff::DELETED, Diff::INSERTED.
     *
     * @return array<int,array<int,int|string>>
     * @throws DiffException
     */
    public function toArray() : array
    {
        if($this->disposed)
        {
            throw new DiffException(
                'The diff has been disposed.',
                'The toArray method cannot be called again after disposing.',
                self::ERROR_DIFF_ALREADY_DISPOSED
            );
        }
        
        // initialise the sequences and comparison start and end positions
        $start = 0;

        if ($this->compareCharacters)
        {
            $this->sequence1 = self::splitCharacters($this->string1);
            $this->sequence2 = self::splitCharacters($this->string2);
        }
        else
        {
            $this->sequence1 = self::splitLines($this->string1);
            $this->sequence2 = self::splitLines($this->string2);
        }

        $end1 = count($this->sequence1) - 1;
        $end2 = count($this->sequence2) - 1;
        $totalSequence = count($this->sequence1);
        
        // skip any common prefix
        while ($start <= $end1 && $start <= $end2 && $this->sequence1[$start] === $this->sequence2[$start])
        {
            $start ++;
        }
        
        // skip any common suffix
        while ($end1 >= $start && $end2 >= $start && $this->sequence1[$end1] === $this->sequence2[$end2])
        {
            $end1 --;
            $end2 --;
        }
        
        // generate the partial diff
        $partialDiff = $this->generatePartialDiff($start, $end1, $end2);
        
        // generate the full diff
        $diff = array();
        
        for ($index = 0; $index < $start; $index ++)
        {
            $diff[] = array($this->sequence1[$index], self::UNMODIFIED);
        }
        
        while (count($partialDiff) > 0)
        {
            $diff[] = array_pop($partialDiff);
        }
        
        for ($index = $end1 + 1; $index < $totalSequence; $index ++)
        {
            $diff[] = array($this->sequence1[$index], self::UNMODIFIED);
        }
        
        // clear the sequences to free up memory, we don't need them anymore
        $this->sequence1 = '';
        $this->sequence2 = '';
        
        return $diff;
    }
    
   /**
    * Splits the string into individual lines.
    * 
    * @param string $string
    * @throws DiffException
    * @return string[]
    */
    public static function splitLines(string $string) : array
    {
        $split = preg_split('/\R/', $string);
        
        if(is_array($split))
        {
            return $split;
        }
        
        throw new DiffException(
            'Could not split the target string.',
            'Could the string be badly formatted?',
            self::ERROR_CANNOT_SPLIT_STRING
        );
    }

    /**
     * Splits the string into individual characters.
     *
     * @param string $string
     * @throws DiffException
     * @return string[]
     */
    public static function splitCharacters(string $string) : array
    {
        $split = mb_str_split($string);

        if(is_array($split))
        {
            return $split;
        }

        throw new DiffException(
            'Could not split the target string.',
            'Could the string be badly formatted?',
            self::ERROR_CANNOT_SPLIT_STRING
        );
    }
    
   /**
    * Returns the table of the longest common subsequence lengths
    * for the specified sequences.
    * 
    * @param int $start
    * @param int $end1
    * @param int $end2
    * @return array<int,array<int,int>>
    */
    private function computeTable(int $start, int $end1, int $end2) : array
    {
        // determine the lengths to be compared
        $length1 = $end1 - $start + 1;
        $length2 = $end2 - $start + 1;
        
        // initialise the table
        $table = array(array_fill(0, $length2 + 1, 0));
        
        // loop over the rows
        for ($index1 = 1; $index1 <= $length1; $index1 ++){
            
            // create the new row
            $table[$index1] = array(0);
            
            // loop over the columns
            for ($index2 = 1; $index2 <= $length2; $index2 ++){
                
                // store the longest common subsequence length
                if ($this->sequence1[$index1 + $start - 1]
                    === $this->sequence2[$index2 + $start - 1]){
                        $table[$index1][$index2] = $table[$index1 - 1][$index2 - 1] + 1;
                }else{
                    $table[$index1][$index2] =
                    max($table[$index1 - 1][$index2], $table[$index1][$index2 - 1]);
                }
                
            }
        }
        
        return $table;
    }
    
   /**
    * Returns the partial diff for the specified sequences, in reverse order.
    * 
    * @param int $start
    * @param int $end1
    * @param int $end2
    * @return array<int,array<int,int|string>>
    */
    private function generatePartialDiff(int $start, int $end1, int $end2) : array
    {
        // compute the table of the longest common subsequence lengths
        $table = $this->computeTable($start, $end1, $end2);
        
        //  initialise the diff
        $diff = array();
        
        // initialise the indices
        $index1 = count($table) - 1;
        $index2 = count($table[0]) - 1;
        
        // loop until there are no items remaining in either sequence
        while ($index1 > 0 || $index2 > 0)
        {
            // check what has happened to the items at these indices
            if (
                $index1 > 0 && $index2 > 0
                && $this->sequence1[$index1 + $start - 1]
                === $this->sequence2[$index2 + $start - 1]
            ){
                // update the diff and the indices
                $diff[] = array($this->sequence1[$index1 + $start - 1], self::UNMODIFIED);
                $index1 --;
                $index2 --;
            }
            elseif (
                $index2 > 0
                && $table[$index1][$index2] === $table[$index1][$index2 - 1]
            ) {
                // update the diff and the indices
                $diff[] = array($this->sequence2[$index2 + $start - 1], self::INSERTED);
                $index2 --;
            }
            else
            {
                // update the diff and the indices
                $diff[] = array($this->sequence1[$index1 + $start - 1], self::DELETED);
                $index1 --;
            }
        }
        
        // return the diff
        return $diff;
    }
    
   /**
    * Returns a diff as a string, where unmodified lines are prefixed by '  ',
    * deletions are prefixed by '- ', and insertions are prefixed by '+ '.
    * 
    * @param string $separator
    * @return string
    */
    public function toString(string $separator = "\n") : string
    {
        $renderer = new PlainText($this);
        $renderer->setSeparator($separator);
        
        return $renderer->render();
    }
    
   /**
    * Returns a diff as an HTML string, where unmodified lines are contained
    * within 'span' elements, deletions are contained within 'del' elements, and
    * insertions are contained within 'ins' elements.
    * 
    * @param string $separator
    * @return string
    */
    public function toHTML(string $separator = '<br>') : string
    {
        $renderer = new HTML($this);
        $renderer->setSeparator($separator);
        
        return $renderer->render();
    }
    
   /**
    * Returns a diff as an HTML table.
    * 
    * @param string $indentation
    * @param string $separator
    * @return string
    */
    public function toHTMLTable(string $indentation = '', string $separator = '<br>') : string
    {
        $renderer = new HTMLTable($this);
        $renderer->setIndentation($indentation);
        $renderer->setSeparator($separator);
        
        return $renderer->render();
    }
    
   /**
    * Disposes of the diff by clearing the stored strings,
    * to free memory until the class is destructed.
    * 
    * @return Diff
    */
    public function dispose() : Diff
    {
        $this->string1 = '';
        $this->string2 = '';
        
        $this->disposed = true;
        
        return $this;
    }
}

