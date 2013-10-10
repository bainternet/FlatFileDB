<?php
/**
* FlatFileDB 
* 
* FlatFileDB is a simple key value file based database wrapper
* 
* @author ohad raz <admin@bainternet.info>
* @version  0.1
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @copyright Ohad raz
* 
* 	//Usage
*  		//instanciate the class
*    	$db = new FlatFileDB(array(
* 	   		'db_file' => dirname(__FILE__).'/data/test.db',
* 	     	'db'      => 'test',
* 	      	'cache'   =>  true
*       ));
*       
*       //set value
*       $db->set('key','value');
*       
*       //get value
*       echo $db->get('key');
*       
*       //update
*       $db->update('key','new value');
*       //or $db->set('key','new value');
*       
*       //remove value
*       $db->delete('key');
*   
*   	//get all keys
*    	print_r($db->get_keys());
*/
class FlatFileDB
{	
	/**
	 * $db 
	 * database name
	 * @var string
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 */
	public $db = '';
	/**
	 * $ext
	 * database extesion
	 * @var string
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 */
	public $ext = 'db';
	/**
	 * $dir 
	 * database directory
	 * @var string
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 */
	public $dir = '';
	/**
	 * $cache 
	 * cache flag
	 * @var boolean
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 */
	public $cache = false;
	/**
	 * $c 
	 * cache container
	 * @var array
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 */
	public $c = array();
	/**
	 * $fp 
	 * file pointer
	 * @var pointer
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 */
	public $fp;
	/**
	 * $db_file 
	 * database file name
	 * @var string
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 */
	public $db_file = '';
	/**
	 * __construct 
	 * class constructor
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @param array $args database arguments
	 */
	function __construct($args=array()){
		$defaults = array(
			'db'      => '',
			'ext'     => 'db',
			'cache'   => false,
			'c'       => array(),
			'fp'      => null,
			'dir'     => '',
			'db_file' => ''
		);
		$options = array_merge($defaults,$args);
		foreach ($options as $key => $value) {
			$this->$key = $value;
		}
		return $this->init();
	}
	/**
	 * init 
	 * setupda datatbase
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @return object instance
	 */
	function init(){
		if($this->db == '' && $this->db_file == '')
			throw $this->_ex('No database file defined!');
		
		if ($this->db_file == '')
			$this->db_file = $this->dir. DIRECTORY_SEPARATOR.$this->db.".".$this->ext;

		if (!file_exists($this->db_file)) {
			$this->create_db();
		}
		return $this;
	}
	/**
	 * _ex 
	 * creates a new exception
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @param  message $m 
	 * @return exception
	 */
	function _ex($m){
		return new Exception($m);		
	}
	/**
	 * open_file 
	 * opens file pointer
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @param  string $file database file name
	 * @param  string $mode mode to open
	 * @return pointer file pointer
	 */
	function open_file($file,$mode){
		$this->fp = @fopen($file, $mode);
		if ($this->fp === false)
			throw $this->_ex('error opening database ' . $this->db);
		
		// Lock file
		/*if(strpos($mode, 'w') !== false)
			flock($this->fp, LOCK_EX);
		else
			flock($this->fp, LOCK_SH);*/
		return $this->fp;
	}
	/**
	 * close_file
	 * closes file pointer
	 * @return void
	 */
	function close_file(){
		// Unlock and close file
		//@flock($this->fp, LOCK_UN);
		@fclose($this->fp);
	}
	/**
	 * create_db 
	 * Create database file
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @return object instance
	 */
	function create_db(){
		$db_file = $this->db_file;
		if (!file_exists($db_file)) {
			$this->open_file($db_file, "wb");
			$this->close_file();
			@chmod($db_file, 0777);

		}

		//check readable
		if(!is_readable($db_file))
			throw $this->_ex('Error Reading database file '.$db_file);

		//check writeable
		if(!is_writable($db_file))
			throw $this->_ex('Error Writing database file '.$db_file);

		return $this;
	}
	/**
	 * get_keys
	 * Gets all keys in db
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @return array of keys
	 */
	function get_keys(){
		$keys = array();
		$this->open_file($this->db_file,"rb");
		// Loop through each line of file
		while (($line = fgets($this->fp)) !== false) {
			// Split up seperator
			$pairs = explode("=", $line);
			$keys[] = $pairs[0];
		}
		$this->close_file();
		return $keys;
	}
	/**
	 * in_cache 
	 * checks if a key exists in cached data
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @param  string $key
	 * @return boolean
	 */
	function in_cache($key){
		if($this->cache === true){
			return array_key_exists($key, $this->c);
		}
		return false;
	}
	/**
	 * get_key
	 * get a value based on key from bd or cache
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @param  string $key
	 * @return mixed value of key if found else false.
	 */
	function get_key($key){
		$ret = false;
		//check if in cache
		if($this->cache && $this->in_cache($key))
			return $this->c[$key];

		$this->open_file($this->db_file,"rb");
		while (($line = fgets($this->fp)) !== false) {
			// Remove new line character from end
			$line = rtrim($line);

			// Split up seperator
			$pairs = explode("=", $line);

			// Match found
			if ($pairs[0] == $key) {
				if (count($pairs) > 2) {
					array_shift($pairs);
					$ret = implode("=", $pairs);
				}else
					$ret = $pairs[1];
				// Unserialize data
				$ret = unserialize($ret);

				// Preserve new lines
				$ret = $this->escape_newlines($ret, true);

				// Save to cache
				if ($this->cache === true) {
					$this->c[$key] = $ret;
				}

				break;
			}
		}
		return $ret;
	}
	/**
	 * set
	 * set a value to the database
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @param string $key
	 * @param mixed $value
	 */
	function set($key,$value){
		//replace?
		if ($this->get_key($key) !== false){
			return $this->update($key,$value);
		}
		//save to cache
		if($this->cache === true)
			$this->c[$key] = $value;

		//escape new lines
		$value = $this->escape_newlines($value);
		//Serialize 
		$value = serialize($value);
		$this->open_file($this->db_file,'ab');
		$write = fwrite($this->fp,$key."=".$value."\n");
		$this->close_file();
		if ($write === false || !isset($write))
			throw $this->_ex('Error writing to database '.$this->db);

		return $this;
	}
	/**
	 * update
	 * update value in db
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @param  string $key
	 * @param  mixed $value value
	 * @return object instance
	 */
	function update($key,$value){
		if ($this->get_key($key) !== false){
			$this->open_file($this->db_file, "rb");
			$content = '';
			while (($line = fgets($this->fp)) !== false) {
				// Remove new line character from end
				//$line = rtrim($line);

				// Split up seperator
				$pairs = explode("=", $line);
				// Match found
				if ($pairs[0] == $key) {
					if($value !== false){
						//save to cache
						if($this->cache === true)
							$this->c[$key] = $value;
						//escape new lines
						$value = $this->escape_newlines($value);
						//Serialize 
						$value = serialize($value);
						$line = $key."=".$value."\n";
					}else{
						$line = '';
						//save to cache
						if($this->cache === true)
							unset($this->c[$key]);
					}
				}
				$content .= $line;
			}
			
			$this->close_file();
			$write = file_put_contents($this->db_file, $content);
			//$write = fwrite($this->fp,$content);
			
			if ($write === false)
				throw $this->_ex('Error writing to database '.$this->db);
			return $this;
		}else{
			return $this->set($key,$value);
		}
	}

	/**
	 * delete 
	 * Delete a value from database
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @param  string $key
	 * @return object instance
	 */
	function delete($key){
		if ($this->get_key($key) !== false){
			return $this->update($key,false);
		}
		return $this;
	}
	/**
	 * escape_newlines
	 * hellper to preserve line breaks
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @param  mixed  $value    value to escape
	 * @param  boolean $unescape revese?
	 * @return mixed escaped or unescaped value based on $unescape
	 */
	function escape_newlines($value,$unescape = false){
		if($unescape){
			$safe = array("\\n", "\\r");
			$unsafe = array("\n", "\r");
		}else{
			$safe = array("\n", "\r");
			$unsafe = array("\\n", "\\r");
		}
		if (is_string($value)) {
			$value = str_replace($safe, $unsafe, $value);
		}elseif (is_array($value)) {
			foreach ($value as $key => $val) {
				$value[$key] = $this->escape_newlines($val, $unescape);
			}
		}
		return $value;
	}

	/**
	 * get 
	 * Get value from database
	 * @since 0.1
	 * @author Ohad Raz <admin@bainternet.info>
	 * @param  string $key key to retrive
	 * @return mixed
	 */
	function get($key){
		return $this->get_key($key);
	}
}//end class