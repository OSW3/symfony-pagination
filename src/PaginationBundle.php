<?php 
namespace OSW3\Pagination;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use OSW3\Pagination\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PaginationBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        (new Configuration)->generateProjectConfig($container->getParameter('kernel.project_dir'));
    }
}