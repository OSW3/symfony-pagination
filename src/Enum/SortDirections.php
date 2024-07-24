<?php
namespace OSW3\Pagination\Enum;

use OSW3\Pagination\Trait\EnumTrait;

enum SortDirections: string 
{
    use EnumTrait;

    case ASC  = 'asc';
    case DESC = 'desc';
}