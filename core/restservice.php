<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Author : Anoop singh
 * Email  : anoop.immortal@gmail.com
 * Timestamp : Aug-29 06:11PM
 * Copyright : avaitor team
 *
 */

final class restService{
	
	    /// -- Hold Service Action Request --
	    private $_action;
	    /// -- Hold Service Action Request --
	    private $_actionMethod;
	    /// -- Hold Service token --
	    private $_token;
	    /// -- Hold Service hash key --
	    private $_hash;
	    /// -- Hold Service Action Parameter Request --
	    private $_register;
	    /// -- Hold encoding  --
	    private $_encoding = "UTF-8";
	    /// -- Hold All error in Array --
	    private $_errorSet=array();
	    /// -- Hold Service Action Request --
	    private $_db;
	    /// -- Hold Static private class members variables --
	    static private $_config=array();
	    /**
	    * @method __construct
	    * @see private constructor to protect beign inherited
	    * @access private
	    * @return void
	    */
	    public function __construct()
	    {
			/// -- Log Debug Message --
			log_message("debug","DB Object Initialized!!");
	    }
	    
	    /**
	    * @method Init
	    * @see public Initialization
	    * @access public
	    * @return void
	    */
	    public function init()
	    {
	    	if(isset($_REQUEST["action"]) && $_REQUEST["action"]!="")
	    	{
	    		$this->_action=$_REQUEST["action"];
	    		/// -- Log Debug Message --
					log_message("debug","Action Set:=>".$this->_action);
				if(isset($_REQUEST["actionMethod"]) && $_REQUEST["actionMethod"]!="")
		    	{
		    		$this->_actionMethod=$_REQUEST["actionMethod"];
		    		/// -- Log Debug Message --
					log_message("debug","Action Method Set:=>".$this->_actionMethod);
		    	}
		    	else{
		    		$this->setErrors("faultActionMethod","Action Method requested Not found");
		    		/// -- Log Debug Message --
					log_message("debug",json_encode($this->_errorSet));
		    	}///--END:FII--
	    	}
	    	else{
	    		$this->setErrors("faultAction","Action requested Not found");
	    		/// -- Log Debug Message --
				log_message("debug",json_encode($this->_errorSet));
	    	}///--END:FII--
	    }///--END:init --
	    
	    /**
	    * @method setEncoding
	    * @access public
	    * @param string (UTF-8, ISO-8859-1)
	    * @return void
	    */
	    public function setEncoding($str)
	    {
			$this->encoding = strval($str);
			return true;
		}///--END:setEncoding --
	    
	    /**
	    * @method execute
	    * @see private Initialization
	    * @access public
	    * @return response
	    */
	    public function execute($requestBody = null)
	    {
	    	if(count($this->_errorSet)>0){
	    		/// -- return Error Message --
    			return $this->getErrors($requestBody);
	    	}///--END:FII--
	    	
	    	/// -- check if class exists --
	    	if(class_exists($this->_action))
	    	{
	    		/// -- Load Class Object --
	    		$class=loadObject($this->_action);
	    		/// -- Log Debug Message --
				log_message("debug","Object of Class ".$this->_action." created successfully");
					
	    	}else{
	    		
	    		/// -- Set Error Message --
	    		$this->setErrors("faultClass","Class ".$this->_action." not Found");
	    		/// -- Log Debug Message --
				log_message("debug","Object of Class ".$this->_action." not Found");
	    		/// -- return error message with requestbody type --
	    		return $this->getErrors($requestBody);
	    		
	    	}///--END:FII class_exists () --

	    	/// -- if $class is object of requested class --
    		if(is_a($class, $this->_action))
    		{
    			/// -- if  method_exists in class --
    			if(method_exists($class,$this->_actionMethod))
    			{
    				/// -- return response of method --
    				return $this->getResponse($class->{$this->_actionMethod}(),$requestBody);
    			} else{
    				/// -- set error message --
    				$this->setErrors("faultClassMethod","Class ".$this->_action." Method ".$this->_actionMethod." not Found");
    				/// -- return error message with requestbody type --
    				return $this->getErrors($requestBody);
    			}///--END:FII method_exists () --
    			
    		}else{
    			
    			/// -- Set Error Message --
	    		$this->setErrors("faultClass","Object Is not of type ".$this->_action."!!!");
	    		/// -- Log Debug Message --
					log_message("debug","Object Is not of type ".$this->_action."!!!");
	    		/// -- return error message with requestbody type --
	    		return $this->getErrors($requestBody);
	    		
    		}///--END:FII is_a () --
	    }///--END:execute --
	    
