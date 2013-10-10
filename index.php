<?php
/**
 * Demo usage
 */
//include the class
include_once('FlatFileDB.php');
//instanciate the class
$db = new FlatFileDB(array(
	'db_file' => dirname(__FILE__).'/data/test.db',
   	'db'      => 'test',
   	'cache'   =>  true
));
$db->set('name','ohad');
$db->set('skills',array('php','kong-fu'));
?>
<html>
<head>
	<title>FlatFlieDB</title>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js?lang=php&skin=sunburst"></script>
	<link rel="stylesheet" type="text/css" href="http://getbootstrap.com/examples/jumbotron-narrow/jumbotron-narrow.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="jumbotron">
			    <div class="container">
			        <h1>FlatFileBD</h1>
			        <p></p>
			        <p>
			        	<a href="https://github.com/bainternet/FlatFileDB/archive/master.zip" class="btn btn-primary btn-large">Download</a>
			        	<a href="https://github.com/bainternet/FlatFileDB/issues" class="btn btn-primary btn-large">Report an isue</a>
			        </p>
			    </div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h2>Usage:</h2>
				<div class="panel panel-info">
				      <div class="panel-heading">
				            <h3 class="panel-title">Install</h3>
				      </div>
				      <div class="panel-body">
				            <pre class="prettyprint"><code class="language-php">
//include the class
include_once('FlatFileDB.php');
//instanciate the class
$db = new FlatFileDB(array(
	'db_file' => dirname(__FILE__).'/data/test.db',
   	'db'      => 'test',
   	'cache'   =>  true
));
						</code></pre>
				      </div>
				</div>
				<div class="panel panel-info">
				      <div class="panel-heading">
				            <h3 class="panel-title">Set A Value</h3>
				      </div>
				      <div class="panel-body">
				            <pre class="prettyprint"><code class="language-php">
//set a string value
$db->set('name','Ohad');
//set an array value
$db->set('skills',array('php','kong-fu'));
				            </code></pre>
				      </div>
				</div>
				<div class="panel panel-info">
				      <div class="panel-heading">
				            <h3 class="panel-title">Get A Value</h3>
				      </div>
				      <div class="panel-body">
				            <pre class="prettyprint"><code class="language-php">
//get value
$db->get('name');
				            </code></pre>
				            Which returns
				            <pre><?php echo $db->get('name'); ?></pre>
				      </div>
				</div>
				<div class="panel panel-info">
				      <div class="panel-heading">
				            <h3 class="panel-title">update A Value</h3>
				      </div>
				      <div class="panel-body"><?php $db->update('name','Ohad Raz');?>
				            <pre class="prettyprint"><code class="language-php">
$db->update('name','Ohad Raz');
//or $db->set('key','new value');
				            </code></pre>
				      </div>
				</div>
				<div class="panel panel-info">
				      <div class="panel-heading">
				            <h3 class="panel-title">Delete A Value</h3>
				      </div>
				      <div class="panel-body">
				            <pre class="prettyprint"><code class="language-php">
$db->delete('name');
				            </code></pre>
				      </div>
				</div>
				<div class="panel panel-info">
				      <div class="panel-heading">
				            <h3 class="panel-title">Get all keys</h3>
				      </div>
				      <div class="panel-body">
				            <pre class="prettyprint"><code class="language-php">
$keys = $db->get_keys();
				            </code></pre>
				             Which returns
				            <pre><?php print_r($db->get_keys()); ?></pre>
				      </div>
				</div>
			</div>
		</div>
	</div>
</body>	
</html>