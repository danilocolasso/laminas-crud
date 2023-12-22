<?php

namespace Task\Model;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\StringLength;
use Task\Enum\StatusEnum;

class Task implements InputFilterAwareInterface
{
    public ?int $id;
    public string $title;
    public ?string $description;
    public StatusEnum $status;
    public \DateTime $createdAt;
    public \DateTime $updatedAt;

    private ?InputFilter $inputFilter = null;

    public function exchangeArray(array $data): void
    {
        $now = new \DateTime();

        $this->id = $data['id'] ?? null;
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->status = isset($data['status']) ? StatusEnum::from($data['status']) : StatusEnum::PENDING;
        $this->createdAt = isset($data['created_at']) ? new \DateTime($data['created_at']) : $now;
        $this->updatedAt = isset($data['updated_at']) ? new \DateTime($data['updated_at']) : $now;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'description' => $this->description,
            'status' => $this->status->value,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter): void
    {
        throw new \DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter(): InputFilter
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'description',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
        ]);

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }
}