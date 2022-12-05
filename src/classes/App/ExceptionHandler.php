<?php
namespace dhope0000\LXDClient\App;

use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Hosts\ChangeStatus;

class ExceptionHandler
{
    private GetDetails $getDetails;
    private ChangeStatus $changeStatus;
    private StringTools $stringTools;

    /**
     * @Inject
     */
    public function inject(GetDetails $getDetails, ChangeStatus $changeStatus, StringTools $stringTools) :void
    {
        $this->getDetails = $getDetails;
        $this->changeStatus = $changeStatus;
        $this->stringTools = $stringTools;
    }

    public function register() :void
    {
        set_exception_handler([$this, "handle"]);
    }

    public function handle($exception) :void
    {
        $message = $exception->getMessage();

        $offlineHostMessage = "cURL error 7: Failed to connect to";

        if ($this->stringTools->stringStartsWith($message, $offlineHostMessage)) {
            $host = trim(StringTools::getStringBetween($message, $offlineHostMessage, "port"));
            $port = trim(StringTools::getStringBetween($message, "port", ":"));
            $url = "https://$host:$port";
            $hostId = $this->getDetails->getIdByUrlMatch($url);
            if (is_numeric($hostId)) {
                http_response_code(205);
                $this->changeStatus->setOffline((int) $hostId);
            }
        }

        echo json_encode([
            "state"=>"error",
            "message"=>$message . " " . $exception->getFile() . " " . $exception->getLine()
        ]);
    }
}
