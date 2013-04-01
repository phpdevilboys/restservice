<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author : Anoop singh
 * @Email  : anoop.immortal@gmail.com
 * @Timestamp : Aug-29-2011 06:11PM
 * @Version : 0.0
 * @Description : File is Sample USER file used in this package.
**/
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
		$this->_db=env::getInst();
	}
	
	public function GetUser(){
		
			if($_SERVER["REQUEST_METHOD"]=="POST")
			{
				$test_array = array();
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
				//$test_array = array(name=>"AMIT",age=>"121",address=>"140 industry house");
				
//				$this->$_responce = $test_array;
				return	$test_array;
			}
			else if($_SERVER["REQUEST_METHOD"]=="GET")
			{
				 
				$test_array = array(name=>"anoop",age=>"12",address=>"120 industry house");
//				$this->$_responce = $test_array;
				return	$test_array;
			}
			else{
				return array("ERROR"=>"Send Data via GET/POST");
			}
	}
}

?>