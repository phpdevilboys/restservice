<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Author : Anoop singh
 * Email  : anoop.immortal@gmail.com
 * Timestamp : Aug-29 06:11PM
 * Copyright : avaitor team
 *
 */

final class env
{
	/// -- Hold Static Connection Object --
	private static $ref;
        /// -- Hold Connection Object --
	private $_con;
        /// -- Hold Static TableStructure Object --
	private static $_tableStructure=array();

	/**
	 * Constructor
	 * @access protected
	 * @return void
	*/
	private function __construct(config $conf){
            $this->_con=mysql_connect($conf->getKeyValue("SQL_HOST_NAME"),
                             $conf->getKeyValue("SQL_USER_NAME"),
                             $conf->getKeyValue("SQL_PASSWORD")) or die("can't connect to DB");
            if($this->_con)
            {
                    $link=mysql_select_db($conf->getKeyValue("SQL_DB")) or die("cant use db".$conf->getKeyValue("SQL_DB"));
                    log_message("debug","env::connect_db <==> ".$link);
            }
            else
            {
                    echo "DB Failed";
            }
	}
   
   /**
	 * final static env::getInst
	 * @access Public
	 * @return Object
	 */
	final public static function getInst(){
            if(!is_object(env::$ref))
            {
                env::$ref=new env(config::getInst());
            }
            return env::$ref;
        }
   
   /**
	 * env::my_query
	 * @ACCESS public
         * @PARAM  (String)$query
         * @PARAM  (String)$errorMsg
	 * @RETURN ResultSet
	 */
	public function my_query($query, $errorMsg){
            $result = mysql_query($query) or die($errorMsg.' '.mysql_error());
            log_message("debug",__FILE__."<=>".__LINE__."::Query => ' ".$query." '\n");
            return  $result !== false ? $result : false;
	}
   
