<?php

class Logger
{
	const ERROR = "error";
	const INFO = "info";
	const WARNING = "warning";
	protected static $log_folder = __DIR__ . "/../logs";

	public static function log($type, $category, $log)
	{
		return;
		$debug_backtrace =  debug_backtrace();
		$file = isset($debug_backtrace[1]) ? $debug_backtrace[1]['file'] : "";
		$line = isset($debug_backtrace[1]) ? $debug_backtrace[1]['line'] : "";
		if (!file_exists(static::$log_folder)) {
			if (!mkdir(static::$log_folder)) {
				return false;
			}
		}
		$now = time();
		if (!file_exists(static::$log_folder . DIRECTORY_SEPARATOR . date("Y", $now))) {
			if (!mkdir(static::$log_folder . DIRECTORY_SEPARATOR . date("Y", $now))) {
				return false;
			}
		}
		if (!file_exists(static::$log_folder . DIRECTORY_SEPARATOR . date("Y", $now) . DIRECTORY_SEPARATOR . date("m", $now))) {
			if (!mkdir(static::$log_folder . DIRECTORY_SEPARATOR . date("Y", $now) . DIRECTORY_SEPARATOR . date("m", $now))) {
				return false;
			}
		}
		$log_file = fopen(static::$log_folder . DIRECTORY_SEPARATOR . date("Y", $now) . DIRECTORY_SEPARATOR . date("m", $now) . DIRECTORY_SEPARATOR . sprintf("log_%s.log", date("Y-m-d", $now)), "a");
		if ($log_file === false) {
			return false;
		}
		fwrite($log_file, sprintf("[%s][%s][%s] %s %s - line %s\r\n", date("Y-m-d H:i:s", $now), $type, $category, $log, $file, $line));
		fclose($log_file);
	}
	public static function info($category, $log)
	{
		return static::log(self::INFO, $category, $log);
	}
	public static function error($category, $log)
	{
		return static::log(self::ERROR, $category, $log);
	}
	public static function warning($category, $log)
	{
		return static::log(self::WARNING, $category, $log);
	}
}
