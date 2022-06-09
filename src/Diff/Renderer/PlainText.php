<?php

declare(strict_types=1);

namespace Mistrals\Diff\Renderer;

use Mistralys\Diff\Diff;
use Mistralys\Diff\DiffException;

class PlainText extends Renderer
{
    private string $separator = "\n";
    
    public function setSeparator(string $separator) : PlainText
    {
        $this->separator = $separator;
        
        return $this;
    }
    
    public function getSeparator() : string
    {
        return $this->separator;
    }

    /**
     * @return string
     * @throws DiffException
     */
    public function render() : string
    {
        $string = '';
        $diff = $this->diff->toArray();
        
        // loop over the lines in the diff
        foreach ($diff as $line)
        {
            // extend the string with the line
            switch ($line[1])
            {
                case Diff::UNMODIFIED : $string .= '  ' . $line[0];break;
                case Diff::DELETED    : $string .= '- ' . $line[0];break;
                case Diff::INSERTED   : $string .= '+ ' . $line[0];break;
            }
            
            // extend the string with the separator
            $string .= $this->separator;
        }
        
        return $string;
    }
}
