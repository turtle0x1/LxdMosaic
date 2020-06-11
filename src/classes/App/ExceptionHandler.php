<?php
namespace dhope0000\LXDClient\App;

use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Hosts\ChangeStatus;
use dhope0000\LXDClient\Tools\Node\Hosts;

class ExceptionHandler
{
    private $getDetails;

    /**
     * @Inject
     */
    public function inject(GetDetails $getDetails, ChangeStatus $changeStatus, Hosts $hosts)
    {
        $this->getDetails = $getDetails;
        $this->changeStatus = $changeStatus;
        $this->hosts = $hosts;
    }

    public function register()
    {
        set_exception_handler([$this, "handle"]);
    }

    public function handle($exception)
    {
        $message = $exception->getMessage();

        $offlineHostMessage = "cURL error 7: Failed to connect to";

        if (StringTools::stringStartsWith($message, $offlineHostMessage)) {
            $host = trim(StringTools::getStringBetween($message, $offlineHostMessage, "port"));
            $port = trim(StringTools::getStringBetween($message, "port", ":"));
            $url = "https://$host:$port";
            $hostId = $this->getDetails->getIdByUrlMatch($url);
            if (is_numeric($hostId)) {
                http_response_code(205);
                $this->changeStatus->setOffline($hostId);
                $this->hosts->reloadHosts();
            }
        }

        echo json_encode([
            "state"=>"error",
            "message"=>$message . " " . $exception->getFile() . " " . $exception->getLine()
        ]);
    }
}
