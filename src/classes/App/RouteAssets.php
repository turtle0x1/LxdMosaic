<?php
namespace dhope0000\LXDClient\App;

class RouteAssets
{
    private array $extensionMapping = [
        "css"=>"text/css",
        "js"=>"text/javascript",
        "png"=>"image/png",
        "ttf"=>"font/ttf",
        "woff"=>"font/woff",
        "woff2"=>"font/woff2"
    ];

    public function route(array $path) :void
    {
        $this->outputFile($path);
    }

    public function outputFile(array $path) :void
    {
        $path = __DIR__ . "/../../" . implode("/", $path);
        if (strpos($path, "?") !== false) {
            $path = substr($path, 0, strpos($path, "?"));
        }

        if (!is_file($path)) {
            throw new \Exception("Cant find asset", 1);
        }

        //get the last-modified-date of this very file
        $lastModified=filemtime($path);

        if (!$lastModified) {
            throw new \Exception("Cant find asset", 1);
        }

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

        if (!isset($this->extensionMapping[$extension])) {
            throw new \Exception("Can't map this file type to content-type", 1);
        }

        header('Content-Type: ' . $this->extensionMapping[$extension]);
        echo file_get_contents($path);
    }
}
