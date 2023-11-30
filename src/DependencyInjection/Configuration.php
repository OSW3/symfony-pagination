<?php 
namespace OSW3\SymfonySupport\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
	/**
	 * define the name of the configuration tree.
	 * > /config/packages/symfony_support.yaml
	 *
	 * @var string
	 */
	public const NAME = "symfony_support";

	/**
	 * Define the translation domain
	 *
	 * @var string
	 */
	public const DOMAIN = 'symfony_support';
	
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