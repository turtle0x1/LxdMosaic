<?php

namespace dhope0000\LXDClient\Tools\Hosts\Certificates;

class GetHostCertificate
{
    public static function get(string $hostWithPort) :array
    {
        $g = stream_context_create(array("ssl" => array(
            "capture_peer_cert" => true,
            "verify_peer"=>false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
        ));
        $r = stream_socket_client(
            "ssl://$hostWithPort",
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $g
        );

        if ($r == false) {
            throw new \Exception("Couldn't connect to host: {$hostWithPort}", 1);
        }

        $cont = stream_context_get_params($r);
        $cert = $cont["options"]["ssl"]["peer_certificate"];

        openssl_x509_export($cont["options"]["ssl"]["peer_certificate"], $cert);

        return $cert;
    }
}
