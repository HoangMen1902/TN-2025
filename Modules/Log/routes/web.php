<?php

use Illuminate\Support\Facades\Route;
use Modules\Log\Http\Controllers\LogController;

use Illuminate\Support\Facades\Response;

Route::get('/log-stream', function () {
    $logFile = storage_path('logs/laravel.log');

    return Response::stream(function () use ($logFile) {
        $lastSize = 0;

        while (true) {
            clearstatcache();
            $size = filesize($logFile);

            if ($size > $lastSize) {
                $file = fopen($logFile, 'r');
                fseek($file, $lastSize);
                while (($line = fgets($file)) !== false) {
                    echo "data: " . trim($line) . "\n\n";
                    ob_flush();
                    flush();
                }
                fclose($file);
                $lastSize = $size;
            }

            usleep(500000); 
        }
    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
    ]);
});



Route::get('/logs', [LogController::class, 'index']);