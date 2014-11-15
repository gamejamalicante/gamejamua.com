How to deploy
=============

First, you must ask for permissions to the server administrator (@albertofem). Then follow the next steps:

* Ensure all the changes you want to deploy are properly merged to master
* Install capistrano 2.15.X (version 3.X won't work)
* From the root directory execute the following command:

    `cap prod deploy`
    
You can change the `prod` to `stag` if you want to deploy to the staging environment.

* Ensure deploy was done correctly by looking at the command output to se everything is fine.