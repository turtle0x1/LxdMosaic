<?php

namespace dhope0000\LXDClient\Tools\Node;

class Hosts
{
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
