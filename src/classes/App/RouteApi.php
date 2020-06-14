<?php
namespace dhope0000\LXDClient\App;

use \DI\Container;
use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\RecordAction;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Hosts\HostList;

class RouteApi
{
    private $recordAction;
    private $project;
    private $userId;

    public function __construct(
        Container $container,
        RecordAction  $recordAction,
        GetDetails $getDetails,
        HostList $hostList
    ) {
        $this->container = $container;
        $this->recordAction = $recordAction;
        $this->getDetails = $getDetails;
        $this->hostList = $hostList;
    }

    public function getRequestedProject()
    {
        return $this->project;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function route($pathParts, $headers)
    {
        $userId = $headers["userid"];

        $this->project = isset($headers["project"]) && !empty($headers["project"]) ? $headers["project"] : null;
        $this->userId = $userId;

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

        $params = $this->orderParams($_POST, $controllerStr, $method, $userId);

        $controller = $this->container->make($controllerStr);

        if ($controller instanceof \dhope0000\LXDClient\Interfaces\RecordAction) {
            $x = $params;
            $x["userId"] = $userId;
            $this->recordAction->record($controllerStr . "\\" . $method, $x);
        }

        // TODO Pass provided arguments to controller
        $data = call_user_func_array(array($controller, $method), $params);

        //TODO So lazy
        echo json_encode($data);
    }

    public function orderParams($passedArguments, $class, $method, int $userId)
    {
        $reflectedMethod = new \ReflectionMethod($class, $method);
        $paramaters = $reflectedMethod->getParameters();
        $o = [];
        $specialParams = ["userId", "host"];
        foreach ($paramaters as $param) {
            $name = $param->getName();
            $hasDefault = $param->isDefaultValueAvailable();

            $type = $param->getType();
            if (!empty($type)) {
                $type = $type->getName();
            }
            
            if ($name == "host" && !isset($passedArguments["hostId"])) {
                throw new \Exception("Missing paramater hostId", 1);
            }


            if ($hasDefault && !isset($passedArguments[$name])) {
                $o[$name] = $param->getDefaultValue();
            } elseif ($name === "userId") {
                $o[$name] = $userId;
            } elseif ($name == "host") {
                $o[$name] = $this->getDetails->fetchHost($passedArguments["hostId"]);
            } elseif ($type == "dhope0000\LXDClient\Objects\Host") {
                $o[$name] = $this->getDetails->fetchHost($passedArguments[$name]);
            } elseif ($type == "dhope0000\LXDClient\Objects\HostsCollection") {
                $o[$name] = $this->hostList->getHostCollection($passedArguments[$name]);
            } elseif (!$hasDefault && !isset($passedArguments[$name])) {
                throw new \Exception("Missing Paramater $name", 1);
            } else {
                $o[$name] = $passedArguments[$name];
            }
        }
        return $o;
    }
}
