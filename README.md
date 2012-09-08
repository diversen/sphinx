### Sphinx

Sphinx search for CosCMS

You will need to have sphinx installed and run the sphinx daemon
You can check out the sphinx.conf which will work on a Ubuntu install. 
You will just need to change username and password for mysql database. 

### indexes

As you can see in the ini file you will need to specify the indexes to search. 
Then you can search on e.g. articles, or blogs, or both. This indexes need to
be set in the sphinx.conf file as well.  

### Use it with other modules

It should be quite easy to implement the sphinx search with any other modules. 
You should just add a new index for every module, e.g. you have made a forum
module name is forum, and index name should also be forum. Add this to 
sphinx.conf, with a correct index query and it should work just fine. In order
for this to work you will need to specify a way that the forum posts will be
displayed. You do this by adding a static method like this: 

    public static function sphinxHit ($id){} 
    // $id e.g. id of forum post, echo some html

### Cron like this: 

Once a day update the index (or another time frame). Run as root and set
in system wide cronta, e.g. /etc/crontab

20 20 * * * root  indexer --all --rotate --quiet

or once every day: 

20 * * * * root indexer --all --rotate --quiet