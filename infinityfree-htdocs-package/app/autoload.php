<?php

function loadAppClass(string $className): void
{
    $namespacePrefix = 'App\\';

    if (strpos($className, $namespacePrefix) !== 0) {
        return;
    }

    $relativeClassName = substr($className, strlen($namespacePrefix));
    $filePath = __DIR__ . DIRECTORY_SEPARATOR
        . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClassName)
        . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    }
}

spl_autoload_register('loadAppClass');