	    /**
	    * @method setError
	    * @see private setError($actionTag,$actionValue)
	    * @access private
	    * @params $actionTag as STRING
	    * @params $actionValue as STRING
	    * @return void
	    */
	    private function setErrors($actionTag,$actionValue)
	    {
	    	$this->_errorSet[$actionTag]=$actionValue;
	    }
	    
	    /**
	    * @method getErrors
	    * @see public getErrors($requestBody="")
	    * @access public
	    * @params $requestBody as OPTIONAL
	    * @return Error Message as _errorSet with requestBody
	    */
	    public function getErrors($requestBody="")
	    {
			if(strtolower($requestBody)=="json")
			{
				return json_encode($this->_errorSet);
			}
			else if(strtolower($requestBody)=="xml")
			{
				// Initiate the class
				$xml = loadObject("xml");
				// Set the array so the class knows what to create the XML from
				$xml->setArray((array)$object);
				// return the XML to screen
				return $xml->outputXML('return');
			}else{
				return $this->_errorSet;
			}
			///--END:FII--
	    }
	    
	     /**
	    * @method getResponse
	    * @see public getResponse($object,$requestBody="")
	    * @access public
	    * @params $object as OBJECT
	    * @params $requestBody as response Type
	    * @return Response Message as requestBody
	    */
	    public function getResponse($object,$requestBody="")
	    {
			if(strtolower($requestBody)=="json")
			{
				return $this->getJsonResponse($object);
			}
			else if(strtolower($requestBody)=="xml")
			{
				return $this->getXMLResponse($object);
			}else{
				return $object;
			}///--END:FII--
	    }///--END:FII getResponse () --
	    
	    
	    /**
	    * @method getXMLResponse
	    * @access private
	    * @params object (Array)
	    * @return Returns xml output
	    */
	    private function getXMLResponse($object) {
			// Initiate the class
			$xml = loadObject("xml");
			// Set encoding for xml if it is other than utf8
			if($this->_encoding != "UTF-8")
				$xml->setXMLEncoding($this->_encoding);
			// Set the array so the class knows what to create the XML from
			$xml->setArray($object);
			// return the XML to screen
			return $xml->outputXML('return');
		}///--END:getXMLResponse () --
		
	    
	    /**
	    * @method getJsonResponse
	    * @access private
	    * @params object (Array)
	    * @return json response of passed array
	    */
	    private function getJsonResponse($object) {
			if($this->_encoding == "UTF-8") {
				$this->definePhpVersionId();
						
				if(PHP_VERSION_ID >= 50400) {
					return json_encode($object, JSON_UNESCAPED_UNICODE);
				} else {
					return $this->getUnicodeJson($object);
				}
			} else {
				return json_encode($object);
			}
		}///--END:getJsonResponse () --
		
	    /**
	    * @method definePhpVersionId
	    * @access private
	    * @params none
	    * @return Define PHP_VERSION_ID for php versions that are less than 5.2
	    */
	    private function definePhpVersionId() {
			if (!defined('PHP_VERSION_ID')) {
				$version = explode('.', phpversion());

				define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
			}///--END:FII--
		}///--END:definePhpVersionId () --
		
		
	    /**
	    * @method getUnicodeJson
	    * @access private
	    * @params object (Array)
	    * @return json string with unicode characters
	    */
	    private function getUnicodeJson($object) {
			array_walk_recursive($object, function(&$item, $key) {
				if(is_string($item)) {
					$item = htmlentities($item);
				}///--END:FII--
			});///--END:FUNCTION--
			
			$json = json_encode($object);
			return html_entity_decode($json);
			//return $json;
		}///--END:getUnicodeJson () --
}
?>
