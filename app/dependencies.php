<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\PhpRenderer;

return function (ContainerBuilder $containerBuilder) {
    $container = [];

    $container[LoggerInterface::class] = function (ContainerInterface $c) {
        $settings = $c->get('settings');

        $loggerSettings = $settings['logger'];
        $logger = new Logger($loggerSettings['name']);

        $processor = new UidProcessor();
        $logger->pushProcessor($processor);

        $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
        $logger->pushHandler($handler);

        return $logger;
    };

    $container['renderer'] = function (ContainerInterface $c) {
        $settings = $c->get('settings')['renderer'];
        $renderer = new PhpRenderer($settings['template_path']);
        return $renderer;
    };

	$container['taskModel'] = DI\factory('App\Factories\TaskModelFactory');
	$container['incompleteTasksController'] = DI\factory('App\Factories\IncompleteTasksControllerFactory');
	$container['completedTasksController'] = DI\factory('App\Factories\CompletedTasksControllerFactory');

	$container['insertTaskController'] = DI\factory('App\Factories\InsertTaskControllerFactory');
	$container['markTaskCompleteController'] = DI\factory('App\Factories\MarkTaskCompleteControllerFactory');

    $containerBuilder->addDefinitions($container);
};
