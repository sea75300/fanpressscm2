<?php
    /**
     * FanPress CM Installer language English
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-*2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

  define("L_BTNNEXT", "next");
  
  /* Schritt 1 */
  
  define("L_DBCONNECTION","Database connection");
  
  define("L_DBSRV","Database server (usually localhost)");
  define("L_DBTYPE","Database type");
  define("L_DBUSR","Database user");
  define("L_DBPWD","Database password");
  define("L_DBNAME","Database name");
  define("L_DBPFX","Database prefix");
  define("L_FPDIR","Fanpress directory");  
  
  /* Schritt 2 */
  define("L_CREATECONFIG","Creating config file");
  define("L_CREATECONFIG_NEXT","The connection to the database was successfull");
  
  /* Schritt 3 */  
  define("L_NODB","Cannot connect to database. Plase check your access data.");
  define("L_CREATETABELS","Creating tabels in database");
  
  /* Schritt 4 */  
  define("L_CREATEADMIN","Creating admin user"); 
  define("L_ADMINUNAME", "User name");  
  define("L_ADMINPWD", "Password");  
  define("L_ADMINPWD_CONFIRM", "Confirm password");
  define("L_ADMINNAME", "Visible name");  
  define("L_ADMINEMAIL", "Email address");
  define("L_ADMINPASSWDSEC", "Please choose another password which contains upper- and lowercase letters, numbers and at lest 6 characters.");
  define("L_ADMINPASSWDCONFIRM", "The entered passwords do not match.");
  
  /* Schritt 5 */  
  define("L_CONFIGSYS","Configuring system");   
  define("L_SYS_ANTISPAMQUESTION","Anti spam question");
  define("L_SYS_ANTISPAMANSWER","Answer to Anti spam question");
  define("L_SYS_SYSMAIL","Email address for system");
  define("L_SYS_URL","URL for article links (your index.php, if you use phpinclude)");
  define("L_SYS_LANG","Language");
  define("L_SYS_UNSECURE_USERNAME","This user name has a very high security risk and cannot be used! <a href=\"javascript:location.replace('index.php?isstep=4&ilang=%lang%')\">Go back</a>");

  /* Schritt 6 */    
  define("L_INSTALL_FINISHED","Installation finished"); 
  define("L_INSTALL_FINWELCOME","Congratulations!"); 
  define("L_INSTALL_FINTXT","You have finished the Installation fo fanpress and can use it! ^^"); 
  define("L_INSTALL_FINTOLOGIN","<a href=\"javascript:location.replace('../login.php')\">Click here</a> to log in."); 


  define("L_INSTALL_NOTICES","Notices");   
  define("L_DELETE_INSTALL_DIR", "<b>Delete</b> the <b>install</b>-directory in the FanPress directory onto your server.");
  define("L_UPLOAD_CHMOD_ERR","The permissions for upload directory <i>/data/upload/</i> count not be set to 777. Plase make this to via FTP.");

?>
