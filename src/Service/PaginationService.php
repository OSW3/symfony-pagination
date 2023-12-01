<?php 
namespace OSW3\SymfonyPagination\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use OSW3\SymfonyPagination\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PaginationService
{
    /**
     * Bundle configuration
     *
     * @var array
     */
    private array $configuration = [];

    /**
     * Current Request
     * 
     * @var Request
     */
    private Request $request;

    /**
     * Entity repository
     *
     * @var object
     */
    private $repository;

    private string $method;

    /**
     * Query criteria
     *
     * @var array
     */
    private array $criteria = [];

    /**
     * Default sorter definition
     *
     * @var array
     */
    private array $defaultSorter = [];

    /**
     * Sorter array
     *
     * @var array
     */
    private array $sorter = [];

    /**
     * Total items
     *
     * @var integer
     */
    private int $total = 0;

    /**
     * Current page number
     *
     * @var integer
     */
    private int $current = 1;

    /**
     * Total pages
     *
     * @var integer
     */
    private int $pages = 1;

    /**
     * Items query offset
     *
     * @var integer
     */
    private int $offset = 0;

    /**
     * Previous page number
     *
     * @var integer
     */
    private int $prev = 0;

    /**
     * Next page number
     *
     * @var integer
     */
    private int $next = 0;

    /**
     * Undocumented variable
     *
     * @var integer
     */
    private int $last = 0;

    /**
     * Items per page
     *
     * @var integer
     */
    private int $perPage = 0;

    /**
     * List of paginated results
     *
     * @var array
     */
    private array $results = [];

    public function __construct(
        #[Autowire(service: 'service_container')] private ContainerInterface $container,
        private RequestStack $requestStack,
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->configuration = $container->getParameter(Configuration::NAME);

        $this->setMethod('findBy');
        $this->setPerPage($this->configuration['per_page']);
    }

    public function find(array $sorter=[])
    {
        return $this->findBy([], $sorter);
    }
    public function findBy(array $criteria = [], array $defaultSorter = [])
    {
        $this->criteria = $criteria;
        $this->defaultSorter = $defaultSorter;

        // Set total items of the entity
        $total = $this->count($this->criteria);
        $this->setTotal($total);

        // Set the current page
        $current = $this->getPageParameter();
        $this->setCurrent($current);

        // Init the sorter array
        $this->setSorter();

        // Set items per page
        $this->perPage = $this->perPage > 0 ? $this->perPage : $this->getTotal();
        $perPage = $this->perPage;

        // Count total pages
        $pages = ceil($total / $perPage);
        $this->setPages($pages);

        // Set the Offset
        $offset = ($this->current * $perPage) - $perPage;
        $this->setOffset($offset);

        // Set previous page number
        $prev = $this->current - 1 < 1 ? 1 : $this->current - 1;
        $this->setPrev($prev);

        // Set next page number
        $next = $this->current + 1 > $pages ? $pages : $this->current + 1;
        $this->setNext($next);

        // Set the last page
        $this->setLast($pages);

        // Results
        $results = $this->fetch(
            $this->criteria, 
            $this->sorter, 
            $this->perPage, 
            $this->offset
        );
        
        $this->setResults($results);

        return $results;
    }

    private function count(array $criteria=[]): int
    {
        $results = $this->fetch($criteria);
        $count   = count($results);

        return $count;
    }
    private function fetch(array $criteria=[], array $sorter=[], ?int $perPage=null, ?int $offset=null): array
    {
        $method  = $this->getMethod();
        $results = $this->repository->$method(
            $criteria, 
            $sorter, 
            $perPage, 
            $offset
        );

        return $results;
    }

    public function setRepository($repository): static
    {
        $this->repository = $repository;

        return $this;
    }

    public function setMethod(string $method): static 
    {
        $this->method = $method;

        return $this;
    }
    private function getMethod(): string
    {
        return $this->method;
    }

    private function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }
    public function getTotal(): int
    {
        return $this->total;
    }
    public function total(): int 
    {
        return $this->getTotal();
    }

    private function setCurrent(int $current): self
    {
        $this->current = $current;

        return $this;
    }
    public function getCurrent(): int
    {
        return $this->current;
    }
    public function page(): int
    {
        return $this->getCurrent();
    }

    private function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }
    public function getPages(): int
    {
        return $this->pages;
    }
    public function pages(): int
    {
        return $this->getPages();
    }

    private function setOffset(int $offset): self
    {
        $this->offset = $offset <= 0 ? 0 : $offset;

        return $this;
    }
    public function getOffset(): int
    {
        return $this->offset;
    }
    public function offset(): int
    {
        return $this->getOffset();
    }

    private function setPrev(int $prev): self
    {
        $this->prev = $prev;

        return $this;
    }
    public function getPrev(): int
    {
        return $this->prev;
    }
    public function prev(): int
    {
        return $this->getPrev();
    }

    private function setNext(int $next): self
    {
        $this->next = $next;

        return $this;
    }
    public function getNext(): int
    {
        return $this->next;
    }
    public function next(): int
    {
        return $this->getNext();
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }
    public function getPerPage(): int
    {
        return $this->perPage;
    }
    public function perPage(): int
    {
        return $this->getPerPage();
    }

    private function setSorter(): self
    {
        // Sorter by Request Query
        // --

        $sorter = $this->request->query->all();

        if (isset($sorter['page']))
            unset($sorter['page']);

        $this->sorter = $sorter;


        // Sorter by Default definition
        // --

        if (empty($this->sorter))
        {
            foreach ($this->defaultSorter as $key => $value)
            {
                unset($this->defaultSorter[$key]);

                if ( gettype($key) === 'integer' )
                {
                    $key = $value;
                    $value = $this->configuration['direction'];
                }
                $this->defaultSorter[$key] = $value;
            }

            $this->sorter = $this->defaultSorter;
        }

        return $this;
    }
    public function getSorter(): array
    {
        return $this->sorter;
    }
    public function sorter(): array
    {
        return $this->getSorter();
    }

    private function setResults(array $results): self
    {
        $this->results = $results;

        return $this;
    }
    public function getResults(): array
    {
        return $this->results;
    }
    public function results(): array
    {
        return $this->getResults();
    }

    private function setLast(int $last): self
    {
        $this->last = $last;

        return $this;
    }
    public function getLast(): int
    {
        return $this->last;
    }
    public function last(): int
    {
        return $this->getLast();
    }

    private function getPageParameter(): int
    {
        return $this->request->get('page') ?? 1;
    }
}