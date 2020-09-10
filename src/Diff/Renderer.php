<?php

namespace Mistrals\Diff\Renderer;

use Mistralys\Diff\Diff;

abstract class Renderer
{
   /**
    * @var Diff
    */
    protected $diff;
    
    public function __construct(Diff $diff)
    {
        $this->diff = $diff;
    }
    
   /**
    * @return mixed
    */
    abstract public function render();
}
