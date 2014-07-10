<?php

namespace Paxal\Airplay;

class Avahi
{
    /**
     * Get a list of servers providing a certain service.
     * This function will return a list of array containing name, address and port of service.
     *
     * @param string $service The service id (eg _airplay._tcp)
     *
     * @return array
     */
    public static function getServerForService($service)
    {
        exec(
            sprintf(
                'avahi-browse -t --resolve -p --no-db-lookup %s',
                escapeshellarg($service)
            ),
            $output
        );
        return static::parseResults($output);
    }

    /**
     * Parse Avahi results to get server list
     *
     * @param string[] $lines avahi-browse command output
     *
     * @return array
     */
    protected static function parseResults($lines)
    {
        $servers = array();
        $i=0;
        foreach ($lines as $line) {
            if (strpos($line, '=') === 0) {
                $service = explode(';', $line);
                if ($service[2] == 'IPv4') {
                    $i++;
                    $servers[$i] = array(
                        'name' => static::unescape($service[3]),
                        'address' => $service[7],
                        'port' => intval($service[8])
                    );
                }
            }
        }

        return $servers;
    }

    /**
     * Unescape a string
     *
     * @param string $s
     *
     * @return string mixed
     */
    protected static function unescape($s)
    {
        return preg_replace_callback(
            '@\\\\(\d\d\d)@',
            function ($matches) {
                return chr($matches[1]);
            },
            $s
        );
    }
}
