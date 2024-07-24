<?php

use OSW3\Pagination\Enum\DisplayStates;
use OSW3\Pagination\Enum\SortDirections;

return static function($definition)
{
    $definition->rootNode()->children()

        /**
         * Item per page
         * --
         * 
         * @var integer
         * @default 10
         */
        ->integerNode('per_page')
            ->defaultValue(10)
        ->end()
            
        /**
         * Range
         * --
         * 
         * @var integer
         * @default 10
         */
        ->integerNode('range')
            ->defaultValue(9)
            ->min(0)
        ->end()
        
        /**
         * Default direction for "OrderBy"
         * --
         * 
         * @var enum ASC | DESC
         * @default ASC
         */
        ->enumNode('direction')
            ->values(SortDirections::toArray())
            ->defaultValue(SortDirections::ASC->value)
        ->end()
        
        /**
         * On empty result strategy
         * --
         * Show pagination block if no results ?
         * 
         * @var enum show | hide
         *  @default hide
         */
        ->enumNode('on_empty_result')
            ->values(DisplayStates::toArray())
            ->defaultValue(DisplayStates::HIDE->value)
        ->end()

    ->end();
};