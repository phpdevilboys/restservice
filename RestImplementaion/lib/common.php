<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author : Anoop singh
 * @Email  : anoop.immortal@gmail.com
 * @Timestamp : Aug-29-2011 06:11PM
 * @Version : 0.0
 * @Description : Files contains all necessare function used in this package
**/
	
	/**
	* Error Logging Interface
	* Function::log_message()
	* We use this as a simple mechanism to access the logging
	* class and send messages to be logged.
	* @access	public
	* @return	void
	*/
	function log_message($level = "error", $message="", $php_error = FALSE) {
		global $wgLog;
	    if(is_object($wgLog)) {
	        $wgLog->write_log($level, $message, $php_error);
	    }
	    else {
	        $wgLog=loadObject("log");
	        $wgLog->write_log($level, $message, $php_error);
	    }
	}
	
	/**
	* Class registry
	*
	* This function acts as a singleton.  If the requested class does not
	* exist it is instantiated and set to a static variable.  If it has
	* previously been instantiated the variable is returned.
	*
	* @access	public
	* @param	string	the class name being requested
	* @param	bool	optional flag that lets classes get loaded but not instantiated
	* @return	object
	*/
	function loadObject($class, $instantiate = "") {
	    static $objects = array();
	    // Does the class exist?  If so, we"re done...
	    if (array_key_exists($class, $objects)){
	        return $objects[$class];
	    }
	    if(isset($instantiate) && $instantiate != "") {
	        $objects[$class] = new $class($instantiate);
	    }
	    else {
	        $objects[$class] = new $class();
	    }
	    log_message("debug",$class.":: Object Initiated Successfully ");

	    return $objects[$class];
	}
	/**
	* Loads the main config.php file
	* Function::&get_config()
	* @access	private
	* @return	array
	*/
	function &get_config() {
		  if (file_exists(BASEPATH.DS.LIBPATH.DS."config".EXT)) {
	        $_config=config::getInst()->getSettings();
	        return $_config;
	    }
	    else if (!file_exists(BASEPATH.DS.LIBPATH.DS."config".EXT)) {
	        exit("The configuration file config".EXT." does not exist.");
	    }
	    else if ( (!isset($_config)) || (!is_array($_config))) {
	        exit("Your config file does not appear to be formatted correctly.");
	    }
	}
	
	/*
	|* Include basic engine core i8E_engine
	|*
	*/
	if(file_exists(BASEPATH.DS.LIBPATH.DS."core_i8e_engine.php"))
	{
	    @include(BASEPATH.DS.LIBPATH.DS."core_i8e_engine.php");
	}
	else
	{
	    die("Illegal use of Script!! Main COnfig files Missing!!!!");
	}
?>