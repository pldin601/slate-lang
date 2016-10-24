<?php

namespace PeacefulBit\Packet;

use PeacefulBit\Packet\Parser\Tokenizer;
use PeacefulBit\Packet\Visitors\NodeCalculatorVisitor;
use PeacefulBit\Packet\Context\Context;

class Calculator
{
    private $rootContext;

    public function __construct(array $native = [])
    {
        $mainContext = array_merge(
            Modules\Math\export(),
            Modules\Logic\export(),
            Modules\Relation\export(),
            $native
        );
        $this->rootContext = new Context($mainContext);
    }

    public function calculate($code)
    {
        $tokenizer = new Tokenizer;
        $visitor = new NodeCalculatorVisitor($this->rootContext);
        $tokens = $tokenizer->tokenize($code);
        $tree = $tokenizer->deflate($tokens);
        $node = $tokenizer->convertSequenceToNode($tree);
        return $visitor->visit($node);
    }
}
