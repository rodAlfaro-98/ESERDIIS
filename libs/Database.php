<?php

//require_once('Database.php');

/*
* Mysql database class - only one connection alowed
*/
class Database extends mysqli {
	private $num_filas = 0;
	private $_connection;
	private static $_instance; //The single instance
	
	private $_host = '108.167.142.89';
	private $_username = 'cloudapp_fi';
	private $_password = 'q203OYj-wAb+';
	private $_database = 'cloudapp_fi';
	private $_port = '3306';
	/*g
	Get an instance of the Database
	@return Instance
	*/
	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one			
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	// Constructor
	private function __construct() {
		$this->_connection = new mysqli($this->_host, $this->_username, 
			$this->_password, $this->_database, $this->_port);
		$this->_connection->set_charset("utf8");
		// Error handling

		if(mysqli_connect_error()) {
			trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
				 E_USER_ERROR);
		}
	}

	// Magic method clone is empty to prevent duplication of connection
	private function __clone() { }

	// Get mysqli connection
	public function getConnection() {
		$this->_connection->set_charset("utf8");
		return $this->_connection;
	}
	
	/*	run
	 *	Runs a query that returns no results (INSERT, UPDATE, etc...)
	 *
	 *	@param	string	Query to be executed
	 *
     * 	@return	boolean	True on success, false on failure
     */
	public function run($query)
	{
		//$this->_connection->set_charset("utf8");
		$db = $this->_connection;

		$result = $db->query($query);
		if (mysqli_error($this))
		{
			throw new Exception(mysqli_error($this), mysqli_errno($this));
		}
		return $result;
	}

	/*	getArray
	 *	Runs a query and returns the result as an array
	 *
	 *	@param	string	Query to be executed.
	 *
     * 	@return	array	Array with the results (Empty if no results).
     */
	public function getArray($query)
	{
		//$this->_connection->set_charset("utf8");
		$db = $this->_connection;
		
		$result = array();
		$rs = $db->query($query, MYSQLI_STORE_RESULT);
		if (mysqli_error($this))
		{
			throw new Exception(mysqli_error($this), mysqli_errno($this));
		}
		if (true == $rs)
		{
			//$this->num_filas = $rs->num_rows;
			$this->num_filas = $rs->num_rows;

			while($row = $rs->fetch_assoc())
			{
				$result[] = $row;
			}
			$rs->free_result();



		}
		return $result;
	}

	/*	getRow
	 *	Runs a query and returns the first result row
	 *
	 *	@param	string	Query to be executed.
	 *
     * 	@return	array	Row with the results (Empty if no results).
     */
	public function getRow($query)
	{
		//$this->_connection->set_charset("utf8");
		$db = $this->_connection;

		$result = array();
		$rs = $db->query($query, MYSQLI_STORE_RESULT);
		if (mysqli_error($this))
		{
			throw new Exception(mysqli_error($this), mysqli_errno($this));
		}
		if (true == $rs)
		{
			$result = $rs->fetch_assoc();
			$rs->free_result();
		}
		return $result;
	}

	/*	getValue
	 *	Runs a query and returns the first result row
	 *
	 *	@param	string	Query to be executed.
	 *
     * 	@return	string	First value of the result query (Empty if no result).
     */
	public function getValue($query)
	{
		//$this->_connection->set_charset("utf8");
		$db = $this->_connection;

		$result = array();
		$rs = $db->query($query, MYSQLI_STORE_RESULT);
		if (mysqli_error($this))
		{
			throw new Exception(mysqli_error($this), mysqli_errno($this));
		}
		if (true == $rs)
		{
			$result = $rs->fetch_array(MYSQLI_NUM);
			$rs->free_result();
		}
		return $result[0];
	}

	public function getLastID()
	{
		//$this->_connection->set_charset("utf8");
		$db = $this->_connection;
		return mysqli_insert_id( $db );
	}

	public function getNumeroFilas()
	{
		return $this->num_filas;
	}

	public function close()
	{

		if ( $this != false ) {
			//return mysqli_close( $this );
		}
		/*
		if ( !empty( $this ) ) {
			return mysqli_close( $this );
		}*/
		//$this->close();
	}
	
}
?>