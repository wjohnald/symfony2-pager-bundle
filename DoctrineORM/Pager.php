<?php

namespace PunkAve\PagerBundle\DoctrineORM;

use Doctrine\ORM\QueryBuilder as QueryBuilder;
use Symfony\Component\HttpFoundation\Request as Request;
use PunkAve\PagerBundle\Interfaces\Pager as PagerInterface;

class Pager implements PagerInterface {

	/**
	 * 
	 *
	 * @var int
	 */
	protected $numResults = null;

	/**
	 * 
	 *
	 * @var array
	 */
	protected $results = null;

	/**
	 * 
	 * @var int
	 */
	protected $maxPerPage = 20;

	/**
	 * 
	 *
	 * @var int
	 */
	protected $pageNumber = 1;

	/**
	 * Sets the default router for route generation
	 */
	public function setRouter($router)
	{
		$this->router = $router;
	}

	/**
	 *
	 * Sets the query to paginate
	 *
	 * @param \Doctrine\ORM\QueryBuilder
	 */
	public function setQueryBuilder(QueryBuilder $queryBuilder)
	{
		$this->queryBuilder = $queryBuilder;
	}

	/**
	 * 
	 * Takes a route name and a set of route parameters
	 * and uses this as a basis for generating pagination
	 * links
	 *
	 * @param string
	 * @param array
	 */
	public function setRoute($routeName, $routeParams = array())
	{
		$this->routeName = $routeName;
		$this->routeParams = $routeParams;
	}

	/**
	 * 
	 * Set the maximum number of results to display per page
	 *
	 * @param int
	 */
	public function setMaxPerPage($maxPerPage = 20)
	{
		$this->maxPerPage = $maxPerPage;
	}

	public function setCurrentPage($pageNumber = 1)
	{
		$this->pageNumber = $pageNumber;
	}

	public function getCurrentPage()
	{
		return $this->pageNumber;
	}

	public function getNumResults()
	{
		if (is_null($this->numResults)) {
			$this->computeResults();
		}

		return $this->numResults;
	}

	public function getResults()
	{
		if (is_null($this->results))
		{
			$this->computeResults();
		}
		
		return $this->results;
	}

	public function getMaxPages()
	{
		$numResults = $this->getNumResults();

		return ceil($numResults / $this->maxPerPage);
	}

	public function getPageLink($pageNumber = 1)
	{
		$routeParams = $this->routeParams;
		$routeParams['page'] = $pageNumber;

		return $this->router->generate($this->routeName, $routeParams);
	}

	public function getFirstPageLink()
	{
		return $this->getPageLink(1);
	}

	public function getPreviousPageLink()
	{
		$currentPage = $this->getCurrentPage();

		if ($currentPage > 1)
		{
			return $this->getPageLink($currentPage - 1);
		}

		return null;
	}

	public function getLastPageLink()
	{
		$lastPage = $this->getMaxPages(); // 0-indexed

		return $this->getPageLink($lastPage);
	}

	public function getNextPageLink()
	{
		$currentPage = $this->getCurrentPage();

		if ($currentPage < $this->getMaxPages())
		{
			return $this->getPageLink($currentPage + 1);
		}

		return null;
	}

	/**
	 *
	 * Returns an array of links for pagination
	 *
	 * @return array
	 */
	public function getPageLinks()
	{
		// get first page link
		// get previous page link

		// get 2 previous page numbers
		// get current page
		// get 2 next page numbers

		// get next page link
		// get last page link

		$links = array();

		$links['first'] = array(
			'href' => $this->getFirstPageLink(),
			'class' => ''
		);
		$links['previous'] = array(
			'href' => $this->getPreviousPageLink(),
			'class' => ''
		);

		$adjacentLinks = array();
		foreach ($this->getAdjacentPageNumbers() as $pageNumber)
		{
			$adjacentLinks["$pageNumber"] = array(
				'href' => $this->getPageLink($pageNumber),
				'class' => ''
			);

			if ($pageNumber == $this->getCurrentPage())
			{
				$adjacentLinks["$pageNumber"]['class'] = 'active';
			}
		}
		$links['adjacent'] = $adjacentLinks;

		$links['next'] = array(
			'href' => $this->getNextPageLink(),
			'class' => ''
		);
		$links['last'] = array(
			'href' => $this->getLastPageLink(),
			'class' => ''
		);

		return $links;
	}

	/**
	 * 
	 * Return an array of two previous and two next page numbers
	 *
	 * @return array
	 */
	public function getAdjacentPageNumbers()
	{
		$pageNumbers = array();

		$n = 1;
		$i = $this->getCurrentPage() - 2;

		$diff = $this->getMaxPages() - $this->getCurrentPage(); // +1 accounts for 0-indexing
		if ($diff < 2)
		{
			$i -= 2 - $diff;
		}


		while (($n <= 5) && ($i <= $this->getMaxPages()))
		{
			if ($i >= 1)
			{
				$pageNumbers[] = $i;
				$n++;
			}

			$i++;
		}
		
		return $pageNumbers;
	}

	/**
	 * This method takes a Symfony\Component\HttpFoundation\Request. It will
	 * set the current route for the pager as well as the current page.
	 */
	public function bindRequest(Request $request)
	{
		$this->setCurrentPage(($request->query->has('page'))? $request->query->get('page') : 1);
		$this->setRoute($request->get('_route'), $request->query->all());
	}

	/**
	 * This method will perform the query and cache the results
	 */
	protected function computeResults()
	{
		$pageNumber = $this->getCurrentPage();


		$paginatedQb = clone $this->queryBuilder;
		$paginatedQb->setMaxResults($this->maxPerPage);
		$paginatedQb->setFirstResult(($pageNumber - 1) * $this->maxPerPage); // -1 accounts for 1-indexing
		$this->results = $paginatedQb->getQuery()->getResult();

		// Efficiently compute the count without fetching everything
		$countQb = clone $this->queryBuilder;
		$countQb->select('COUNT(' . $countQb->getRootAlias() . '.id)');
		$this->numResults = $countQb->getQuery()->getSingleScalarResult();
	}
}
