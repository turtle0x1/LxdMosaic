<?php

namespace dhope0000\LXDClient\Tools\Utilities;

class IsUpToDate
{
    public static function isIt()
    {
        $x = [
            'master' => false,
            'currentVersion' => isset($_ENV['SNAP']) ?? "GIT",
            'cantSeeGithub' => false,
            'snap' => isset($_ENV['SNAP']),
            'newVersion' => false,
            'newVersionUrl' => false,
        ];

        if ($x['snap']) {
            return $x;
        }
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.github.com/repos/turtle0x1/LxdMosaic/git/refs/tags',
            CURLOPT_USERAGENT => 'LXDMosaic',
            CURLOPT_TIMEOUT => 3 // Some environments will be isolated the net without this it crashes waiting
        ]);
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        $githubData = json_decode($resp, true);
        
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (json_last_error() || $statusCode !== 200) {
            $x['cantSeeGithub'] = true;
            return $x;
        }

        $gitVersion = trim((string) `git symbolic-ref -q --short HEAD || git describe --tags --exact-match`);

        $x['currentVersion'] = $gitVersion;

        if ($gitVersion == 'master') {
            $x['master'] = true;
            return $x;
        }

        $gitVersion = str_replace('v', '', $gitVersion);
        $gitVersion = (int) str_replace('.', '', $gitVersion);

        foreach ($githubData as $data) {
            $o = str_replace('refs/tags/v', '', $data['ref']);
            $ghVersion = (int) str_replace('.', '', $o);
            if ($ghVersion > $gitVersion) {
                $x['newVersionUrl'] = "https://github.com/turtle0x1/LxdMosaic/releases/tag/v{$o}";
                $x['newVersion'] = $o;
                return $x;
            }
        }

        return $x;
    }
}
