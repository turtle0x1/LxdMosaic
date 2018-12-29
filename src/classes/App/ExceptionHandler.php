<?php
namespace dhope0000\LXDClient\App;

class ExceptionHandler
{
    public function register()
    {
        set_exception_handler([$this, "handle"]);
    }

    public function handle($exception)
    {
        echo json_encode(
            [
                "state"=>"error",
                "message"=>$exception->getMessage()
            ]
        );
    }
}
