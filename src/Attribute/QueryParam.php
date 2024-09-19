<?php
declare(strict_types=1);

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class QueryParam
{
    // Можно добавить дополнительные свойства, если необходимо
}
