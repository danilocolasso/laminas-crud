<?php

namespace TaskTest\Model;

use PHPUnit\Framework\TestCase;
use Task\Model\Task;

class TaskTest extends TestCase
{
    public function testInitialTaskValuesAreNull(): void
    {
        $task = new Task();

        $this->assertNull($task->id, '"id" should be null by default');
        $this->assertNull($task->title, '"title" should be null by default');
        $this->assertNull($task->description, '"description" should be null by default');
    }

    public function testExchangeArraySetsPropertiesCorrectly(): void
    {
        $task = new Task();
        $data  = [
            'id'     => 123,
            'title'  => 'some title',
            'description' => 'some description',
        ];

        $task->exchangeArray($data);

        $this->assertSame(
            $data['description'],
            $task->description,
            '"description" was not set correctly'
        );

        $this->assertSame(
            $data['id'],
            $task->id,
            '"id" was not set correctly'
        );

        $this->assertSame(
            $data['title'],
            $task->title,
            '"title" was not set correctly'
        );
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent(): void
    {
        $task = new Task();

        $task->exchangeArray([
            'description' => 'some $this->description',
            'id'     => 123,
            'title'  => 'some title',
        ]);
        $task->exchangeArray([]);

        $this->assertNull($task->id, '"id" should default to null');
        $this->assertNull($task->title, '"title" should default to null');
        $this->assertNull($task->description, '"description" should default to null');
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues(): void
    {
        $task = new Task();
        $data  = [
            'description' => 'some description',
            'id'     => 123,
            'title'  => 'some title'
        ];

        $task->exchangeArray($data);
        $copyArray = $task->getArrayCopy();

        $this->assertSame($data['id'], $copyArray['id'], '"id" was not set correctly');
        $this->assertSame($data['title'], $copyArray['title'], '"title" was not set correctly');
        $this->assertSame($data['description'], $copyArray['description'], '"description" was not set correctly');
    }

    public function testInputFiltersAreSetCorrectly(): void
    {
        $task = new Task();

        $inputFilter = $task->getInputFilter();

        $this->assertSame(3, $inputFilter->count());
        $this->assertTrue($inputFilter->has('id'));
        $this->assertTrue($inputFilter->has('title'));
        $this->assertTrue($inputFilter->has('description'));
    }
}