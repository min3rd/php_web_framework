<?php
define("LOG_DB_MANAGER", "LOG_DB_MANAGER");
class DBManager
{
	private string $statement;
	private DBC $dbc;
	static protected $instance;
	const STATE_NONE = 'NONE';
	const STATE_BEGIN = 'BEGIN';
	const STATE_COMMIT = 'COMMIT';
	const STATE_ROLLBACK = 'ROLLBACK';


	public function __construct()
	{
		return false;
	}

	public static function getDBManager()
	{
		if (!DBManager::$instance) {
			$dbm = new DBManager();
			if (!$dbm->init()) {
				return false;
			}
			DBManager::$instance = $dbm;
		}
		return DBManager::$instance;
	}
	protected function init()
	{
		$this->dbc = new DBC(DB_HOSTNAME, DB_DATABASE, DB_USERNAME, DB_PASSWORD, DB_PORT, DB_SOCKET);
		if (!$this->dbc) {
			return false;
		}
		$this->statement = self::STATE_NONE;
		return true;
	}
	public function query($sql)
	{
		if (!$this->checkStatement($sql)) {
			return false;
		}
		if (!$this->dbc->query("SET NAMES 'UTF8'")) {
			Logger::error(LOG_DB_MANAGER, "force character setting failed");
			return false;
		}
		$result = $this->dbc->query($sql);
		if (!$result) {
			Logger::error(LOG_DB_MANAGER, "error sql=$sql: {$result}");
		}
		Logger::info(LOG_DB_MANAGER, "sql=$sql");
		return $result;
	}

	public function multi_query($sql)
	{
		return $this->dbc->multi_query($sql);
	}

	public function begin()
	{
		return $this->query(self::STATE_BEGIN);
	}

	public function rollback()
	{
		return $this->query(self::STATE_ROLLBACK);
	}

	public function commit()
	{
		$result = $this->query(self::STATE_COMMIT);
		if (!$result) {
			$this->rollback();
			return false;
		}
		return $result;
	}

	function checkStatement($raw)
	{
		$sql = trim($raw);
		$sql_upper_case = strtoupper($sql);
		$is_set_name_statement = strpos($sql_upper_case, "SET NAMES") === 0;
		$is_special_statement = false;
		$new_statement = $this->statement;
		if (strpos($sql_upper_case, self::STATE_BEGIN) === 0) {
			$new_statement = self::STATE_BEGIN;
			$is_special_statement = true;
		} else if (strpos($sql_upper_case, self::STATE_COMMIT) === 0) {
			$new_statement = self::STATE_COMMIT;
			$is_special_statement = true;
		} else if (strpos($sql_upper_case, self::STATE_ROLLBACK) === 0) {
			$new_statement = self::STATE_ROLLBACK;
			$is_special_statement = true;
		}

		$this->statement = $new_statement;
		$is_update_statement = (strpos($sql_upper_case, 'UPDATE') === 0 && strpos($sql_upper_case, 'WHERE') !== false)
			|| strpos($sql_upper_case, 'DELETE') === 0
			|| strpos($sql_upper_case, 'REPLACE') === 0
			|| strpos($sql_upper_case, 'FOR UPDATE') === 0
			|| strpos($sql_upper_case, 'INSERT') === 0;
		$is_query_statement = strpos($sql_upper_case, 'SELECT') === 0;
		if (!$is_set_name_statement && !$is_special_statement && !$is_update_statement && !$is_query_statement) {
			return false;
		}
		return true;
	}
}
