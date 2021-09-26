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
        return new PhpRenderer($settings['template_path']);
    };

	$container['taskModel'] = DI\factory('App\Factories\TaskModelFactory');
	$container['errorLoggerModel'] = DI\factory('App\Factories\ErrorLoggerModelFactory');

	$container['getAllTasksController'] = DI\factory('App\Factories\GetAllTasksControllerFactory');
	$container['editTasksController'] = DI\factory('App\Factories\EditTasksControllerFactory');
	$container['insertTaskController'] = DI\factory('App\Factories\InsertTaskControllerFactory');

	$container['markTasksCompleteController'] = DI\factory('App\Factories\MarkTasksCompleteControllerFactory');
	$container['markTasksIncompleteController'] = DI\factory('App\Factories\MarkTasksIncompleteControllerFactory');
	$container['markTasksArchivedController'] = DI\factory('App\Factories\MarkTasksArchivedControllerFactory');
	$container['markTasksNotArchivedController'] = DI\factory('App\Factories\MarkTasksNotArchivedControllerFactory');
	$container['deleteTasksPermanentlyController'] = DI\factory('App\Factories\DeleteTasksPermanentlyControllerFactory');

    $containerBuilder->addDefinitions($container);
};
