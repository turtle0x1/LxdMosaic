<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\FetchRecordedActions;
use DI\Container;

class GetUserOverview
{
    public function __construct(
        private readonly KnownControllerConversions $knownControllerConversions,
        private readonly FetchRecordedActions $fetchRecordedActions,
        private readonly Container $container
    ) {
    }

    public function get(int $userId, int $targetUser)
    {
        $controllers = $this->knownControllerConversions->getAllControllers();

        $groupedActions = $this->fetchRecordedActions
            ->fetchUserActionsForControllers($targetUser, $controllers);

        $events = [];

        foreach ($groupedActions as $controller => $actions) {
            $convertor = $this->knownControllerConversions->getConvertorClass($controller);
            $convertor = $this->container->make($convertor);
            foreach ($actions as $action) {
                $action['params'] = json_decode((string) $action['params'], true);
                $action = $convertor->convert($action);

                $cat = $action->getCategory();
                if (!isset($events[$cat])) {
                    $events[$cat] = [];
                }

                $method = $action->getMethod();
                if (!isset($events[$cat][$method])) {
                    $events[$cat][$method] = [];
                }

                $events[$cat][$method][] = $action;
            }
        }

        ksort($events);

        foreach ($events as $category => &$methods) {
            ksort($methods);
        }

        return $events;
    }
}
