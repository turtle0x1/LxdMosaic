<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;
/**
 * This is all so very dumb - but it works, so leave it alone, dont re-format
 * a god dam line
 */
class GetMetricVendorData
{
    private function getFormatedCode()
    {
        $code = file_get_contents("/var/www/LxdMosaic/python/sysMetrics/main.py");
        $x = explode("\n", $code);
        foreach($x as $key=>$str){
            if($key !== 0){
                $x[$key] = "      " . $str;
            }
        }
        return implode($x, "\n");
    }

    public function get($pythonAlias = "python3")
    {
        $code = $this->getFormatedCode();
        return "#cloud-config
write_files:
  - owner: root:root
    path: /etc/cron.d/lxdMosaicMetrics
    content: |
      */1 * * * * root $pythonAlias /etc/lxdMosaic/metrics.py
  - path: /etc/lxdMosaic/metrics.py
    content: |
      $code";
    }
}
