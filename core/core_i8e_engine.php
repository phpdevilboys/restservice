<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Author : Anoop singh
 * Email  : anoop.immortal@gmail.com
 * Timestamp : Aug-29 06:11PM
 * Copyright : avaitor team
 *
 */

/// -- Unserialize Call Back Function --
ini_set('unserialize_callback_func', '__autoload' );

/// -- __autoload Call Back Function --
function __autoload($extensionName)
{
    //echo $extensionName."<br>";
    if (isset($extensionName))
    {
            $extensionName = strtolower($extensionName);
            $inc=array(
					dirname(__FILE__)."/",
					dirname(dirname(__FILE__))."/core/",
					dirname(dirname(__FILE__))."/controllers/",
					dirname(dirname(__FILE__))."/models/",
					dirname(dirname(__FILE__))."/libraries/",
	            	dirname(dirname(__FILE__))."/helpers/",
	            	dirname(dirname(__FILE__))."/modules/",
	            	dirname(dirname(__FILE__))."/language/",
	            	dirname(dirname(__FILE__))."/include/",
					dirname(dirname(__FILE__))."/classes/",
                    dirname(dirname(__FILE__))."/inc/"
            );
            foreach($inc as $dir)
            {
                if (__load_class($extensionName, $dir))
                {
                    $logMessage="Class ".$dir.$extensionName." Loaded!!!";
                    return;
                }/// -- END FI $extensionName --
                
                $logMessage="Class Not Found!!!";
                
            }/// -- WEND $extensionName --
            
    }/// -- ENF FI $extensionName --

}/// -- END function __autoload --

/// -- __load_class Call Back Function --
function __load_class($classname, $dir)
{
     $file = $dir.$classname;
     $fileExtension=array(".php",".inc",".php5",".l");
     for($i=0;$i < count($fileExtension);$i++)
     {
	     //echo $file.$fileExtension[$i]."<br>";
	     if (file_exists($file.$fileExtension[$i]))
	     {
	     	//echo $file.$fileExtension[$i]."<br>";
        	require_once($file.$fileExtension[$i]);
	        return true;
	     }
	   }
     return false;

}/// -- END function __load_class --
?>

