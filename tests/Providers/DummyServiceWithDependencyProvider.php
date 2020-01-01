<?php

namespace Tests\Providers;

use GenSys\GenerateBundle\Resources\Dummy\Service\DummyServiceWithDependency;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;

class DummyServiceWithDependencyProvider
{
    /**
     * @return ReflectionMethod
     */
    public function getConstructor(): ReflectionMethod
    {
        $reflectionClass = $this->getReflectionClass();
        return $reflectionClass->getConstructor();
    }

    /**
     * @return ReflectionMethod[]
     */
    public function getAllMethods(): array
    {
        $reflectionClass = $this->getReflectionClass();
        $methods = [];
        foreach ($reflectionClass->getMethods() as $method) {
            $methods[] = [$method];
        }

        return $methods;
    }

    /**
     * @return ReflectionMethod
     */
    public function getAddToProperty(): ReflectionMethod
    {
        $reflectionClass = $this->getReflectionClass();
        try {
            $addToProperty = 'addToProperty';
            $reflectionMethod = $reflectionClass->getMethod($addToProperty);
        } catch (ReflectionException $e) {
            throw new RuntimeException("Method $addToProperty not found");
        }

        return $reflectionMethod;
    }

    /**
     * @return ReflectionMethod
     */
    public function getAddToDummyValue(): ReflectionMethod
    {
        try {
            $methodName = 'addToDummyValueProperty';
            $reflectionMethod = $this->getReflectionClass()->getMethod($methodName);
        } catch (ReflectionException $e) {
            $message = sprintf('Method %s not found', $methodName);
            throw new RuntimeException($message);
        }

        return $reflectionMethod;
    }

    /**
     * @return ReflectionClass
     */
    private function getReflectionClass(): ReflectionClass
    {
        try {
            $className = DummyServiceWithDependency::class;
            $reflectionClass = new ReflectionClass($className);
        } catch (ReflectionException $e) {
            $message = sprintf('Class %s not found', $className);
            throw new RuntimeException($message);
        }

        return $reflectionClass;
    }
}
