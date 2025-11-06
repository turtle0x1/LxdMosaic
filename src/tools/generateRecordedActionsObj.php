<?php

require __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\Routing\Attribute\Route;

$path = __DIR__ . '/../../src/classes/Controllers/';
$fqcns = [];

$allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$phpFiles = new RegexIterator($allFiles, '/\.php$/');
foreach ($phpFiles as $phpFile) {
    $content = file_get_contents($phpFile->getRealPath());
    $tokens = token_get_all($content);
    $namespace = '';
    for ($index = 0; isset($tokens[$index]); $index++) {
        if (!isset($tokens[$index][0])) {
            continue;
        }
        if ($tokens[$index][0] === T_NAMESPACE) {
            $index += 2;
            while (isset($tokens[$index]) && is_array($tokens[$index])) {
                $namespace .= $tokens[$index++][1];
            }
        }
        if ($tokens[$index][0] === T_CLASS) {
            $index += 2;
            $fqcns[] = $namespace . '\\' . $tokens[$index][1];
        }
    }
}

$routeNames = [];
$classTemplate = '<?php

namespace dhope0000\LXDClient\Objects;

class RouteToNameMapping
{
    public $routesToName = REPLACE_ME;

    public function getControllerName(string $controller): string
    {
        return $this->routesToName[$controller] ?? "";
    }
}';

foreach ($fqcns as $class) {
    if (!class_exists($class)) {
        continue;
    }

    $refClass = new ReflectionClass($class);
    if (!$refClass->implementsInterface("dhope0000\LXDClient\Interfaces\RecordAction")) {
        continue;
    }

    foreach ($refClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        if ($method->getName() === '__construct') {
            continue;
        }

        $attributes = $method->getAttributes(Route::class);

        if (!empty($attributes)) {
            /** @var Route $route */
            $route = $attributes[0]->newInstance();
            $routeNames[$class . '\\' . $method->getName()] = $route->getName();
        } else {
            echo "Missing {$class}::{$method->getName()}" . PHP_EOL;
        }
    }
}

$routeNames['dhope0000\\LXDClient\\Controllers\\Images\\ImportLinuxContainersByAliasController\\import']
    = 'Import LinuxContainer.Org Image';

$routesString = var_export($routeNames, true);
$classString = str_replace('REPLACE_ME', $routesString, $classTemplate);
file_put_contents(__DIR__ . '/../classes/Objects/RouteToNameMapping.php', $classString);
