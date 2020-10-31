<?php

namespace dhope0000\LXDClient\Tools\Instances\Files;

use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class GetPath
{
    private $cache;

    public function __construct(ArrayAdapter $cache)
    {
        $this->cache = $cache;
    }

    public function get(
        Host $host,
        string $instance,
        string $path,
        string $download
    ) {
        $host->callClientMethod("addCache", $this->cache);

        $response = $host->instances->files->read($instance, $path);

        $this->instanceUrlKey = str_replace("/", "", $host->instances->getEndpoint());
        $params = http_build_query([
            "project"=>$host->getProject(),
            "path"=>$path
        ]);

        $cacheKey = hash("sha1", "GET " . $host->callClientMethod("getUrl") . "/1.0/$this->instanceUrlKey/$instance/files?$params");

        if ($response == null) {
            $isDirectory = $this->isCachedResponsePathDirectory($cacheKey);
            if (!$isDirectory) {
                throw new \Exception("The file is litreally null", 1);
            }
        } else {
            $isDirectory = is_array($response) || $response == null;
        }

        // This will exit
        if (!$isDirectory && $download) {
            $this->downloadFile($cacheKey, $response);
        }

        // We dont send the output of files here as its not required
        $contents = is_string($response) ? null : $this->labelDirContents($host, $instance, $path, $response);

        return [
            "isDirectory"=>$isDirectory,
            "contents"=>$contents
        ];
    }

    private function labelDirContents($host, $instance, $path, $contents)
    {
        foreach ($contents as $index => $content) {
            $contentPath = $path == "/" ? "/$content" : "$path/$content";

            $response = $host->instances->files->read($instance, $contentPath);
            $params = http_build_query([
                "project"=>$host->getProject(),
                "path"=>$contentPath
            ]);
            $cacheKey = hash("sha1", "GET " . $host->callClientMethod("getUrl") . "/1.0/$this->instanceUrlKey/$instance/files?$params");
            $contents[$index] = [
                "name"=>$content,
                "isDirectory"=>$this->isCachedResponsePathDirectory($cacheKey)
            ];
        }

        $dirs = [];
        $files = [];
        foreach ($contents as $content) {
            if ($content["isDirectory"]) {
                $dirs[] = $content;
            } else {
                $files[] = $content;
            }
        }

        usort($files, function ($a, $b) {
            return strcmp($a["name"], $b["name"]);
        });

        usort($dirs, function ($a, $b) {
            return strcmp($a["name"], $b["name"]);
        });


        return array_merge($dirs, $files);
    }

    private function isCachedResponsePathDirectory(string $cacheKey)
    {
        $response = $this->cache->getItem($cacheKey);

        $response = $response->get()["response"];

        if ($response === null) {
            return false;
        }

        $headers = $response->getHeaders();

        if (isset($headers["X-Lxd-Type"]) && $headers["X-Lxd-Type"][0] == "directory") {
            return true;
        }

        return in_array('X-Lxd-Gid', $headers);
    }

    private function downloadFile(string $cacheKey, string $currentResponseBody)
    {
        $response = $this->cache->getItem($cacheKey);

        $response = $response->get()["response"];

        if (headers_sent()) {
            throw new \RuntimeException('Headers were already sent. The response could not be emitted!');
        }

        $statusLine = sprintf(
            'HTTP/%s %s %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );

        header($statusLine, true);

        foreach ($response->getHeaders() as $name => $values) {
            $responseHeader = sprintf(
                '%s: %s',
                $name,
                $response->getHeaderLine($name)
            );
            header($responseHeader, false); /* The header doesn't replace a previous similar header. */
        }

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="' . $this->getFileName($response->getHeader("Content-Disposition")[0]) . '"');

        // Step 3: Output the message body.
        echo $currentResponseBody;
        exit();
    }

    private function getFileName($string)
    {
        return str_replace("inline;filename=", "", $string);
    }
}
