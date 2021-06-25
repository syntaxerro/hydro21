<?php

namespace App\TCPController;

use App\Logger;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;

class GPIO
{
    const FS_PATH = '/sys/class/gpio/';

    const PATH_EXPORT = self::FS_PATH.'export';
    const PATH_DIRECTION = self::FS_PATH.'gpio%s/direction';
    const PATH_VALUE = self::FS_PATH.'gpio%s/value';

    private static $output = '';

    private static $processId = 1;

    public static function run(LoopInterface $loop, $cmd, callable $onExit = null)
    {
        self::$output = '';

        $pid = 'PROC: '.self::$processId;
        self::$processId++;

        $process = new Process($cmd);
        $process->start($loop);

        $process->stdout->on('data', function($chunk) {
            self::$output .= $chunk;
        });

        $process->stderr->on('error', function (\Exception $e) use($pid) {
            Logger::log($pid, 'ERROR: '.$e->getMessage().PHP_EOL.$e->getTraceAsString());
        });

        $process->on('exit', function($exitCode, $termSignal) use($pid, $onExit) {
            Logger::log($pid, 'Exit code: '.$exitCode);
            if($onExit) {
                $onExit($exitCode, self::$output);
            }
        });

        Logger::log($pid, 'RUN: '.$cmd);
    }
}