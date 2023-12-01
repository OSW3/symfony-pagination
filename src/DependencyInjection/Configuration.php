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


        $rootNode->children()
			
			/**
			 * Item per page
			 * --
			 * 
			 * @var integer
			 * @default 10
			 */
			->integerNode('per_page')->defaultValue(10)->end()
				
			/**
			 * Range
			 * --
			 * 
			 * @var integer
			 * @default 10
			 */
			->integerNode('range')->defaultValue(9)->min(0)->end()
			
			/**
			 * Default direction for "OrderBy"
			 * --
			 * 
			 * @var enum ASC | DESC
			 * @default ASC
			 */
            ->enumNode('direction')->values(["ASC", "DESC"])->defaultValue("ASC")->end()
			
			/**
			 * No result strategy
			 * --
			 * Show pagination block if no results ?
			 * 
			 * @var enum show | hide
			 *  @default hide
			 */
            ->enumNode('empty')->values(["show", "hide"])->defaultValue("hide")->end()

        ->end();
		
		return $builder;
	}
}