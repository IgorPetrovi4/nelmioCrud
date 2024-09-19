<?php
declare(strict_types=1);

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Deserialize
{
    // Дополнительные свойства можно добавить при необходимости
}
