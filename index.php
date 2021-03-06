<?php

// If we're running from phar load the phar autoload file.

$pharPath = \Phar::running(true);
if ($pharPath) {
    $autoloaderPath = "$pharPath/vendor/autoload.php";
} else {
    if (file_exists(__DIR__.'/vendor/autoload.php')) {
        $autoloaderPath = __DIR__.'/vendor/autoload.php';
    } elseif (file_exists(__DIR__.'/../../autoload.php')) {
        $autoloaderPath = __DIR__ . '/../../autoload.php';
    } else {
        die("Could not find autoloader. Run 'composer install'.");
    }
}
$classLoader = require $autoloaderPath;

// Customization variables
$appName = 'Roger';
//$appVersion = trim(file_get_contents(__DIR__ . '/VERSION'));
$appVersion = '1.3.2';
$commandClasses = [
    \Roger\Commands\CleverCloudCommands::class,
    \Roger\Commands\GithubCommands::class,
    \Roger\Commands\WPCommands::class,
    \Roger\Commands\LaravelCommands::class,
];

$selfUpdateRepository = 'matiere-noire/roger';
$configurationFilename = "{$_SERVER['HOME']}/.roger/robo.yml";

// Define our Runner, and pass it the command classes we provide.
$runner = new \Robo\Runner($commandClasses);
$runner
    ->setSelfUpdateRepository($selfUpdateRepository)
    ->setConfigurationFilename($configurationFilename)
    ->setClassLoader($classLoader);

// Execute the command and return the result.
$output = new \Symfony\Component\Console\Output\ConsoleOutput();
$statusCode = $runner->execute($argv, $appName, $appVersion, $output);
exit($statusCode);