   /**
	 * env::my_query_id
	 * @ACCESS public
         * @PARAM  (String)$query
         * @PARAM  (String)$errorMsg
	 * @RETURN Laste Inserted ID
	 */
	//public function my_query_id($query, $errorMsg) // previous function title
	public function my_query_id(){
	   $result= mysql_insert_id();
	   return  $result;
	}
	 /**
	 * env::my_num_rows
	 * @ACCESS public
         * @PARAM  (recordSet)$res
	 * @RETURN ResultSet Resource
	 */
	public function my_num_rows($res){
		return mysql_num_rows($res);
	}
        /**
	 * env::my_fetch_object
	 * @ACCESS public
         * @PARAM  (recordSet)$res
	 * @RETURN ResultSet Resource
	 */
	public function my_fetch_object(&$res){
		return mysql_fetch_object($res);
	}
	/**
	 * env::my_fetch_array
	 * @ACCESS public
         * @PARAM  (recordSet)$res
	 * @RETURN ResultSet Resource
	 */
	public function my_fetch_array(&$res){
		return mysql_fetch_array($res);
	}
	/**
	 * env::my_fetch_assoc
	 * @ACCESS public
         * @PARAM  (recordSet)$res
	 * @RETURN ResultSet Resource
	 */
	public function my_fetch_assoc(&$res){
		return mysql_fetch_assoc($res);
	}
        /**
	 * env::data_prepare
	 * @ACCESS public
         * @PARAM  (STRING)$data
	 * @RETURN (STRING)
	 */
	public function data_prepare($data){
            if (is_array($data))
            {
                foreach ($data as $key => $value)
                {
                    $data[$key] = $value;
                }
            } else {
                if (get_magic_quotes_gpc())
		{
		   $data = stripslashes($data);
                   /*
                    * Validate standard character entities
                    *
                    * Add a semicolon if missing.  We do this to enable
                    * the conversion of entities to ASCII later.
                    *
                    */
                    $data = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $data);

                    /*
                    * Validate UTF16 two byte encoding (x00)
                    *
                    * Just as above, adds a semicolon if missing.
                    *
                    */
                    $data = preg_replace('#(&\#x?)([0-9A-F]+);?#i',"\\1\\2;",$data);

                    /*
                    * Un-Protect GET variables in URLs
                    */
                    $data = str_replace($this->xss_hash(), '&', $data);

                    /*
                    * URL Decode
                    *
                    * Just in case stuff like this is submitted:
                    *
                    * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
                    *
                    * Note: Use rawurldecode() so it does not remove plus signs
                    *
                    */
                    $data = rawurldecode($data);
                   $naughty = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
                   $non_displayables = array(
                                '/%0[0-8bcef]/',			// url encoded 00-08, 11, 12, 14, 15
                                '/%1[0-9a-f]/',				// url encoded 16-31
                                '/[\x00-\x08]/',			// 00-08
                                '/\x0b/', '/\x0c/',			// 11, 12
                                '/[\x0e-\x1f]/'				// 14-31
                   );
                   $data = preg_replace($non_displayables, '', $data);
                   $data = str_replace(array('<?php', '<?PHP', '<?', '?'.'>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $data);
                   $data = str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'),$data);
                   $data = preg_replace('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', array('&gt;', '&lt;'), $data);
                   $data = preg_replace("#<(/*)(script|xss)(.*?)\>#si", '[removed]', $data);
                   $data = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $data);
                   $data = str_replace("#href=.*?(alert\(|alert&\#40;|javascript\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si",$data);

		}
        else
        {
           $data = addslashes(trim($data));
        }
     }
            return $data;
	}
        /**
	 * env::my_num_fields
	 * @ACCESS public
         * @PARAM  (STRING)$resource
	 * @RETURN (STRING)
	 */
        public function my_num_fields($resource){
            return mysql_num_fields($resource);
        }
        /**
	 * env::my_fetch_field_type
	 * @ACCESS public
         * @PARAM  (STRING)$tableName
	 * @RETURN (ARRAY) return array
	 */
	private function my_fetch_field_type($tableName){
            if(is_array(env::$_tableStructure) && array_key_exists($tableName,env::$_tableStructure))
            {
                return env::$_tableStructure[$tableName];
            }
            else
            {
                $tableStructure=array();
                $result = $this->my_query("SELECT * FROM ".$tableName,"ERROR: ".$tableName);
                $fields = $this->my_num_fields($result);
                for ($i=0; $i < $fields; $i++) {
                    $type  = mysql_field_type($result, $i);
                    $name  = mysql_field_name($result, $i);
                    $len   = mysql_field_len($result, $i);
                    $flags = mysql_field_flags($result, $i);
                    $tableStructure[$name]=$type;
                }
                env::$_tableStructure[$tableName]=$tableStructure;
                unset($tableStructure);
                return env::$_tableStructure[$tableName];
            }
	}
        /**
         * @method env::insertAll
	 * @see     Creates the Insert statment for the table. Will be called 'INSERT'
	 * @param   (String) tableName
         * @param   (ARRAY)  tableColumns
	 * @return  (BOOLEAN)
	*/
        public function insertAll($tableName,array $tableColumns){
            /// -- List of Table Parameter Field to be inserted --
            $parameters="";
            /// -- List of Table Column Field to be inserted --
            $columnNames="";
            /// -- Fecth Feald type --
            $this->my_fetch_field_type($tableName);
            /// -- Create parameters --
            foreach(array_keys($tableColumns) as $key)
            {
                if(array_key_exists($key,env::$_tableStructure[$tableName]))
                {
                    if(env::$_tableStructure[$tableName][$key]=="int"
                      || env::$_tableStructure[$tableName][$key]=="float"
                      || env::$_tableStructure[$tableName][$key]=="decimal"
                      || env::$_tableStructure[$tableName][$key]=="real"
                      )
                    {
                        $parameters .= "".$this->data_prepare($tableColumns[$key]).", ";
                    }
                    else if(env::$_tableStructure[$tableName][$key]=="string"
                        || env::$_tableStructure[$tableName][$key]=="text"
                        || env::$_tableStructure[$tableName][$key]=="datetime"
                        || env::$_tableStructure[$tableName][$key]=="blob"
                    )
                    {
                        if($key=="f_password")
                        {
                            $tableColumns[$key]=md5($tableColumns[$key]);
                        }
                        $parameters .= "'".$this->data_prepare($tableColumns[$key])."', ";
                    }
                }
            }/// -- End foreach --
            $parameters = rtrim($parameters, ", ");
            /// -- Create the column Names --
            foreach($tableColumns as $column => $value){
              if(array_key_exists($column,env::$_tableStructure[$tableName]))
                {
                  if(substr($column, 0,2)=="f_"){
                    $columnNames .= "".$column.", ";
                  }
                }
            }/// -- End foreach --
            $columnNames = rtrim($columnNames, ", ");
            $string = "INSERT INTO ".$tableName." (".$columnNames.") VALUES (".$parameters.");";
            log_message("debug",__FILE__."<=>".__LINE__."insertAll():: Query ==> ".$string);
            $point="ERROR: ==>".$string."<==";
            if($this->my_query($string ,$point))
            {
                return true;
            }
            else
            {
                return false;
            }/// -- END:Fii my_query
        }/// -- END:Function insertAll() --
        
