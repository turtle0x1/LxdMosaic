<?php
use Symfony\Component\Finder\Finder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherDumper;

require __DIR__ . "/../../vendor/autoload.php";

$iter = (new hanneskod\classtools\Iterator\ClassIterator((new Finder())->in('src/classes/Controllers')))
    ->enableAutoloading();

$reader = new AnnotationReader();
$routeCollection = new RouteCollection();

$iter = iterator_to_array($iter);

usort($iter, function ($a, $b) {
    return $a->getName() > $b->getName() ? 1 : -1;
});

foreach ($iter as $class) {
    $publicMethods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

    foreach ($publicMethods as $method) {
        if ($method->getName() == "__construct") {
            continue;
        }

        $annotation = $reader->getMethodAnnotation(
            $method,
            Symfony\Component\Routing\Annotation\Route::class
        );

        if (empty($annotation)) {
            throw new \Exception("Public controller missing Route annotation ({$class->getName()}::{$method->getName()})", 1);
        }

        $options = $annotation->getOptions();
        $methods = $annotation->getMethods();

        if (count($methods) == 0) {
            throw new \Exception("Public controller missing methods property ({$class->getName()}::{$method->getName()})", 1);
        }

        $rbac = $options["rbac"] ?? null;

        $defaults = $annotation->getDefaults();
        // Add our class, method and rbac to the "defaults" array so we can call
        // them later (the options array is empty later? bug? who knows...)
        $defaults["_controller"] = $class->getName();
        $defaults["_method"] = $method->getName();
        $defaults["_rbac"] = $rbac;

        $route = new Symfony\Component\Routing\Route(
            $annotation->getPath(),
            $defaults,
            $annotation->getRequirements(),
            $options,
            $annotation->getHost(),
            $annotation->getSchemes(),
            $methods,
            $annotation->getCondition()
        );

        $routeCollection->add($annotation->getName(), $route);
    }
}

$compiledRoutes = (new CompiledUrlMatcherDumper($routeCollection))->getCompiledRoutes(true);
file_put_contents(__DIR__ . "/../sensitiveData/application/routes.cache", serialize($compiledRoutes));
