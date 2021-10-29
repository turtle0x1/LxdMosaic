<?php

namespace dhope0000\LXDClient\Tools\Utilities;

use dhope0000\LXDClient\Objects\Host;

/**
 * Based on https://github.com/lxc/lxd/blob/60a079dc506faf1b877d865ff536d2eee6c1d4cb/shared/util.go#L739
 */
class ValidateInstanceName
{
    public static function validate(string $name) :void
    {
        $length = strlen($name);

        if ($length < 1 || $length > 63) {
            throw new \Exception("Instance name to long or short ", 1);
        }

        if ($name[0] == "-") {
            throw new \Exception("Instance name cant start with '-'", 1);
        }

        // Cant start with a digit
        if (preg_match('/^\d/', $name) === 1) {
            throw new \Exception("Instance name cant start with a number", 1);
        }

        // Cant be numeric
        if (is_numeric($name)) {
            throw new \Exception("Instance name cant be a number", 1);
        }

        // Cant end with a "-"
        if ($name[$length - 1] === "-") {
            throw new \Exception("Instance name cant end with a '-'", 1);
        }

        if (preg_match('/[^a-z\-0-9]/i', $name) === 1) {
            throw new \Exception("Instance name can only container alphanumeric characters and hyphens", 1);
        }
    }
}
