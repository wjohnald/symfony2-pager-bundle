<?php

namespace PunkAve\PagerBundle\Interfaces;

use Doctrine\ORM\QueryBuilder as QueryBuilder;
use Symfony\Component\HttpFoundation\Request as Request;

interface Pager
{
    /**
     * Sets the default router for route generation
     */
    public function setRouter($router);

    /**
     *
     * Sets the query to paginate
     *
     * @param \Doctrine\ORM\QueryBuilder
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder);

    /**
     * 
     * Takes a route name and a set of route parameters
     * and uses this as a basis for generating pagination
     * links
     *
     * @param string
     * @param array
     */
    public function setRoute($routeName, $routeParams = array());

    /**
     * 
     * Set the maximum number of results to display per page
     *
     * @param int
     */
    public function setMaxPerPage($maxPerPage = 20);

    public function setCurrentPage($pageNumber = 1);

    public function getCurrentPage();

    public function getNumResults();

    public function getResults();

    public function getMaxPages();

    public function getPageLink($pageNumber = 1);

    public function getFirstPageLink();

    public function getPreviousPageLink();

    public function getLastPageLink();

    public function getNextPageLink();

    public function getPageLinks();

    /**
     * This method takes a Symfony\Component\HttpFoundation\Request. It will
     * set the current route for the pager as well as the current page.
     */
    public function bindRequest(Request $request);
}