The purpose of this bundle is to provide general pagination for a supplied Doctrine2 query.

Suppose you are receiving a Doctrine2 query from an external source or that you are carefully crafting your own query based on any number of parameter inputs:

Assumptions:
* The query might have hundreds or thousands of results
* You don't want to display all of those results on the same page
* The query does not supply default methods of pagination
* There is a route and a set of parameters that led you to the action that created this query

What this Paginator provides:
* You may set an arbitrary Doctrine2 ORM Query object
* You may set a list of routing parameters
* You may supply a custom route
* You may retrieve a set of pagination links
* You may retrieve a total count of objects

Why is this Paginator good?

This Paginator is stateless and decoupled from larger pagination frameworks such as SonataAdmin. It simply requires a DoctrineORM Query object, and a route to build. It does no more and no less than simple pagination. It can be passed to twig, and designers will be happy.