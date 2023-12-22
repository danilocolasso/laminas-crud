<?php

namespace TaskTest\Model;

use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\TableGateway\TableGatewayInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Task\Enum\StatusEnum;
use Task\Model\Task;
use Task\Model\TaskTable;

class TaskTableTest extends TestCase
{
    use ProphecyTrait;

    protected function setUp() : void
    {
        $this->tableGateway = $this->prophesize(TableGatewayInterface::class);
        $this->taskTable = new TaskTable($this->tableGateway->reveal());
    }

    public function testFetchAllShoulReturnAll(): void
    {
        $resultSet = $this->prophesize(ResultSetInterface::class)->reveal();
        $this->tableGateway->select()->willReturn($resultSet);

        $this->assertSame($resultSet, $this->taskTable->fetchAll());
    }

    public function testDeleteTaskTableByIdShouldDelete()
    {
        $this->tableGateway->delete(['id' => 123])->shouldBeCalled();
        $this->taskTable->delete(123);
    }

    public function testSaveTaskTableShouldInsert()
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $taskData = [
            'title'  => 'Title foo',
            'description' => 'Description bar',
            'status' => StatusEnum::PENDING->value,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $task = new Task();
        $task->exchangeArray($taskData);

        $this->tableGateway->insert($taskData)->shouldBeCalled();
        $this->taskTable->save($task);
    }

    public function testUpdateTaskTableShouldUpdate()
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $taskData = [
            'id' => 123,
            'title'  => 'Title foo',
            'description' => 'Description bar',
            'status' => StatusEnum::PENDING->value,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $task = new Task();
        $task->exchangeArray($taskData);

        $resultSet = $this->prophesize(ResultSetInterface::class);
        $resultSet->current()->willReturn($task);

        $this->tableGateway
            ->select(['id' => $taskData['id']])
            ->willReturn($resultSet->reveal());

        $this->tableGateway
            ->update(
                array_filter($taskData, function ($key) {
                    return in_array($key, ['title', 'description', 'status', 'created_at', 'updated_at']);
                }, ARRAY_FILTER_USE_KEY),
                ['id' => $taskData['id']]
            )->shouldBeCalled();

        $this->taskTable->save($task);
    }

    public function testNotFoundTaskTableShouldThrowException()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class);
        $resultSet->current()->willReturn(null);

        $this->tableGateway
            ->select(['id' => 123])
            ->willReturn($resultSet->reveal());

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Could not find row with identifier 123');
        $this->taskTable->get(123);
    }
}