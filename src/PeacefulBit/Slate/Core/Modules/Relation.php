<?php

namespace PeacefulBit\Slate\Core\Modules\Relation;

use function Nerd\Common\Arrays\toHeadTail;

function relationReduce(NodeCalculatorVisitor $visitor, $callback, array $arguments)
{
    if (empty($arguments)) {
        throw new RuntimeException("Too few arguments");
    }
    $iter = function ($rest, $first) use (&$iter, $callback, $visitor) {
        if (empty($rest)) {
            return true;
        }
        list ($head, $tail) = toHeadTail($rest);
        $visitedHead = $visitor->valueOf($head);
        if ($callback($first, $visitedHead)) {
            return $iter($tail, $visitedHead);
        }
        return false;
    };
    list ($head, $tail) = toHeadTail($arguments);
    return $iter($tail, $visitor->valueOf($head));
}

function export()
{
    return [
        '>' => new NativeNode('>', function (NodeCalculatorVisitor $visitor, array $arguments) {
            return relationReduce($visitor, function ($first, $second) {
                return $first > $second;
            }, $arguments);
        }),
        '<' => new NativeNode('<', function (NodeCalculatorVisitor $visitor, array $arguments) {
            return relationReduce($visitor, function ($first, $second) {
                return $first < $second;
            }, $arguments);
        }),
        '>=' => new NativeNode('>=', function (NodeCalculatorVisitor $visitor, array $arguments) {
            return relationReduce($visitor, function ($first, $second) {
                return $first >= $second;
            }, $arguments);
        }),
        '<=' => new NativeNode('<=', function (NodeCalculatorVisitor $visitor, array $arguments) {
            return relationReduce($visitor, function ($first, $second) {
                return $first <= $second;
            }, $arguments);
        }),
        '=' => new NativeNode('=', function (NodeCalculatorVisitor $visitor, array $arguments) {
            return relationReduce($visitor, function ($first, $second) {
                return $first == $second;
            }, $arguments);
        }),
        '==' => new NativeNode('==', function (NodeCalculatorVisitor $visitor, array $arguments) {
            return relationReduce($visitor, function ($first, $second) {
                return $first === $second;
            }, $arguments);
        }),
        '!=' => new NativeNode('!=', function (NodeCalculatorVisitor $visitor, array $arguments) {
            return relationReduce($visitor, function ($first, $second) {
                return $first != $second;
            }, $arguments);
        }),
        '!==' => new NativeNode('!==', function (NodeCalculatorVisitor $visitor, array $arguments) {
            return relationReduce($visitor, function ($first, $second) {
                return $first !== $second;
            }, $arguments);
        })
    ];
}
