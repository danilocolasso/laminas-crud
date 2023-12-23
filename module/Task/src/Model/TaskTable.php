<?php

namespace Task\Model;

use Laminas\Db\ResultSet\ResultSetInterface;
use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class TaskTable
{
    public function __construct(
        private readonly TableGatewayInterface $tableGateway
    ) {}

    public function fetchAll(): ResultSetInterface
    {
        return $this->tableGateway->select();
    }

    public function get(int $id): Task|null
    {
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();

        if (!$row) {
            throw new RuntimeException(sprintf('Could not find row with identifier %d', $id));
        }

        return $row;
    }

    public function save(Task $task): void
    {
        $data = [
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status->value,
            'created_at' => $task->updatedAt->format('Y-m-d H:i:s'),
            'updated_at' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];

        $id = $task->id;

        if (!$id) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->get($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf('Cannot update task with identifier %d; does not exist', $id));
        }

        unset($data['created_at']);
        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function delete(int $id): void
    {
        $this->tableGateway->delete(['id' => $id]);
    }
}