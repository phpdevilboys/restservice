<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Author : Anoop singh
 * Email  : anoop.immortal@gmail.com
 * Timestamp : Aug-29 06:11PM
 * Copyright : avaitor team
 *
 */
	
 	final class config
	{
	    /// -- Hold Static class object --
	    private static $ref;
	    /// -- Hold Static private class members variables --
	    private $_config=array();
	    /**
	    * @method __construct
	    * @see private constructor to protect beign inherited
	    * @access private
	    * @return void
	    */
	    private function __construct()
	    {
	        /*
	        |--------------------------------------------------------------------------
	        | Web Site URL
	        |--------------------------------------------------------------------------
	        | Get url of web site with domain name and folder address
	        */
	        $this->_config['siteUrl'] = $_SERVER["REQUEST_URI"];
	        /*
	        |--------------------------------------------------------------------------
	        | Web Service Name
	        |--------------------------------------------------------------------------
	        | Display Web Service Name on Service Interface
	        */
	        $this->_config['webServiceTitle'] = "CDN_WebSite_Manager";
	        /*
	        |--------------------------------------------------------------------------
	        | Web soap server Namespace
	        |--------------------------------------------------------------------------
	        | Display Web Service - create a soap server Namespace
	        */
	        $this->_config['namespace'] = "CDNSOL";
	        /*
	        |--------------------------------------------------------------------------
	        | Sql Host Server Name
	        |--------------------------------------------------------------------------
	        |
	        | Name of server which you are trying to connect Ex. (www.chatsupport.co.uk)
	        | Please do not allow this use access to "%" to mantain security
	        |
	        */
	        $this->_config["SQL_HOST_NAME"] ="localhost";
	        /*
	        |--------------------------------------------------------------------------
	        | Sql User Name to Database Server
	        |--------------------------------------------------------------------------
	        |
	        | Name of User which you are trying to connect with server Ex. (www.chatsupport.co.uk)
	        | Please do not allow this use access to "%" to this user to mantain security
	        |
	        */
	        $this->_config['SQL_USER_NAME'] ="root";
	        /*
	        |--------------------------------------------------------------------------
	        | Sql User Password to Database Server
	        |--------------------------------------------------------------------------
	        |
	        | Name of User Password which you are trying to connect with server Ex. (www.chatsupport.co.uk)
	        | Please do not allow this use access to "%" to this user to mantain security
	        |
	        */
	        $this->_config['SQL_PASSWORD']="8899-hijk";
	        /*
	        |--------------------------------------------------------------------------
	        | Sql Database Name
	        |--------------------------------------------------------------------------
	        |
	        | Name of Data-base which you are trying to connect with server Ex. (www.chatsupport.co.uk)
	        | Please Make sure that only local user have permission to access this DB
	        |
	        */
	        $this->_config['SQL_DB']="avaitor";
	        /*
	        |--------------------------------------------------------------------------
	        | Error Logging Directory Path
	        |--------------------------------------------------------------------------
	        |
	        | Leave this BLANK unless you would like to set something other than the default
	        | system/logs/ folder.  Use a full server path with trailing slash.
	        |
	        */
	        $this->_config['log_path'] = '';
	        /*
	        |--------------------------------------------------------------------------
	        | Error Logging Threshold
	        |--------------------------------------------------------------------------
	        | If you have enabled error logging, you can set an error threshold to
	        | determine what gets logged. Threshold options are:
	        | You can enable error logging by setting a threshold over zero. The
	        | threshold determines what gets logged. Threshold options are:
	        |
	        |	0 = Disables logging, Error logging TURNED OFF
	        |	1 = Error Messages (including PHP errors)
	        |	2 = Debug Messages
	        |	3 = Informational Messages
	        |	4 = All Messages
	        | For a live site you'll usually only enable Errors (1) to be logged otherwise
	        | your log files will fill up very fast.
	        */
	        $this->_config['log_threshold'] = 4;
	        /*
	        |--------------------------------------------------------------------------
	        | Date Format for Logs
	        |--------------------------------------------------------------------------
	        | Each item that is logged has an associated date. You can use PHP date
	        | codes to set your own date formatting
	        */
	        $this->_config['log_date_format']= 'D M j G:i:s T Y';
	    }
	    
	    /**
	    *Config::getInst()
	    *@Param Void
	    *@RETURN Static Object of Class
	    */
	    final public static function getInst()
	    {
	      if(!is_object(config::$ref)){
	          config::$ref=new config();
	      }
	      return config::$ref;
	    }
	    
	    /**
	    *Config::getConfig Array()
	    *@Param Void
	    *@RETURN Array of Settings
	    */
	    final public function getSettings()
	    {
	        return $this->_config;
	    }
	
	    /**
	    *config::getKeyValue Array()
	    *@Param key STRING
	    *@RETURN Array of Settings
	    */
	    final public function getKeyValue($key)
	    {
	        if(array_key_exists($key,$this->_config))
	        {
	          return $this->_config[$key];
	        }
	    }
	}///  -- Class:config ENDS --
?>
