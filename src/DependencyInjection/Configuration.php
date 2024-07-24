<?php 
namespace OSW3\Pagination\DependencyInjection;

use Symfony\Component\Filesystem\Path;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use OSW3\Pagination\DependencyInjection\DefinitionConfigurator;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
	/**
	 * define the name of the configuration tree.
	 * > /config/packages/pagination.yaml
	 *
	 * @var string
	 */
	public const string NAME = "pagination";

	/**
	 * Define the translation domain
	 *
	 * @var string
	 */
	public const string DOMAIN = 'pagination';

	/**
	 * Update and return the Configuration Builder
	 *
	 * @return TreeBuilder
	 */
	public function getConfigTreeBuilder(): TreeBuilder
    {
        $definition = require Path::join(__DIR__, "../../", "config/definition/definition.php");
        $builder    = new TreeBuilder( static::NAME );

        $definition(new DefinitionConfigurator($builder->getRootNode()));

		return $builder;
    }
}