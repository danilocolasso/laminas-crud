<?php

namespace Task\Form;

use Laminas\Form\Element\Checkbox;
use Laminas\Form\Form;
use Task\Enum\StatusEnum;

class TaskForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('task');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'status',
            'type' => Checkbox::class,
            'options' => [
                'label' => 'Completed',
                'use_hidden_element' => true,
                'checked_value' => StatusEnum::COMPLETED->value,
                'unchecked_value' => StatusEnum::PENDING->value,
            ],
        ]);

        $this->add([
            'name' => 'title',
            'type' => 'text',
            'options' => [
                'label' => 'Title',
            ],
        ]);

        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'options' => [
                'label' => 'Description',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Save',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}