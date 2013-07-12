<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
*	@Class: Exception Handler
*	@Author: Anoop Singh
*	@Email: anoop.immortal@gmail.com
*	@Version: 1.0
*	@Usage: 	Put this is config file before using:
				$config["error-capture"]="path/to/error/handler/controller/"
				Configure HOOK for exception handling:
				$hook['post_controller_constructor'][] = array(
                   'class'    => 'ExceptionHook',
                   'function' => 'SetExceptionHandler',
                   'filename' => 'ExceptionHook.php',
                   'filepath' => 'hooks'
                  );
*/ 

class exceptionhook
{

	/*
	* @Method: __construct()
	* @params: return void 
	*/
	public function __construct(){
		
	}/// --- FEND::__construct() ---
	
	/*
	* @Method: SetExceptionHandler()
	* @params: void 
	* @return: null
	*/
	public function SetExceptionHandler()
	{
		/// --- handling of internal exceptions by triggering errors and handling them with a user defined function ---
		set_error_handler('myErrorHandler');
		
		/// --- handling of Sets a user-defined exception handler function ---
		set_exception_handler(array($this, 'HandleExceptions'));
		
		/// --- handling of internal Break down by triggering errors and handling them with function ---
		register_shutdown_function('handleShutdown');
		
	}/// --- FEND::SetExceptionHandler() ---
	
	
	/*
	* @Method: HandleExceptions($exception)
	* @params: $exception as Object
	* @return: Void
	*/
	public function HandleExceptions($exception)
	{
		$msg ='Exception of type \''.get_class($exception).'\' occurred with Message: '.$exception->getMessage().' in File '.$exception->getFile().' at Line '.$exception->getLine();
		$msg .="\r\n Backtrace \r\n";
		$msg .=$exception->getTraceAsString();
		log_message('error', $msg, TRUE);
		$data["message"]=$msg;
		echo $msg;
	}/// --- FEND::HandleExceptions($exception) ---
}

	/*
	* @Function: handleShutdown()
	* @params: void 
	* @return: null
	*/
	function handleShutdown()
	{
		if (($error = error_get_last())) {
			ob_start();
			$buffer = ob_get_contents();
			ob_clean();
			# report the event, send email etc.
			$um ='We have found some error please try again later.';
			echo "<pre>";
			//print_r($error);
			echo $um."\n".$buffer;
			echo "ERROR NO:" .$error["type"]."<br>";
			echo "ERROR MESSAGE:" .$error["message"]."<br>";
			echo "ERROR FILE:" .$error["file"]."<br>";
			echo "ERROR LINE:" .$error["line"]."<br>";
			exit();
		}
	}/// --- FEND::handleShutdown() ---

	/*
	* @Function: myErrorHandler()  error handler function
	* @params: void 
	* @return: null
	*/
	
	function myErrorHandler($errno, $errstr, $errfile, $errline)
	{
		if (!(error_reporting() & $errno)) {
			// This error code is not included in error_reporting
			return;
		}
		switch ($errno) {
		case E_USER_ERROR:
			$msg="<b>ERROR:</b> [$errno] $errstr<br />\n";
			$msg.="  Fatal error on line $errline in file $errfile";
			$msg.=", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			$msg.="Aborting...<br />\n";
			echo $msg;
			log_message('ERROR', $msg, TRUE);
			exit(1);
			break;

		case E_USER_WARNING:
			$msg="<b>ERROR:</b> [$errno] $errstr<br />\n";
			$msg.="  Fatal error on line $errline in file $errfile";
			$msg.=", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			$msg.="Aborting...<br />\n";
			echo $msg;
			log_message('ERROR', $msg, TRUE);
			break;

		case E_USER_NOTICE:
			$msg="<b>ERROR:</b> [$errno] $errstr<br />\n";
			$msg.="  Fatal error on line $errline in file $errfile";
			$msg.=", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			$msg.="Aborting...<br />\n";
			echo $msg;
			log_message('ERROR', $msg, TRUE);
			break;

		default:
			$msg="<b>ERROR:</b> [$errno] $errstr<br />\n";
			$msg.="  Fatal error on line $errline in file $errfile";
			$msg.=", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			$msg.="Aborting...<br />\n";
			echo $msg;
			log_message('ERROR', $msg, TRUE);
			break;
		}

		/* Don't execute PHP internal error handler */
		return true;
		
	}/// --- FEND::myErrorHandler() ---

/* End of file ExceptionHook.php */
/* Location: ./application/hook/ExceptionHook.php */	
