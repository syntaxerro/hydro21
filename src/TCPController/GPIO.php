<?php

namespace App\TCPController;

use App\Logger;
use App\TCPController\Mock\ValveRegister;
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

    public static $isDevModeEnabled = false;

    public static function run(LoopInterface $loop, $cmd, callable $onExit = null)
    {
        if(self::$isDevModeEnabled) {
            self::runDev($loop, $cmd, $onExit);
        } else {
            self::runProd($loop, $cmd, $onExit);
        }
    }

    public static function runProd(LoopInterface $loop, $cmd, callable $onExit = null)
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

    public static function runDev(LoopInterface $loop, $cmd, callable $onExit = null)
    {
        self::$output = '';

        $pid = 'PROC: '.self::$processId;
        self::$processId++;

        Logger::log($pid, 'RUN: '.$cmd);

        $loop->addTimer(strpos($cmd, 'pump') === null ? 1 : 5, function () use($cmd, $pid, $onExit) {
            if(preg_match('/cat\ \/sys\/class\/gpio\/gpio(\d+)/', $cmd, $matches)) {
                self::$output = ValveRegister::getByGPIONumber($matches[1]);
            }

            if(preg_match('/echo\ (\d)\ \>\ \/sys\/class\/gpio\/gpio(\d+)/', $cmd, $matches)) {
                ValveRegister::setByGPIONumber($matches[2], (bool)$matches[1]);
            }

            Logger::log($pid, 'Exit code: 0');
            if($onExit) {
                $onExit(0, self::$output);
            }
        });
    }
}