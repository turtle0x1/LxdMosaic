<?php

namespace dhope0000\LXDClient\App;

use dhope0000\LXDClient\Exceptions\Users\Permissions\PermissionsError;
use dhope0000\LXDClient\Model\Hosts\ChangeStatus;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use DI\Attribute\Inject;

class ExceptionHandler
{
    private $getDetails;

    private $changeStatus;

    private $stringTools;

    #[Inject]
    public function inject(GetDetails $getDetails, ChangeStatus $changeStatus, StringTools $stringTools)
    {
        $this->getDetails = $getDetails;
        $this->changeStatus = $changeStatus;
        $this->stringTools = $stringTools;
    }

    public function register()
    {
        set_exception_handler($this->handle(...));
    }

    public function handle($exception)
    {
        $message = $exception->getMessage();

        $offlineHostMessage = 'cURL error 7: Failed to connect to';

        if ($this->stringTools->stringStartsWith($message, $offlineHostMessage)) {
            $host = trim((string) StringTools::getStringBetween($message, $offlineHostMessage, 'port'));
            $port = trim((string) StringTools::getStringBetween($message, 'port', ':'));
            $url = "https://{$host}:{$port}";
            $hostId = $this->getDetails->getIdByUrlMatch($url);
            if (is_numeric($hostId)) {
                http_response_code(205);
                $this->changeStatus->setOffline($hostId);
            }
        }

        if ($exception instanceof PermissionsError) {
            http_response_code(403);
        }

        echo json_encode([
            'state' => 'error',
            'message' => $message . ' ' . $exception->getFile() . ' ' . $exception->getLine(),
        ]);
    }
}
