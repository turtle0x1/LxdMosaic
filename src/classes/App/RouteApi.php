<?php
namespace dhope0000\LXDClient\App;

use \DI\Container;
use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\RecordAction;

class RouteApi
{
    private $recordAction;

    public function __construct(Container $container, RecordAction  $recordAction)
    {
        $this->container = $container;
        $this->recordAction = $recordAction;
    }

    public function route($pathParts, $userId)
    {
        if (count($pathParts) < 3) {
            throw new \Exception("Api String Not Long Enough", 1);
        }

        unset($pathParts[0]);

        end($pathParts);

        $methodkey = key($pathParts);
        $method = $pathParts[$methodkey];

        unset($pathParts[$methodkey]);

        $controllerStr = "dhope0000\\LXDClient\\Controllers\\" . implode($pathParts, "\\");

        if (!class_exists($controllerStr)) {
            throw new \Exception("End point not found", 1);
        } elseif (method_exists($controllerStr, $method) !== true) {
            throw new \Exception("Method point not found");
        }

        $params = $this->orderParams($_POST, $controllerStr, $method);

        $controller = $this->container->make($controllerStr);

        if ($controller instanceof \dhope0000\LXDClient\Interfaces\RecordAction) {
            $params["userId"] = $userId;
            $this->recordAction->record($controllerStr . "\\" . $method, $params);
            unset($params["userId"]);
        }

        // TODO Pass provided arguments to controller
        $data = call_user_func_array(array($controller, $method), $params);
        //TODO So lazy
        echo json_encode($data);
    }

    public function orderParams($passedArguments, $class, $method)
    {
        $reflectedMethod = new \ReflectionMethod($class, $method);
        $paramaters = $reflectedMethod->getParameters();
        $o = [];
        foreach ($paramaters as $param) {
            $name = $param->getName();
            $hasDefault = $param->isDefaultValueAvailable();

            if (!$hasDefault && !isset($passedArguments[$name])) {
                throw new \Exception("Missing Paramater $name", 1);
            } elseif ($hasDefault && !isset($passedArguments[$name])) {
                $o[$name] = $param->getDefaultValue();
                continue;
            }

            $o[$name] = $passedArguments[$name];
        }
        return $o;
    }
}
