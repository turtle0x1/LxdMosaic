<?php
namespace dhope0000\LXDClient\App;

use \DI\Container;
use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\RecordAction;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\Projects\FetchUserProject;
use dhope0000\LXDClient\Model\Users\InvalidateToken;

class RouteApi
{
    private $recordAction;
    private $project;
    private $userId;
    private $fetchAllowedProjects;

    public function __construct(
        Container $container,
        RecordAction  $recordAction,
        GetDetails $getDetails,
        HostList $hostList,
        FetchAllowedProjects $fetchAllowedProjects,
        FetchUserDetails $fetchUserDetails,
        FetchUserProject $fetchUserProject,
        InvalidateToken $invalidateToken
    ) {
        $this->container = $container;
        $this->recordAction = $recordAction;
        $this->getDetails = $getDetails;
        $this->hostList = $hostList;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->fetchUserProject = $fetchUserProject;
        $this->invalidateToken = $invalidateToken;
    }

    public function getRequestedProject()
    {
        return $this->project;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function route($pathParts, $headers, $returnResult = false)
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

        $params = $this->orderParams($_POST, $controllerStr, $method, $userId, $headers);

        $controller = $this->container->make($controllerStr);

        if ($controller instanceof \dhope0000\LXDClient\Interfaces\RecordAction) {
            $this->recordAction->record($userId, $controllerStr . "\\" . $method, $params);
        }

        // TODO Pass provided arguments to controller
        $data = call_user_func_array(array($controller, $method), $params);

        if ($returnResult) {
            return $data;
        }

        //TODO So lazy
        echo json_encode($data);
    }

    public function orderParams($passedArguments, $class, $method, int $userId, $headers)
    {
        $reflectedMethod = new \ReflectionMethod($class, $method);
        $paramaters = $reflectedMethod->getParameters();
        $o = [];

        $allowedProjects = $this->fetchAllowedProjects->fetchAll($userId);

        $userIsAdmin = $this->fetchUserDetails->isAdmin($userId) === '1';

        if (empty($allowedProjects) && !$userIsAdmin) {
            $this->invalidateToken->invalidate($userId, $headers["apitoken"]);
            http_response_code(403);
            throw new \Exception("No Access To Any Projects", 1);
        }

        $currentProjects = $this->fetchUserProject->fetchCurrentProjects($userId);

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

            $project = $this->getRequestedProject();

            if ($hasDefault && !isset($passedArguments[$name])) {
                $o[$name] = $param->getDefaultValue();
            } elseif ($name === "userId") {
                $o[$name] = $userId;
            } elseif ($name == "host") {
                if (!$userIsAdmin) {
                    if (is_null($project) && isset($currentProjects[$passedArguments["hostId"]])) {
                        $project = $currentProjects[$passedArguments["hostId"]];
                    }
                    $this->canAccessProject($allowedProjects, $passedArguments["hostId"], $project);
                }

                $o[$name] = $this->getDetails->fetchHost($passedArguments["hostId"]);
            } elseif ($name == "targetProject") {
                $targetProject = "";
                if (!$userIsAdmin && (isset($passedArguments["targetProject"]) && !empty($passedArguments["targetProject"]))) {
                    $targetProject = $passedArguments["targetProject"];
                    $this->canAccessProject($allowedProjects, $passedArguments["hostId"], $targetProject);
                } elseif ($userIsAdmin && (isset($passedArguments["targetProject"]) && !empty($passedArguments["targetProject"]))) {
                    $targetProject = $passedArguments["targetProject"];
                }
                $o[$name] = $targetProject;
            } elseif ($type == "dhope0000\LXDClient\Objects\Host") {
                if (!$userIsAdmin) {
                    if (is_null($project) && isset($currentProjects[$passedArguments[$name]])) {
                        $project = $currentProjects[$passedArguments[$name]];
                    }
                    $this->canAccessProject($allowedProjects, $passedArguments[$name], $project);
                }


                $o[$name] = $this->getDetails->fetchHost($passedArguments[$name]);
            } elseif ($type == "dhope0000\LXDClient\Objects\HostsCollection") {
                if (!$userIsAdmin) {
                    foreach ($passedArguments[$name] as $hostAttempt) {
                        if (is_null($project) && isset($currentProjects[$hostAttempt])) {
                            $project = $currentProjects[$hostAttempt];
                        }
                        $this->canAccessProject($allowedProjects, $hostAttempt, $project);
                    }
                }

                $o[$name] = $this->hostList->getHostCollection($passedArguments[$name]);
            } elseif (!$hasDefault && !isset($passedArguments[$name])) {
                throw new \Exception("Missing Paramater $name", 1);
            } else {
                $o[$name] = $passedArguments[$name];
            }
        }
        return $o;
    }

    private function canAccessProject($allowedProjects, $hostId, $project)
    {
        if (!is_string($project) || strlen($project) == 0) {
            throw new \Exception("Cant work out which project to use", 1);
        }

        if (!isset($allowedProjects[$hostId])) {
            throw new \Exception("No access to project", 1);
        }

        if (!in_array($project, $allowedProjects[$hostId])) {
            throw new \Exception("Not allowed to access project", 1);
        }
    }
}
