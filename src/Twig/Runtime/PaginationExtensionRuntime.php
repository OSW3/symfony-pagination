<?php

namespace OSW3\SymfonyPagination\Twig\Runtime;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\RuntimeExtensionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use OSW3\SymfonyPagination\DependencyInjection\Configuration;
use OSW3\SymfonyPagination\Service\PaginationService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaginationExtensionRuntime implements RuntimeExtensionInterface
{
    /**
     * Bundle configuration
     *
     * @var array
     */
    private array $configuration = [];

    /**
     * Current Request
     */
    private Request $request;

    private PaginationService $service;

    public function __construct(
        #[Autowire(service: 'service_container')] private ContainerInterface $container,
        private Environment $environment,
        private RequestStack $requestStack,
        private UrlGeneratorInterface $router,
    ){
        $this->request = $requestStack->getCurrentRequest();
        $this->configuration = $container->getParameter(Configuration::NAME);
    }

    public function pagination(array $options=[])
    {
        $options = array_merge([
            'paginationService'   => null,
            'route'               => null,
            'absolute'            => true,
            'label_first'         => "First",
            'label_previous'      => "&laquo;",
            'label_next'          => "&raquo;",
            'label_last'          => "Last",
            'aria_label_first'    => "First",
            'aria_label_previous' => "Previous",
            'aria_label_next'     => "Next",
            'aria_label_last'     => "Last",
        ], $options);

        $this->service = $options['paginationService'];
        $route         = $options['route'];
        $absolute      = $options['absolute'];

        //   $total     = $service->getTotal();
          // $range     = $service->getRange();
        $pages       = $this->service?->getPages();
        $offset      = $this->service?->getOffset();
        $current     = $this->service?->getCurrent();
        $prev        = $this->service?->getPrev();
        $next        = $this->service?->getNext();
        $last        = $this->service?->getLast();
        $sorter      = $this->service?->getSorter();

        $url_first   = $this->router->generate($route, array_merge($this->request->query->all(), ['page' => 1], $sorter), $absolute);
        $url_prev    = $this->router->generate($route, array_merge($this->request->query->all(), ['page' => $prev], $sorter), $absolute);
        $url_next    = $this->router->generate($route, array_merge($this->request->query->all(), ['page' => $next], $sorter), $absolute);
        $url_last    = $this->router->generate($route, array_merge($this->request->query->all(), ['page' => $last], $sorter), $absolute);
        $url_page    = array_merge($this->request->query->all(), $sorter);

        $range       = $this->configuration['range'];
        $range_start = 1;
        $range_end   = $last;

        if ($range > 0)
        {
            $range_split = (int) floor($range / 2);
    
            $range_start = $current - $range_split;
            $range_start = $range_start <= 1 ? 1 : $range_start;
            $range_start = $range_start > $last - $range ? $last - $range + 1 : $range_start;
    
            $range_end   = $current + $range_split;
            $range_end   = $range_end >= $last ? $last : $range_end;
            $range_end   = $range_end < $range ? $range : $range_end;
        }

        $range       = range($range_start, $range_end);

        $options = array_merge([
            // 'total'     => $total,
            // 'range'     => $range,
            'pages'     => $pages,
            'offset'    => $offset,
            'current'   => $current,
            'prev'      => $prev,
            'next'      => $next,
            'last'      => $last,
            'sorter'    => $sorter,
            'url_first' => $url_first,
            'url_prev'  => $url_prev,
            'url_next'  => $url_next,
            'url_last'  => $url_last,
            'url_page'  => $url_page,

            'range'     => $range
        ], $options);

        if ($pages <= 0 && $this->configuration['empty'] === 'hide')
        {
            return '';
        }

        return $this->environment->render('@Pagination/pagination.html.twig', $options);
    }

    public function pagination_total(): int
    {
        return  $this->service->getTotal();
    }
    public function pagination_pages(): int
    {
        return  $this->service->getPages();
    }
    public function pagination_page(): int
    {
        return  $this->service->getCurrent();
    }
    public function pagination_per_page(): int
    {
        return  $this->service->getPerPage();
    }
}
