Contributing
============

Branch model
------------

There are currently 2 mainstream branches:

* develop - All work production ready should be here
* master - Version currently in prod


Pull request
------------

All contributed features must be requested using the pull request mechanism. Every contributor must work directly in their own forks and then open a pull request in order to merge changes.

Merging to master
-----------------

If you need to deploy changes, you must first merge develop to master. Follow the next steps:

* git fetch --all
* git checkout develop
* git reset --hard origin/develop
* git checkout master
* git reset --hard origin/master
* git merge --no-ff --no-comit develop
* git commit
* git tag 1.X.X
* git push origin master
* git push origin 1.X.X


Coding standars
---------------

All pull requests should follow the PSR-0 through PSR-4 coding standards. More details here:

* https://github.com/php-fig/fig-standards/tree/master/accepted