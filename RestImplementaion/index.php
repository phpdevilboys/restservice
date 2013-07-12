<?php
/**
 * @Author : Anoop singh
 * @Email  : anoop.immortal@gmail.com
 * @Timestamp : Aug-29-2011 06:11PM
 * @Version : 0.0
 * @Description : Base file to handel all request
 */
	error_reporting(1);
	//@ini_set(‘display_errors’, 0);
	/// -- Get Output in Buffer --
	ob_start();
	/// -- Define base path to check it is a correct way to access --
	define('BASEPATH',dirname(__FILE__));
		/// -- Define Lib path --
	define('LIBPATH',"lib");
	/// -- Define Directory seprator --
	define('DS',"/");
	/// -- Define Base file extension type --
	define('EXT',".php");
	/// -- All to catch exception handling --
	try{

		/// -- check FIle exists or now --
		if(file_exists(BASEPATH.DS.LIBPATH.DS."common.php")){
			/// -- Include File exists or now --
			include(BASEPATH.DS.LIBPATH.DS."common.php");
			/// -- Log Debug Message --
			log_message("debug","common file included");
			/// -- Create Service Object --
			$resService=loadObject("restService");
			/// -- Init Service Object --
			$resService->init();
			/// -- GET Service Output format as XML, JSON --
			$format=$resService->getFormat();
			/// -- Execute Service Object --
			$result=$resService->execute($format);
			if($format=="xml")
			{
				header("Pragma: public"); // required
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
				header("Cache-Control: private",false); // required for certain browsers 
				header("content-type: text/xml; charset=utf-8");
				header("Content-Transfer-Encoding: binary");
			}
			echo $result;
		}
	}catch(Exception $e){
		die("OOPS something goes wrong");
	}
?>