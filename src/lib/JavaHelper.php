<?php

namespace Paxal\Airplay;

class JavaHelper
{
    /**
     * Check if java binary is installed
     *
     * @return bool
     */
    public static function hasJava()
    {
        exec('which java &> /dev/null', $output, $exitCode);
        return $exitCode === 0;
    }

    /**
     * Run a java program
     *
     * @param $args
     */
    public static function runJar($jar, $args)
    {
        static::installPcntlCatch();

        $tmpjar = tempnam(sys_get_temp_dir(), 'airplay.jar');
        $bSuccess = copy($jar, $tmpjar);
        if (!$bSuccess) {
            throw new \RuntimeException('Unable to copy airplay.jar to temp directory');
        }

        passthru(
            'java -jar '.
            escapeshellarg($tmpjar).
            ' '.
            join(
                ' ',
                array_map('escapeshellarg', $args)
            )
        );

        file_exists($tmpjar) && unlink($tmpjar);

        static::installPcntlCatch(false);
    }

    protected static function installPcntlCatch($catch_syscalls = true)
    {
        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGINT, function() { }, !$catch_syscalls);
        }
    }
}
