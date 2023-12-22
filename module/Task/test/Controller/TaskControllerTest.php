<?php

declare(strict_types=1);

namespace TaskTest\Controller;

use Laminas\ServiceManager\ServiceManager;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Task\Controller\TaskController;
use Task\Model\TaskTable;

class TaskControllerTest extends AbstractHttpControllerTestCase
{
    use ProphecyTrait;

    protected TaskTable|ObjectProphecy $taskTable;

    public function setUp(): void
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

//        $this->configureServiceManager($this->getApplicationServiceLocator());
    }

    public function testIndexActionCanBeAccessed(): void
    {
//        $this->taskTable->fetchAll()->willReturn([]);

        $this->dispatch('/task', 'GET');

        $this->assertResponseStatusCode(200);

        $this->assertModuleName('task');
        $this->assertControllerName(TaskController::class);
        $this->assertControllerClass('TaskController');
        $this->assertMatchedRouteName('task');
    }

    public function testIndexActionViewModelTemplateRenderedWithinLayout(): void
    {
        $this->dispatch('/task', 'GET');
        $this->assertQuery('body h1');
    }

    public function testInvalidRouteDoesNotCrash(): void
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }

    protected function configureServiceManager(ServiceManager $services): void
    {
        $services->setAllowOverride(true);

        $services->setService('config', $this->updateConfig($services->get('config')));
        $services->setService(TaskTable::class, $this->mockTaskTable()->reveal());

        $services->setAllowOverride(false);
    }

    protected function updateConfig($config): array
    {
        $config['db'] = [];
        return $config;
    }

    protected function mockTaskTable(): ObjectProphecy
    {
        $this->taskTable = $this->prophesize(TaskTable::class);
        return $this->taskTable;
    }
}
