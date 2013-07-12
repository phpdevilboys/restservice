<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Author : Anoop singh
 * Email  : anoop.immortal@gmail.com
 * Timestamp : Aug-29 06:11PM
 * Copyright : avaitor team
 *
 */
class users 
{
	public $_responce = array();


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
	}
	
	/**
	* @method GetUser
	* @see public function with Both GET and POST method
	* @access private
	* @return void
	*/
	public function GetUser(){
		
			if($_SERVER["REQUEST_METHOD"]=="POST")
			{
				return $_POST;
				/// -- UNCOMMENT THESE SECTION TO STORE VALUE TO DB--
				/*$test_array = array();
				if($_POST['name'] != '')
				{
					$query = 'INSERT INTO rest_service_test SET name ="'.$_POST['name'].'",age = '.$_POST['age'].',address="'.$_POST['address'].'"';
					/// -- Log Debug Message --
					log_message("debug","common file included");
					if($this->_db->my_query($query))
					{
						$test_array['record_insert_id'] = mysql_insert_id();
						$test_array['message'] = "Records are inserted successfully.";
					}
				}
				return	$test_array;
				*/
			}
			else if($_SERVER["REQUEST_METHOD"]=="GET")
			{
				$test_array = array("name"=>$_GET["name"],"age"=>$_GET["age"],"address"=>$_GET["addr"]);
				return	$test_array;
			}
			else{
				return array("ERROR"=>"Send Data via GET/POST");
			}
	}
}

?>
