<?php

namespace GenSys\GenerateBundle\Model\Decorator;

use GenSys\GenerateBundle\Model\Structure\MethodCall;

class SortedMethodCalls extends AbstractMethodCallDecorator
{
    /**
     * @param iterable $methodCalls
     * @return iterable
     */
    protected function decorate(iterable $methodCalls): iterable
    {
        return $this->sortMethodCalls($methodCalls);
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
    private function sortMethodCalls(iterable $methodCalls): iterable
    {
        $sortedMethodCalls = [];
        foreach ($this->groupMethodCalls($methodCalls) as $subject => $group) {
            foreach ($group as $methodCall) {
                $sortedMethodCalls[] = $methodCall;
            }
        }

        return $sortedMethodCalls;
    }
}