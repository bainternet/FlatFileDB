FlatFileDB
==========

FlatFileDB is a simple key value file based database wrapper.

###Usage
```php
<?php 
//include the class
include_once('FlatFileDB.php');
//instanciate the class
$db = new FlatFileDB(array(
    'db_file' => dirname(__FILE__).'/data/test.db',
   	'db'      => 'test',
   	'cache'   =>  true
));

//set value
$db->set('name','Ohad');

//get value
echo $db->get('name');

//update value
$db->update('name','Ohad Raz');
//or $db->set('key','new value');

//get value
echo $db->get('name');       

//get all keys
print_r($db->get_keys());

//remove value
$db->delete('key');
```

###Download

You can download this project in either [zip][1] or [tar][2] formats

You can also clone the project with Git by running:

    $ git clone git@github.com:bainternet/FlatFileDB.git

###License
licensed: GPL http://www.gnu.org/licenses/gpl.html

  [1]: https://github.com/bainternet/FlatFileDB/zipball/master
  [2]: https://github.com/bainternet/FlatFileDB/tarball/master
  
[![githalytics.com alpha](https://cruel-carlota.pagodabox.com/91b4c4784641675662d0b82a94dace2a "githalytics.com")](http://githalytics.com/bainternet/FlatFileDB)
