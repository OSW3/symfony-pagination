<?php 
namespace OSW3\SymfonyPagination\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
	/**
	 * define the name of the configuration tree.
	 * > /config/packages/symfony_pagination.yaml
	 *
	 * @var string
	 */
	public const NAME = "symfony_pagination";

	/**
	 * Define the translation domain
	 *
	 * @var string
	 */
	public const DOMAIN = 'symfony_pagination';
	
	/**
	 * Update and return the Configuration Builder
	 *
	 * @return TreeBuilder
	 */
	public function getConfigTreeBuilder(): TreeBuilder
	{
		$builder = new TreeBuilder( self::NAME );
		$rootNode = $builder->getRootNode();
		
		return $builder;
	}
}