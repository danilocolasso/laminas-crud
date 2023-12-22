<?php

declare(strict_types=1);

namespace Task\Controller;

use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Task\Form\TaskForm;
use Task\Model\Task;
use Task\Model\TaskTable;

class TaskController extends AbstractActionController
{
    public function __construct(
        private readonly TaskTable $table
    ) {}

    public function indexAction(): ViewModel
    {
        $tasks = $this->table->fetchAll();

        return new ViewModel([
            'tasks' => $tasks,
        ]);
    }

    public function viewAction(): Response|array
    {
        $id = (int) $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('task');
        }

        try {
            $task = $this->table->get($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('task', ['action' => 'index']);
        }

        return [
            'id' => $id,
            'task' => $task,
        ];
    }

    public function addAction(): Response|array
    {
        $form = new TaskForm();

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $task = new Task();
        $form->setInputFilter($task->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $task->exchangeArray($form->getData());
        $this->table->save($task);

        return $this->redirect()->toRoute('task');
    }

    public function editAction(): Response|array
    {
        $id = (int) $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('task', ['action' => 'add']);
        }

        try {
            $task = $this->table->get($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('task', ['action' => 'index']);
        }

        $form = new TaskForm();
        $form->bind($task);

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($task->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        $this->table->save($task);

        return $this->redirect()->toRoute('task', ['action' => 'index']);
    }

    public function deleteAction(): Response|array
    {
        $id = (int) $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('task');
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->delete($id);
            }

            return $this->redirect()->toRoute('task');
        }

        return [
            'id' => $id,
            'task' => $this->table->get($id),
        ];
    }
}
