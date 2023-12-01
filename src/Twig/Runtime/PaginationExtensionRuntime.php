<?php

namespace OSW3\SymfonyPagination\Twig\Runtime;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\RuntimeExtensionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use OSW3\SymfonyPagination\DependencyInjection\Configuration;
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

        $service   = $options['paginationService'];
        $route     = $options['route'];
        $absolute  = $options['absolute'];

        // $total     = $service->getTotal();
        // $range     = $service->getRange();
        $pages     = $service?->getPages();
        $offset    = $service?->getOffset();
        $current   = $service?->getCurrent();
        $prev      = $service?->getPrev();
        $next      = $service?->getNext();
        $last      = $service?->getLast();
        $sorter    = $service?->getSorter();
        
        $url_first = $this->router->generate($route, array_merge($this->request->query->all(), ['page' => 1], $sorter), $absolute);
        $url_prev  = $this->router->generate($route, array_merge($this->request->query->all(), ['page' => $prev], $sorter), $absolute);
        $url_next  = $this->router->generate($route, array_merge($this->request->query->all(), ['page' => $next], $sorter), $absolute);
        $url_last  = $this->router->generate($route, array_merge($this->request->query->all(), ['page' => $last], $sorter), $absolute);
        $url_page  = array_merge($this->request->query->all(), $sorter);

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
        ], $options);


        if ($pages <= 0 && $this->configuration['empty'] === 'hide')
        {
            return '';
        }


        return $this->environment->render('@Pagination/pagination.html.twig', $options);
    }
}
