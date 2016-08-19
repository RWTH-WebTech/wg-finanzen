<?php
spl_autoload_register(function ($class) {// Anonyme Funktion. Bekannt aus JavaScript.
    $prefix = 'WGFinanzen';
    $base_dir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);

    // Ersetze Präfix durch $base_dir,
    // ersetze namespace Separator durch Ordner Separator,
    // füge .php hinzu
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
