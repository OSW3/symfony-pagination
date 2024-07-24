<?php
namespace OSW3\Pagination\Enum;

use OSW3\Pagination\Trait\EnumTrait;

enum DisplayStates: string 
{
    use EnumTrait;

    case SHOW = 'show';
    case HIDE = 'hide';
}