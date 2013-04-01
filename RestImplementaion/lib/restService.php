<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author : Anoop singh
 * @Email  : anoop.immortal@gmail.com
 * @Timestamp : Aug-29-2011 06:11PM
 * @Version : 0.0
 * @Description : Files contains routing of rest service based on call, used to return output in 2 different format XML, JSON which is used in this package.
**/

final class restService{
	
	    /// -- Hold Service Action Request --
	    private $_action;
	    /// -- Hold Service Action Request --
	    private $_actionMethod;
	    /// -- Hold Service Action Parameter Request --
	    private $_register;
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
			/// -- Create Database Connection instance --
			//$this->_db=env::getInst();
			/// -- Log Debug Message --
			log_message("debug","DB Object Initialized!!");
	    }

		/**
	    * @method getFormat
	    * @see public Initialization
	    * @access public
	    * @return Format
	    */
		public function getFormat(){
			$this->_config=config::getInst()->getKeyValue("format");
			return $this->_config;
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
	    }
	    
	    /**
	    * @method Init
	    * @see private Initialization
	    * @access private
	    * @return void
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
	    			return json_encode($object);
	    		}
	    		else if(strtolower($requestBody)=="xml")
	    		{
					// Initiate the class
					$xml = loadObject("xml");
					// Set the array so the class knows what to create the XML from
					$xml->setArray($object);
					// return the XML to screen
					return $xml->outputXML('return');
	    		}else{
	    			return $object;
	    		}///--END:FII--
	    }///--END:FII getResponse () --
	    
}
?>