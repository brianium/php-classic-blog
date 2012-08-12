<?php
namespace Test;
trait RepositoryTester
{
    protected function getRepo()
    {
        $reflection = new \ReflectionClass(get_class($this));
        $test = $reflection->getShortName();
        $repoClass = 'Infrastructure\\Persistence\\Doctrine\\' . str_replace('Test', '', $test);
        return new $repoClass($this->manager);
    }
}