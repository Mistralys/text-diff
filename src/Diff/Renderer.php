<?php

declare(strict_types=1);

namespace Mistrals\Diff\Renderer;

use Mistralys\Diff\Diff;

abstract class Renderer
{
    protected Diff $diff;
    
    public function __construct(Diff $diff)
    {
        $this->diff = $diff;
    }
    
   /**
    * @return mixed
    */
    abstract public function render();
}
