<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\Rename;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class RenameProfileController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $rename;
    
    public function __construct(Rename $rename)
    {
        $this->rename = $rename;
    }
    /**
     * @Route("/api/Profiles/RenameProfileController/rename", name="Rename Profile", methods={"POST"})
     */
    public function rename(
        Host $host,
        string $currentName,
        string $newProfileName
    ) {
        $this->rename->rename(
            $host,
            $currentName,
            $newProfileName
        );
        return ["state"=>"success", "message"=>"Updated profile name"];
    }
}
