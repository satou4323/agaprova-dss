<?php
/**
 * ErrorHandler - Manejo centralizado de errores
 */

class ErrorHandler {
    public static function register() {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleError($errno, $errstr, $errfile, $errline) {
        $error = [
            'type' => self::getErrorType($errno),
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline
        ];

        self::log($error);

        if (!in_array($errno, [E_NOTICE, E_DEPRECATED, E_STRICT])) {
            self::displayError($error);
        }

        return true;
    }

    public static function handleException($exception) {
        $error = [
            'type' => 'Exception',
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];

        self::log($error);
        self::displayError($error);
    }

    public static function handleShutdown() {
        $error = error_get_last();
        if ($error) {
            self::handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    private static function getErrorType($errno) {
        $types = [
            E_ERROR => 'Error Fatal',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            E_DEPRECATED => 'Deprecated',
            E_STRICT => 'Strict',
            E_RECOVERABLE_ERROR => 'Recoverable Error'
        ];
        return $types[$errno] ?? 'Unknown Error';
    }

    private static function log($error) {
        $logFile = BASE_PATH . '/logs/errors.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] {$error['type']}: {$error['message']} ({$error['file']}:{$error['line']})\n";

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    private static function displayError($error) {
        $isDev = !FORCE_HTTPS;

        if ($isDev) {
            echo "<pre style='background: #f00; color: #fff; padding: 20px; margin: 0;'>";
            echo "ERROR: {$error['type']}\n";
            echo "Message: {$error['message']}\n";
            echo "File: {$error['file']}:{$error['line']}\n";
            if (isset($error['trace'])) {
                echo "\nTrace:\n{$error['trace']}";
            }
            echo "</pre>";
        } else {
            echo "Ha ocurrido un error. Por favor intente más tarde.";
            http_response_code(500);
        }
    }
}
