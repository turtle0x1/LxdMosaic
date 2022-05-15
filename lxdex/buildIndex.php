<?php

use Symfony\Component\Yaml\Yaml;

require __DIR__ . "/../vendor/autoload.php";

// NOTE So this takes over 1 second on somewhere between 4000-8000 hosts

$output = json_decode(file_get_contents(__DIR__ . "/cache.json"), true);

// Copy and paste the below line to simulate more host data to parse
// $output = array_merge($output, array_values($output));
// var_dump(count($output));

// Inspired by https://stackoverflow.com/a/28473131/4008082
function buildIndex(array $input, array $indexableKeys, array &$index, string $currentKey = '', array &$uniqueEntities = [], int &$idChain = 0) :void
{
    foreach ($input as $key=>$value) {
        foreach ($indexableKeys as $searchKey => $searchCallback) {
            $i = preg_match($searchKey, ($currentKey . "[$key]"));

            if ($i !== 0) {
                if (!is_array($value)) {
                    if (!isset($uniqueEntities[$currentKey . "[$key]"])) {
                        $uniqueEntities[$currentKey . "[$key]"] = $idChain;
                        $idChain = $idChain + 1;
                    }

                    if (is_callable($searchCallback)) {
                        $searchCallback($key, ($currentKey . "[$key]"), $value, $index);
                    } else {
                        $v = strtolower($value);

                        if ($value == "") {
                            continue;
                        }

                        if (!isset($index[$v])) {
                            $index[$v] = [];
                        }

                        $index[$v][] = $uniqueEntities[$currentKey . "[$key]"];
                        // $index[$v][] = $currentKey . "[$key]";
                    }
                } else {
                    throw new \Exception("Indexing array keys not supported yet", 1);
                }
            }
        }

        if (is_array($value)) {
            buildIndex($value, $indexableKeys, $index, $currentKey . "[$key]", $uniqueEntities, $idChain);
        }
    }
}

$valueToListFunc = function ($key, $path, $value, &$index) {
    if ($value == "") {
        return;
    }
    if (!isset($index[$value])) {
        $index[$value] = [];
    }
    if (!isset($index[$value][$key])) {
        $index[$value][$key] = [];
    }
    $index[$value][$key][] = $path;
};

$indexableKeys = [
    // Status of running instances
    '/\[([^]]+)\]\[([^]]+)\]\[instances\]\[([^]]+)\]\[state\]\[status\]/m'=>null,
    // Require cuda
    '/\[([^]]+)\]\[([^]]+)\]\[instances\]\[([^]]+)\]\[config\]\[nvidia\.require\.cuda\]/m'=>$valueToListFunc,
    // Require cuda
    '/\[([^]]+)\]\[([^]]+)\]\[instances\]\[([^]]+)\]\[config\]\[nvidia\.runtime\]/m'=>$valueToListFunc,
    // IP addresses
    '/\[([^]]+)\]\[([^]]+)\]\[instances\]\[([^]]+)\]\[state\]\[network\]\[([^]]+)\]\[addresses\]\[([^]]+)\]\[address\]/m'=>null,
    // Mac addresses
    '/\[([^]]+)\]\[([^]]+)\]\[instances\]\[([^]]+)\]\[state\]\[network\]\[([^]]+)\]\[hwaddr\]/m'=>null,
    // Target folder paths on instance specific mounts
    '/\[([^]]+)\]\[([^]]+)\]\[instances\]\[([^]]+)\]\[devices\]\[share\]\[path\]/m'=>null,
    // Source folder paths on instance specific mounts
    '/\[([^]]+)\]\[([^]]+)\]\[instances\]\[([^]]+)\]\[devices\]\[share\]\[source\]/m'=>null,
    // All image properties
    '/\[([^]]+)\]\[([^]]+)\]\[images\]\[([^]]+)\]\[properties\]\[([^]]+)\]/m'=>null,
    // All image fingerprints
    '/\[([^]]+)\]\[([^]]+)\]\[images\]\[([^]]+)\]\[fingerprint\]/m'=>null,
    // Any Top Level Name
    '/^\[([^]]+)\]\[([^]]+)\]\[([^]]+)\]\[([^]]+)\]\[name\]/'=>null,
    // Any Operating System Names
    '/\[([^]]+)\]\[([^]]+)\]\[([^]]+)\]\[([^]]+)\]\[(properties|config)\]\[(os|image\.os)\]/'=>null,
    // Cloud Config Data
    '/\[([^]]+)\]\[([^]]+)\]\[([^]]+)\]\[([^]]+)\]\[config\]\[\user\.user-data\]/'=>function ($key, $path, $value, &$index) {
        try {
            $x = Yaml::parse($value);
            if (isset($x["packages"])) {
                foreach ($x["packages"] as $package) {
                    if (!isset($index[$package])) {
                        $index[$package] = [];
                    }
                    $index[$package][] = $path;
                }
            }
        } catch (\Throwable $e) { // Slienty ingore user-data we cant parse as yaml
        }
    },
    // All storage drivers
    '/\[([^]]+)\]\[([^]]+)\]\[storage\]\[([^]]+)\]\[driver\]/m'=>null,
];

$index = [];
$uniqueEntities = [];
$idChain = 0;
buildIndex($output, $indexableKeys, $index, '', $uniqueEntities, $idChain);
file_put_contents(__DIR__ . "/index.json", json_encode(["dataIndex"=>$index, "entityIndex"=>$entityIndex]));
