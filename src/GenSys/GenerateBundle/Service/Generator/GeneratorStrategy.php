<?php

namespace GenSys\GenerateBundle\Service\Generator;

interface GeneratorStrategy
{
    public function createTest(string $className);
}