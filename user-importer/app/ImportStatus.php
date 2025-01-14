<?php

namespace App;

enum ImportStatus: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Failed = 'failed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
