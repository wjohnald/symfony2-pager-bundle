symfony2-doctrine-pager
=======================

Introduction
============

While developing a search page using a very much custom Doctrine2 ORM QueryBuilder, I ran into a snag when trying to display every search result simultaneously. The solution was obviously pagination. Unfortunately, we couldn't use the SonataAdmin pager because there would be too much overhead for such a simple problem. This bundle provides a pager service that simply takes a route (with parameters), and a Doctrine QueryBuilder object. It produces simple pagination links for the entire result set of the query. I have also provided a simple twig template that can be included in any search page so long as the pager has been passed to the view layer.

Requirements
============

* Symfony2
* Doctrine2
* Twig
* Bootstrap (optional)


Usage
=====

1) Simply get the pager service from the DIC.

	$pager = $this->get('punk_ave.doctrine.orm.pager');

2a) Bind the pager to the current request to set the current page and the current route:

    $request = $this->getRequest();
    $pager->bindRequest($request);

2b) Optionally pass the current page to the pager and set the route (With params) on the pager:

	$currentPage = ($request->query->has('page'))? $request->query->get('page') : 0;
    $pager->setCurrentPage($currentPage);
    $pager->setRoute($this->getRequest()->get('_route'), $this->getRequest()->query->all());

3) Pass the QueryBuilder you have setup to the pager:

	$pager->setQueryBuilder($queryBuilder);

4) Pass the pager to the view:

	return $this->render('Bundle:Module:view.html.twig', array(
            'pager' => $pager
        ));

To get the results simply call:

	$pager->getResults();

To get an associative array of page links, simply call:

	$pager->getPageLinks();

