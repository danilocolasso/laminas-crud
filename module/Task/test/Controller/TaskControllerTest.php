<?php

declare(strict_types=1);

namespace TaskTest\Controller;

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
    }

    public function testIndexActionShouldReturnOk(): void
    {
        $this->dispatch('/task', 'GET');

        $this->assertResponseStatusCode(200);

        $this->assertModuleName('task');
        $this->assertControllerName(TaskController::class);
        $this->assertControllerClass('TaskController');
        $this->assertMatchedRouteName('task');
    }

    public function testIndexActionShouldRenderWithinLayout(): void
    {
        $this->dispatch('/task', 'GET');
        $this->assertQuery('body h1');
    }

    public function testNotFoundRouteShouldRender(): void
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
