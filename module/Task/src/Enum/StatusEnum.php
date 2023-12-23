<?php

namespace Task\Enum;

enum StatusEnum: string
{
    case PENDING = 'pending';
    case COMPLETED = 'complete';

    public function toggle(): self
    {
        return match ($this) {
            self::PENDING => self::COMPLETED,
            self::COMPLETED => self::PENDING,
        };
    }
}
