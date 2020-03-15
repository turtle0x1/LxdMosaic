<?php

namespace dhope0000\LXDClient\Tools\Containers\Files;

use dhope0000\LXDClient\Model\Client\LxdClient;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class GetPath
{
    private $lxdClient;
    private $cache;

    public function __construct(LxdClient $lxdClient, ArrayAdapter $cache)
    {
        $this->lxdClient = $lxdClient;
        $this->cache = $cache;
    }

    public function get(
        int $hostId,
        string $container,
        string $path,
        string $download
    ) {
        $client = $this->lxdClient->getANewClient($hostId);

        $client->addCache($this->cache, []);

        $response = $client->instances->files->read($container, $path);

        $cacheKey = hash("sha1", "GET " . $client->getUrl() . "/1.0/containers/$container/files?path=$path");

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
        $contents = is_string($response) ? null : $this->labelDirContents($client, $container, $path, $response);

        return [
            "isDirectory"=>$isDirectory,
            "contents"=>$contents
        ];
    }

    private function labelDirContents($client, $container, $path, $contents)
    {
        foreach ($contents as $index => $content) {
            $contentPath = $path == "/" ? "/$content" : "$path/$content";

            $response = $client->instances->files->read($container, $contentPath);
            $cacheKey = hash("sha1", "GET " . $client->getUrl() . "/1.0/containers/$container/files?path=$contentPath");
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
