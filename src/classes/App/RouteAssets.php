<?php
namespace dhope0000\LXDClient\App;

class RouteAssets
{
    public function route($path)
    {
        $this->outputFile($path);
    }

    public function outputFile($path)
    {
        $path = __DIR__ . "/../../" . implode($path, "/");
        //get the last-modified-date of this very file
        $lastModified=filemtime($path);
        //get a unique hash of this file (etag)
        $etagFile = md5_file($path);
        //get the HTTP_IF_MODIFIED_SINCE header if set
        $ifModifiedSince=(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
        //get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
        $etagHeader=(isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

        //set last-modified header
        header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");
        //set etag-header
        header("Etag: $etagFile");
        //make sure caching is turned on
        header('Cache-Control: public');

        // //check if page has changed. If not, send 304 and exit
        if (@strtotime($ifModifiedSince) == $lastModified || $etagHeader == $etagFile) {
            header("HTTP/1.1 304 Not Modified");
            return;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        header('Content-Type: ' . $extension);
        echo file_get_contents($path);
    }
}
