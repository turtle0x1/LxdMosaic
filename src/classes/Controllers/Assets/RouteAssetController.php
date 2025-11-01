<?php

namespace dhope0000\LXDClient\Controllers\Assets;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RouteAssetController
{
    private $extensionMapping = [
        "css" => "text/css",
        "js" => "text/javascript",
        "png" => "image/png",
        "ttf" => "font/ttf",
        "woff" => "font/woff",
        "woff2" => "font/woff2"
    ];

    /**
     * @Route("/assets/{path}", name="serve_asset", requirements={"path"=".+"})
     */
    public function get(Request $request): Response
    {
        $path = trim($request->getPathInfo(), '/');
        $fullPath = realpath(__DIR__ . "/../../../" . $path);
        $assetsDir = realpath(__DIR__ . "/../../../assets");

        if (substr($fullPath, 0, strlen($assetsDir)) !== $assetsDir) {
            throw new \Exception("Hmm");
        }

        if (($pos = strpos($fullPath, '?')) !== false) {
            $fullPath = substr($fullPath, 0, $pos);
        }

        if (!file_exists($fullPath)) {
            throw new \Exception("File not found");
        }

        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $content = file_get_contents($fullPath);
        $mimeType = $this->extensionMapping[$extension];
        if (!isset($this->extensionMapping[$extension])) {
            throw new \Exception("Mime type not allowed");
        }

        $lastModified = filemtime($fullPath);
        $lastModifiedStr = gmdate('D, d M Y H:i:s', $lastModified) . ' GMT';

        $etag = md5($content);

        $response = new Response($content, 200, [
            'Content-Type' => $mimeType,
            'Last-Modified' => $lastModifiedStr,
            'ETag' => $etag,
        ]);

        $response->headers->remove('Cache-Control');
        $response->headers->set('Cache-Control', 'public, max-age=31536000, s-maxage=31536000');

        if ($response->isNotModified($request)) {
            $response->setStatusCode(304);
            return $response;
        }
        return $response;
    }
}
