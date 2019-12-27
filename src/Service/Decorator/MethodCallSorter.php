<?php

namespace GenSys\GenerateBundle\Service\Decorator;

use GenSys\GenerateBundle\Model\MethodCall;

class MethodCallSorter extends IterableDecorator
{
    /**
     * @param iterable $items
     * @return iterable
     */
    public function decorate(iterable $items): iterable
    {
        return $this->groupMethodCallsBySubject($items);
    }

    /**
     * @param MethodCall[] $methodCalls
     * @return MethodCall[]
     */
    private function groupMethodCalls(iterable $methodCalls): iterable
    {
        $groupedMethodCalls = [];
        foreach ($methodCalls as $methodCall) {
            $groupedMethodCalls[$methodCall->getSubject()][] = $methodCall;
        }

        return $groupedMethodCalls;
    }

    /**
     * @param MethodCall[] $methodCalls
     * @return array
     */
    private function groupMethodCallsBySubject(iterable $methodCalls): iterable
    {
        $sortedMethodCalls = [];
        $groupedMethodCalls = $this->groupMethodCalls($methodCalls);
        foreach ($groupedMethodCalls as $group) {
            foreach ($group as $methodCall) {
                $sortedMethodCalls[] = $methodCall;
            }
        }

        return $sortedMethodCalls;
    }
}