<?php

namespace OSW3\SymfonyPagination\Twig\Extension;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use OSW3\SymfonyPagination\Twig\Runtime\PaginationExtensionRuntime;

class PaginationExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pagination', [PaginationExtensionRuntime::class, 'pagination'], ['is_safe' => ['html']]),
        ];
    }
}
