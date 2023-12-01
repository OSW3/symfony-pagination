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
            new TwigFunction('pagination_total', [PaginationExtensionRuntime::class, 'pagination_total']),
            new TwigFunction('pagination_pages', [PaginationExtensionRuntime::class, 'pagination_pages']),
            new TwigFunction('pagination_page', [PaginationExtensionRuntime::class, 'pagination_page']),
            new TwigFunction('pagination_per_page', [PaginationExtensionRuntime::class, 'pagination_per_page']),
        ];
    }
}
