<?php

namespace GenSys\GenerateBundle\PhpParser\Filter\Node;

use PhpParser\NodeFinder;

abstract class AbstractNodeFilter implements NodeFilter
{
    /** @var NodeFinder */
    protected $nodeFinder;

    public function __construct(
    ) {
        $this->nodeFinder = new NodeFinder();
    }
}