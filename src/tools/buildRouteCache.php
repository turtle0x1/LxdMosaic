<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/../../vendor/autoload.php';

$routes = new RouteCollection();
$reader = new AnnotationReader();

$cacheFile = __DIR__ . "/../classes/App/Router/routes.cache";
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../classes/Controllers'));

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

    if (!$className) continue;

    $fqcn = $namespace ? "$namespace\\$className" : $className;

    $refClass = new ReflectionClass($fqcn);
    foreach ($refClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        foreach ($reader->getMethodAnnotations($method) as $annot) {
            if ($annot instanceof Symfony\Component\Routing\Annotation\Route) {
                $path = $annot->getPath();
                $name = $annot->getName() ?: strtolower(str_replace('\\', '_', $fqcn . '_' . $method->getName()));
                $route = new Route(
                    $path,
                    array_merge($annot->getDefaults(), ['_controller' => $fqcn, '_method' => $method->getName()]),
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
}

file_put_contents(
    $cacheFile,
    "<?php\n\nreturn unserialize(" . var_export(serialize($routes), true) . ");\n"
);
