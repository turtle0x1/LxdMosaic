<?php

namespace dhope0000\LXDClient\Tools\Node;

class Hosts
{
    public function reloadHosts()
    {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://localhost:3000/hosts/reload',
            CURLOPT_USERAGENT => 'Codular Sample cURL Request',
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
    }

    public function sendMessage(string $type, array $data)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL=>"https://localhost:3000/hosts/message",
            CURLOPT_POST=>1,
            CURLOPT_POSTFIELDS=>"type=$type&data=" . json_encode($data),
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);


        $server_output = curl_exec($ch);

        curl_close($ch);
        var_dump($server_output);
    }
}