        /**
	 * @method      UpdateAll()
         * @see         Creates the update statment for the table. Will be called 'update'
	 * @param       (String) Table Name
	 * @return      N/A
	 *******************************/
	function UpdateAll($tableName,array $tableColumns,array $primaryKeyToUpdate, array $conditionSeq){
                /// -- List of Table Parameter Field to be inserted --
                $parameters="";
                /// -- List of Table Primary Key Update Field to be inserted --
                $primaryKeyUpdate="";
                /// -- Fecth Feald type --
                $this->my_fetch_field_type($tableName);
                /// -- Create parameters --
                foreach($tableColumns as $column => $values)
                {
                    if(array_key_exists($column,env::$_tableStructure[$tableName]))
                    {
                        if(env::$_tableStructure[$tableName][$column]=="int"
                          || env::$_tableStructure[$tableName][$column]=="float"
                          || env::$_tableStructure[$tableName][$column]=="decimal"
                          || env::$_tableStructure[$tableName][$column]=="real"
                        )
                        {
                            $parameters .= $column."=".$this->data_prepare($tableColumns[$column]).", ";
                        }
                        else if(env::$_tableStructure[$tableName][$column]=="string"
                            || env::$_tableStructure[$tableName][$column]=="text"
                            || env::$_tableStructure[$tableName][$column]=="datetime"
                            || env::$_tableStructure[$tableName][$column]=="blob"
                        )
                        {
                            if($column=="f_password")
                            {
                                $tableColumns[$column]=md5($tableColumns[$column]);
                            }
                            $parameters .= $column."='".$this->data_prepare($tableColumns[$column])."', ";
                        }
                    }
                }/// -- End foreach --
                $parameters = rtrim($parameters, ", ");

                /// -- Create the Primary Query Field Names --
                foreach($primaryKeyToUpdate as $column => $value)
                {
                    if(array_key_exists($column,env::$_tableStructure[$tableName]))
                    {
                        if(env::$_tableStructure[$tableName][$column]=="int"
                        || env::$_tableStructure[$tableName][$column]=="float"
                        || env::$_tableStructure[$tableName][$column]=="decimal"
                        || env::$_tableStructure[$tableName][$column]=="real"

                         )
                        {
                            $primaryKeyUpdate .= $column."=".$value." ";
                        }
                        else if(env::$_tableStructure[$tableName][$column]=="string"
                            || env::$_tableStructure[$tableName][$column]=="text"
                            || env::$_tableStructure[$tableName][$column]=="datetime"
                            || env::$_tableStructure[$tableName][$column]=="blob"
                        )
                        {
                            if($column=="f_password")
                            {
                                $value=md5($value);
                            }
                            $primaryKeyUpdate .= $column."='".$value."' ";
                        }
                        if(is_array($conditionSeq) && count($conditionSeq)>0)
                        {
                            if(array_key_exists($column,$conditionSeq))
                            {
                                $primaryKeyUpdate .=" ".$conditionSeq[$column]." ";
                            }
                        }
                    }

                }//End foreach
                $primaryKeyUpdate = rtrim($primaryKeyUpdate, ", ");
                $string="";
                if(isset($tableName) && isset($parameters) && isset($primaryKeyUpdate))
                {
                  $string .="UPDATE ".$tableName." SET ".$parameters." WHERE  ".$primaryKeyUpdate;
                  log_message("debug",__FILE__."<=>".__LINE__."UpdateAll():: Query ==> ".$string);
                }
                $point="ERROR: ==>".$string."<==";
                if($this->my_query($string ,$point))
                {
                    return true;
                }
                else
                {
                    return false;
                }
	}//End addUpdateMethod
        /*******************************
	 * addRemoveMethod:  Creates the delete statment for the table. Will be called 'remove'
	 * Parameters:   (String) Table Name
	 * Return:        N/A
	 *******************************/
        function DeleteAll($tableName,$primaryKeyToUpdate,array $conditionSeq=null){
            /// -- List of Table Primary Key Update Field to be inserted --
            $primaryKeyUpdate="";
            /// -- Fecth Feald type --
            $this->my_fetch_field_type($tableName);
            /// -- Create the Primary Query Field Names --
            foreach($primaryKeyToUpdate as $column => $value)
            {
                if(array_key_exists($column,env::$_tableStructure[$tableName]))
                {
                    if(env::$_tableStructure[$tableName][$column]=="int" 
                     || env::$_tableStructure[$tableName][$column]=="float"
                     || env::$_tableStructure[$tableName][$column]=="decimal"
                     || env::$_tableStructure[$tableName][$column]=="real"
                    )
                    {
                        $primaryKeyUpdate .= $column."=".$value." ";
                    }
                    else if(env::$_tableStructure[$tableName][$column]=="string"
                        || env::$_tableStructure[$tableName][$column]=="text"
                        || env::$_tableStructure[$tableName][$column]=="datetime"
                        || env::$_tableStructure[$tableName][$column]=="blob"
                    )
                    {
                        if($column=="f_password")
                        {
                            $value=md5($value);
                        }
                        $primaryKeyUpdate .= $column."='".$value."' ";
                    }
                    if(is_array($conditionSeq) && count($conditionSeq)>0 && count($conditionSeq)==count($primaryKeyToUpdate) - 1)
                    {
                        if(array_key_exists($column,$conditionSeq))
                        {
                            $primaryKeyUpdate .=" ".$conditionSeq[$column]." ";
                        }
                    }
                }
            }//End foreach
            $primaryKeyUpdate = rtrim($primaryKeyUpdate, ", ");
            $string="";
            if(isset($tableName) && isset($primaryKeyUpdate))
            {
                $string .="DELETE FROM ".$tableName." WHERE ".$primaryKeyUpdate;
            }
            $point="ERROR: ==>".$string."<==";
            if($this->my_query($string ,$point))
            {
                return true;
            }
            else
            {
                return false;
            }
	}//End addDeleteMethod()
}

// Below mentioned instructions are usable for usage of above functions

/*
//define("BASEPATH",".");
//include("config.php");
//include("common.php");
$testDb=env::getInst();
$tableName="cm_users";
$columnNameToUpdate=array(
    "f_address"=>"140 Industry house",
    "f_city" => "Indore",
    "f_state" => "MP",
    "f_country" => "India",
    "f_company" => "CDNSOL",
    "f_companyDiscription" => "CDNSOL PVT. LTD.",
    "f_companyAddress" => "140 Industry House",
    "f_companyPhone" => 12121212,
    "f_companyFax" => 12121212,
    "f_companyCity" => "INDORE",
    "f_compayCountry" => "INDIA",
    "f_websiteUrl" => "http://www.cdnsol.com",
    "f_phone" => 12121212,
    "f_mobileNumber" => 9898989898,
    "f_aboutMe" => "I am about me memememememem"
);

$primaryKeyToUpdate=array(
    "id" => "76",
    "f_fName"   => "Anoop Singh"
);

$conditionSeq=array(
    "id"=>"AND"
);
echo $testDb->UpdateAll($tableName,$columnNameToUpdate,$primaryKeyToUpdate,$conditionSeq);

 */
?>
