<?php

declare(strict_types=1);

namespace Task;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                Model\TaskTable::class => function($container) {
                    $tableGateway = $container->get(Model\TaskTableGateway::class);
                    return new Model\TaskTable($tableGateway);
                },
                Model\TaskTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Task());
                    return new TableGateway('task', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig(): array
    {
        return [
            'factories' => [
                Controller\TaskController::class => function($container) {
                    return new Controller\TaskController(
                        $container->get(Model\TaskTable::class)
                    );
                },
            ],
        ];
    }
}
