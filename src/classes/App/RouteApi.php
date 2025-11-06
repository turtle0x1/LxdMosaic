<?php

namespace dhope0000\LXDClient\App;

use dhope0000\LXDClient\Controllers\Assets\RouteViewController;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\InvalidateToken;
use dhope0000\LXDClient\Model\Users\Projects\FetchUserProject;
use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\RecordAction;
use DI\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class RouteApi
{
    private $project;

    private $userId;

    public function __construct(
        private readonly Container $container,
        private readonly RecordAction $recordAction,
        private readonly GetDetails $getDetails,
        private readonly HostList $hostList,
        private readonly FetchAllowedProjects $fetchAllowedProjects,
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly FetchUserProject $fetchUserProject,
        private readonly InvalidateToken $invalidateToken,
        private readonly RouteViewController $routeViewController
    ) {
    }

    public function getRequestedProject()
    {
        return $this->project;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function route($request, $headers, $returnResult = false)
    {
        $userId = $headers['userid'];

        $this->project = isset($headers['project']) && !empty($headers['project']) ? $headers['project'] : null;
        $this->userId = $userId;

        $routes = require __DIR__ . '/Router/routes.cache';
        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($routes, $context);

        try {
            $parameters = $matcher->match($context->getPathInfo());
            $controllerClass = $parameters['_controller'];

            $method = $parameters['_method'];
            if (!class_exists($controllerClass)) {
                throw new \Exception('End point not found', 1);
            } elseif (method_exists($controllerClass, $method) !== true) {
                throw new \Exception('Method point not found');
            }

            $params = $this->orderParams($_POST, $controllerClass, $method, $userId, $headers, $request);
            $controller = $this->container->make($controllerClass);

            if ($controller instanceof \dhope0000\LXDClient\Interfaces\RecordAction) {
                $this->recordAction->record($userId, $controllerClass . '\\' . $method, $params);
            }

            $data = call_user_func_array([$controller, $method], $params);

            if ($returnResult) {
                return $data;
            }

            if ($data instanceof Response) {
                $data->send();
            } else {
                echo json_encode($data);
            }
        } catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException) {
            if (str_starts_with((string) $request->getPathInfo(), '/api')) {
                http_response_code(404);
                echo json_encode([
                    'error' => 'Not found',
                ]);
            } else {
                $this->routeViewController->route($request)
                    ->send();
            }
        }
    }

    public function orderParams($passedArguments, $class, $method, int $userId, $headers, $request)
    {
        $reflectedMethod = new \ReflectionMethod($class, $method);
        $paramaters = $reflectedMethod->getParameters();
        $o = [];

        $allowedProjects = $this->fetchAllowedProjects->fetchAll($userId);

        $userIsAdmin = $this->fetchUserDetails->isAdmin($userId);

        if (empty($allowedProjects) && !$userIsAdmin) {
            $this->invalidateToken->invalidate($userId, $headers['apitoken']);
            http_response_code(403);
            throw new \Exception('No Access To Any Projects', 1);
        }

        $currentProjects = $this->fetchUserProject->fetchCurrentProjects($userId);

        foreach ($paramaters as $param) {
            $name = $param->getName();
            $hasDefault = $param->isDefaultValueAvailable();

            $type = $param->getType();
            if (!empty($type)) {
                $type = $type->getName();
            }

            if ($name == 'host' && !isset($passedArguments['hostId'])) {
                throw new \Exception('Missing paramater hostId', 1);
            }

            $project = $this->getRequestedProject();

            if ($hasDefault && !isset($passedArguments[$name])) {
                $o[$name] = $param->getDefaultValue();
            } elseif ($name === 'userId') {
                $o[$name] = $userId;
            } elseif ($name == 'host') {
                if (!$userIsAdmin) {
                    if ($project === null && isset($currentProjects[$passedArguments['hostId']])) {
                        $project = $currentProjects[$passedArguments['hostId']];
                    }
                    $this->canAccessProject($allowedProjects, $passedArguments['hostId'], $project);
                }

                $o[$name] = $this->getDetails->fetchHost($passedArguments['hostId']);
            } elseif ($name == 'targetProject') {
                $targetProject = '';
                if (!$userIsAdmin && (isset($passedArguments['targetProject']) && !empty($passedArguments['targetProject']))) {
                    $targetProject = $passedArguments['targetProject'];
                    $this->canAccessProject($allowedProjects, $passedArguments['hostId'], $targetProject);
                } elseif ($userIsAdmin && (isset($passedArguments['targetProject']) && !empty($passedArguments['targetProject']))) {
                    $targetProject = $passedArguments['targetProject'];
                }
                $o[$name] = $targetProject;
            } elseif ($type == "dhope0000\LXDClient\Objects\Host") {
                if (!$userIsAdmin) {
                    if ($project === null && isset($currentProjects[$passedArguments[$name]])) {
                        $project = $currentProjects[$passedArguments[$name]];
                    }
                    $this->canAccessProject($allowedProjects, $passedArguments[$name], $project);
                }

                $o[$name] = $this->getDetails->fetchHost($passedArguments[$name]);
            } elseif ($type == "dhope0000\LXDClient\Objects\HostsCollection") {
                if (!$userIsAdmin) {
                    foreach ($passedArguments[$name] as $hostAttempt) {
                        if ($project === null && isset($currentProjects[$hostAttempt])) {
                            $project = $currentProjects[$hostAttempt];
                        }
                        $this->canAccessProject($allowedProjects, $hostAttempt, $project);
                    }
                }

                $o[$name] = $this->hostList->getHostCollection($passedArguments[$name]);
            } elseif ($type == Request::class) {
                $o[$name] = $request;
            } elseif (!$hasDefault && !isset($passedArguments[$name])) {
                throw new \Exception("Missing Paramater {$name}", 1);
            } else {
                $o[$name] = $passedArguments[$name];
            }
        }
        return $o;
    }

    private function canAccessProject($allowedProjects, $hostId, $project)
    {
        if (!is_string($project) || strlen($project) == 0) {
            throw new \Exception('Cant work out which project to use', 1);
        }

        if (!isset($allowedProjects[$hostId])) {
            throw new \Exception('No access to project', 1);
        }

        if (!in_array($project, $allowedProjects[$hostId])) {
            throw new \Exception('Not allowed to access project', 1);
        }
    }
}
