<?php

declare(strict_types=1);

namespace Mistrals\Diff\Renderer;

use Mistralys\Diff\Diff;

class HTML extends Renderer
{
   /**
    * @var string
    */
    private $separator = '<br>';
    
   /**
    * @var string
    */
    private $container = '<div class="text-diff-container">%s</div>';
    
    public function setSeparator(string $separator) : HTML
    {
        $this->separator = $separator;
        
        return $this;
    }
    
    public function getSeparator() : string
    {
        return $this->separator;
    }
    
    public function getContainer() : string
    {
        return $this->container;
    }
    
    public function render() : string
    {
        $html = '';
        
        $diff = $this->diff->toArray();
        
        // loop over the lines in the diff
        foreach ($diff as $line)
        {
            // extend the HTML with the line
            switch ($line[1])
            {
                case Diff::UNMODIFIED : $element = 'span'; break;
                case Diff::DELETED    : $element = 'del';  break;
                case Diff::INSERTED   : $element = 'ins';  break;
            }
            
            $html .= sprintf(
                '<%1$s>%2$s</%1$s>',
                $element,
                htmlspecialchars($line[0])
            );
            
            // extend the HTML with the separator
            $html .= $this->separator;
        }
        
        return sprintf($this->container, $html);
    }
}
