Rest Base Web Service
=====================

This is basic Web Service Implementation looks like REST call (but not a REST call). You can directly use this as ready to go framework kind of thing you dont need to make any changes in core structure. To Use this you need to create classes based on your requirment and place them in lib folder. Now to use this class (Ex. users class which is a example class) as webservice you need to first create URL like:
  
	Ex. http://localhost/RestImplementaion/users/GetUser
	There Users is class name and GetUser is member function.
	
	If you want any Code class object you use steps as mentioned below:
	Ex: $abc=loadObject("users"); // This will Auto load your class and provide object to you.
		
Things To Do:
=====================
  .htaccess
    - Changes base url rewrite based on your folder structure:
      Ex. RewriteBase /RestImplementaion/

  Database Connection String: Config.php
    - You need to provide you DB credentials in this class with DB name you want to use.
      Ex.
        $this->_config["SQL_HOST_NAME"] ="localhost";
        $this->_config['SQL_USER_NAME'] ="root";
        $this->_config['SQL_PASSWORD']="";
        $this->_config['SQL_DB']="demodb";

  Log Class: log.php
    - Log class help you with logging all actions in a log file for further debuging and error handling thing. To manage log settings you have option in Config.php file, from where you can manage log levels;
    $this->_config['log_threshold'] = 4;
    |	0 = Disables logging, Error logging TURNED OFF
    |	1 = Error Messages (including PHP errors)
    |	2 = Debug Messages
    |	3 = Informational Messages
    |	4 = All Messages

  Output Format: Config.php
    - Web service output format is again a configuration which will be managed by config file. 2 possible format XML, JSON.	

  Contact Details:
    Please feel free you use this. Let me know your suggestion on this as well. If you have any issue or confusion contact me:
		  anoop.immortal@gmail.com
		  phpdevilboys@gmail.com
      Skype:			
		    anoop.immortal
		    phpdevilboys
