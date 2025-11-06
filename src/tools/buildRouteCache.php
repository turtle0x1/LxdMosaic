<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/../../vendor/autoload.php';

$routes = new RouteCollection();

$cacheFile = __DIR__ . '/../classes/App/Router/routes.cache';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../classes/Controllers'));

$seenPaths = [];
$seenNames = [];

foreach ($rii as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    require_once $file->getPathname();

    $namespace = '';
    $className = '';

    $code = file_get_contents($file->getPathname());
    if (preg_match('/namespace\s+([^;]+);/', $code, $m)) {
        $namespace = $m[1];
    }
    if (preg_match('/class\s+(\w+)/', $code, $m)) {
        $className = $m[1];
    }

    if (!$className) {
        continue;
    }

    $fqcn = $namespace ? "{$namespace}\\{$className}" : $className;

    if (!class_exists($fqcn)) {
        continue;
    }

    $refClass = new ReflectionClass($fqcn);
    foreach ($refClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        $attributes = $method->getAttributes(Symfony\Component\Routing\Attribute\Route::class);

        foreach ($attributes as $attribute) {
            $annot = $attribute->newInstance();

            $path = $annot->getPath();
            $name = $annot->getName() ?: strtolower(str_replace('\\', '_', $fqcn . '_' . $method->getName()));

            if (in_array($path, $seenPaths)) {
                throw new \Exception("Duplicate route path '{$path}' detected");
            }
            $seenPaths[] = $path;

            if (in_array($name, $seenNames)) {
                throw new \Exception("Duplicate route name '{$name}' detected");
            }
            $seenNames[] = $name;

            $route = new Route(
                $path,
                array_merge($annot->getDefaults(), [
                    '_controller' => $fqcn,
                    '_method' => $method->getName(),
                ]),
                $annot->getRequirements(),
                $annot->getOptions(),
                $annot->getHost(),
                $annot->getSchemes(),
                $annot->getMethods(),
                $annot->getCondition()
            );

            $routes->add($name, $route);
        }
    }
}

file_put_contents(
    $cacheFile,
    "<?php\n\nreturn unserialize(" . var_export(serialize($routes), true) . ");\n"
);
