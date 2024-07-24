<?php 
namespace OSW3\Pagination;

use OSW3\Pagination\Utils\ConfigurationYaml;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PaginationBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        ConfigurationYaml::write($container->getParameter('kernel.project_dir'));
    }
}