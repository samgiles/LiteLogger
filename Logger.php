<?php
/**
 * A really simple, lightweight logging class.  The only configuration needed is of the static $relativePath variable to the path where log files will be stored.
 * This needs to be the relative to where this file is installed.
 * @author Samuel E Giles
 * @version 0.5 August 2011
 * @license GPLv3 http://www.gnu.org/licenses/gpl.txt
 */
class Logger {
	
	/**
	 * The relative path to the logging directory.
	 * @var string
	 */
	private static $_relativePath = "/../logs/";
	
	/**
	 * The current instance of this object.
	 * @var Logger
	 */
	protected static $_instance;
	
	/**
	 * The complete path to the logging directory, this is set up in the constructor.
	 * @var string
	 */
	protected $_path;
	
	/**
	 * Gets the current instance of the Logger.
	 */
	public static function GetLogger(){
		if (!isset(Logger::$_instance) || is_null(Logger::$_instance)){
			Logger::$_instance = new Logger();
		}
		
		return Logger::$_instance;
	}
	
	/**
	 * Creates a new Logger object initialising the path to the log files.
	 */
	private function __construct(){
		$this->_path = dirname(__FILE__) . Logger::$_relativePath;
	}
	
	/**
	 * Logs an information message to the info.log file.
	 * @param string $message The message to log.
	 * @param mixed $sender The class that raised this informational message.
	 */
	public function info($message, $sender = null){
		$this->log($message, $sender, 'info');
	}
	
	/**
	 * Logs an error message to the error.log file.
	 * @param string $message The message to log.
	 * @param mixed $sender The class that raised this error message.
	 */
	public function error($message, $sender = null){
		$this->log($message, $sender, 'error');		
	}

	/**
	 * Logs a warning message to the warn.log file.
	 * @param string $message The message to log
	 * @param mixed $sender The class that raised this warning message.
	 */
	public function warn($message, $sender = null){
		$this->log($message, $sender, 'warn');		
	}
	
	/**
	 * Logs a message to the particular log file, setting up the format of the log line.
	 * @param string $message The message to log to the log file.
	 * @param mixed $sender The sending class.
	 * @param string $type The log type, this actually sets the filename as it's simple this way.
	 */
	private function log($message, $sender, $type){
		$this->checkFile("$type.log");
		$logMessage = "[" . date("M j G:i:s T");
		$logMessage .= "] - " . $message;
		
		if (isset($sender) && !is_null($sender)){
			$logMessage .= " (" . get_class($sender) . ")";
		}
		
		$logMessage .= " - Referrer: " . $_SERVER['REQUEST_URI'];
		
		$this->append("$type.log", $logMessage);
	}
	
	/**
	 * Checks the file.  If it doesn't exist it is created.  I wanted to set up some kind of log rotation, but this may defeat my simplicity goal.
	 * @param string $filename The filename to check inside the destination path.
	 */
	private function checkFile($filename){
		if(!file_exists($this->_path . $filename)){
			$handle = fopen($this->_path . $filename, 'w') or die("Can't create log file: " . $filename);
			fclose($handle);
			return;
		}
	}
	
	/**
	 * Appends a log message to a file.
	 * @param string $filename The filename to append to.
	 * @param string $message The message to append to the $filename
	 */
	private function append($filename, $message){
		$handle = fopen($this->_path . $filename, 'a') or die('Can not open file. ' . $filename);
		fwrite($handle,"\n\r" . $message . "\n\r");
		fclose($handle);
	}
}