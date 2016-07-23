GameJam Alicante
========================

**This version of this site is no longer online, so this repository is no longer mantained**

This is the source code for the of GameJam Alicante, a site dedicated to hold GameJam local contests.

License
--------

All code is under license `GPLv3` and `Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License`. For more information, see file `LICENSE`.

Framework
---------

This projects uses the PHP framework Symfony. All relevant documentation can be found in the official documentation:

* http://symfony.com/doc/current/index.html

Doctrine is used as a ORM. All the documentation can be found in the official documentation as well:

* http://doctrine-orm.readthedocs.org/en/latest/

Installation
------------

In order to begin development, it's necessary to install the site in your local machine. PHP >=5.5 is required as hard dependency. The rest of dependencies are the defined in the `composer.json` file. But before installing all the dependencies, a `parameters.yml` file should be created in the `app/config/` directory. A model for this file is to be found at `app/config/parameters.yml.dist`.

After that, you can install the dependencies doing:

`composer install`

You will also need to create the database schema:

`php app/console doctrine:schema:update --force`

Bundle structure
----------------

There are currently 4 bundles used in this site:

* **ChallengeBundle**: this contains the challenges feature, which consist on an REST API and some views to interact with the challenges in the main site. This bundle requires the Redis server installed in the machine. Also, the REST API may be accessed by using the `rest_dev` and `rest_prod` environments which actually loads the routes for the REST API.

* **CompoBundle**: This is the main Bundle where most of the site sections are. Summary of controllers:

    * _ChallengeController_: Shows challenges related to some compos (solidaria)
    * _CompoController_: main compo controller, which shows the shoutbox, general info, etc.
    * _ContributorController_: shows contributors information
    * _FrontendController_: main website section, which also contains a shoutbox. It constains some partial actions to be used as subrequests from other views
    * _GameController_: shows games from all users of the site
    * _JuryController_: show judges from current compo
    * _NotificationController_: shows notifications / news
    * _PaymentController_: all payment stuff is to be found at this controller. This is rather complex and it uses the JMSPaymentBundle: http://jmsyst.com/bundles/JMSPaymentCoreBundle
    * _ScoreboardController_: shows results for each compo
    * _TeamController_: team management (during compo)

* **UserBundle**: all user related stuff, profile views, control panel, payment, etc. This is based on FOSUserBundle: https://github.com/FriendsOfSymfony/FOSUserBundle
 
* **GameBundle**: all game related stuff, game view, game control panel, ultra coins, etc.
