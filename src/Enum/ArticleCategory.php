<?php

namespace App\Enum;

enum ArticleCategory: string
{
    case CHAUSSURES = 'Chaussures';
    case MAILLOTS = 'Maillots';
    case SHORTS = 'Shorts';
    case JOGGING = 'Jogging';
    case CHAUSSETTES = 'Chaussettes';

    public static function getChoices(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_column(self::cases(), 'value')
        );
    }
} 