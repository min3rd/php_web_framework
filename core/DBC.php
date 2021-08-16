<?php
class DBC extends mysqli
{
	public function __construct($host, $database, $username, $password, $port = null, $socket = null)
	{
		parent::__construct($host, $username, $password, $database, $port, $socket);
		if ($this->connect_errno) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			die('Connect Error (' . $this->connect_errno . ') ' . $this->connect_error);
		}
	}
	function __destruct()
	{
		$this->close();
	}
}
