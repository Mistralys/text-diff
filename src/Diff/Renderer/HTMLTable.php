<?php

declare(strict_types=1);

namespace Mistrals\Diff\Renderer;

use Mistralys\Diff\Diff;
use Mistralys\Diff\DiffException;

class HTMLTable extends Renderer
{
    private string $separator = '<br>';
    private string $indentation = '';
    private string $tab = '    ';
    private string $nl = PHP_EOL;
    private string $container = '<table class="text-diff-container">%s</table>';

   /**
    * @var array<int, array<int,int|string>>
    */
    private $array;

   /**
    * Sets the tab character(s) to indent the HTML code with.
    * This is used to indent the table's tags beyond the
    * base indentation.
    * 
    * @param string $tab
    * @return HTMLTable
    * @see HTMLTable::setIndentation()
    */
    public function setTab(string $tab) : HTMLTable
    {
        $this->tab = $tab;
        
        return $this;
    }
    
    public function getTab() : string
    {
        return $this->tab;
    }
    
    public function getNewlineCharacter() : string
    {
        return $this->nl;
    }
    
   /**
    * Sets the character
    * @param string $separator
    * @return HTMLTable
    */
    public function setSeparator(string $separator) : HTMLTable
    {
        $this->separator = $separator;
        
        return $this;
    }
    
   /**
    * Sets the character(s) to indent the whole table with.
    *  
    * @param string $indent
    * @return HTMLTable
    * @see HTMLTable::setTab()
    */
    public function setIndentation(string $indent) : HTMLTable
    {
        $this->indentation = $indent;
        
        return $this;
    }
    
    public function getSeparator() : string
    {
        return $this->separator;
    }
    
    public function getIndentation() : string
    {
        return $this->indentation;
    }

    /**
     * Returns a diff as an HTML table.
     *
     * @return string
     * @throws DiffException
     */
    public function render() : string
    {
        $this->array = $this->diff->toArray();

        $i1 = $this->indentation;
        $i2 = $this->indentation.$this->tab;
        $i3 = $this->indentation.$this->tab.$this->tab;
        $nl = PHP_EOL;
        
        $html = '';
        
        // loop over the lines in the diff
        $index = 0;
        while ($index < count($this->array))
        {
            $leftCell = '';
            $rightCell = '';
            
            // determine the line type
            switch ($this->array[$index][1])
            {
                // display the content on the left and right
                case Diff::UNMODIFIED:
                    $leftCell = $this->getCellContent($index, Diff::UNMODIFIED);
                    $rightCell = $leftCell;
                    break;
                    
                    // display the deleted on the left and inserted content on the right
                case Diff::DELETED:
                    $leftCell = $this->getCellContent($index, Diff::DELETED);
                    $rightCell = $this->getCellContent($index, Diff::INSERTED);
                    break;
                    
                    // display the inserted content on the right
                case Diff::INSERTED:
                    $rightCell = $this->getCellContent($index, Diff::INSERTED);
                    break;
                    
            }
            
            $leftType = $this->resolveLeftType($leftCell, $rightCell);
            $rightType = $this->resolveRightType($leftCell, $rightCell);
            
            // extend the HTML with the new row
            $html .=
            $i2. "<tr>". $nl . 
            $i3.     '<td class="text-diff-'.$leftType. '">'. $leftCell . "</td>".$nl.
            $i3.     '<td class="text-diff-'.$rightType. '">'. $rightCell . "</td>".$nl.
            $i2. "</tr>".$nl;
                                                
        }
        
        return $i1. sprintf($this->container, $nl.$html).$nl;
    }
    
    private function resolveLeftType(string $leftCell, string $rightCell) : string
    {
        if(empty($leftCell))
        {
            return 'empty';
        }
        
        if($leftCell !== $rightCell)
        {
            return 'del';
        }
        
        return 'unmodified';
    }
    
    private function resolveRightType(string $leftCell, string $rightCell) : string
    {
        if(empty($rightCell))
        {
            return 'empty';
        }
        
        if($leftCell !== $rightCell)
        {
            return 'ins';
        }
        
        return 'unmodified';
    }
    
   /**
    * Returns the content of the cell.
    * 
    * @param int $index
    * @param int $type The operation type (inset/delete/unmodified)
    * @return string
    */
    private function getCellContent(int &$index, int $type) : string
    {
        // initialise the HTML
        $html = '';
        $tag = $this->resolveTag($type);
        
        // loop over the matching lines, adding them to the HTML
        while ($index < count($this->array) && $this->array[$index][1] === $type)
        {
            $html .= sprintf(
                '<%1$s>%2$s</%1$s>%3$s',
                $tag,
                htmlspecialchars((string)$this->array[$index][0]),
                $this->separator
            );
            
            $index ++;
        }
        
        return $html;
    }
    
    private function resolveTag(int $type) : string
    {
        switch($type)
        {
            case Diff::DELETED: return 'del';
            case Diff::INSERTED: return 'ins';
        }
        
        return 'span';
    }
}
