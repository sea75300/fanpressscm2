<?php
    /**
     * FanPress CM Language package English
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    define("LANG_ACP_HEADLINE", "Admin Control Panel");

    /* Login */
    define("LANG_LOGIN_LOCKED_SUBJECT", "Login ban for user");
    define("LANG_LOGIN_LOCKED_TXT", "The login for user %lockeduser% was locked at %lockedtime%. The users ip address was %lockedip%.");    

    /* Admin-Index */
    define("LANG_WELCOME_TXT", "You are in the dashboard of FanPress CM, the news system for fan sites.<br><br>"
                             . "Use the main navigation on the left to enter the different areas.<br><br>"
                             . "Below you will finde some stats about your system and maybe other additional informations.");  


    /* Index-Statistiken */
    define("LANG_INDEX_STATS", "Statistics");  
    define("LANG_STATS_NEWS", "News posts (overall)"); 
    define("LANG_STATS_NEWS_AC", "News posts (active)");
    define("LANG_STATS_NEWS_AR", "News posts (archived)");  
    define("LANG_STATS_NEWSP", "News posts (preview)");     
    define("LANG_STATS_COMMENTS", "Comments (overall)"); 
    define("LANG_STATS_COMMENTSP", "Comments (private/ not approved)");   
    define("LANG_STATS_USERS", "User in system"); 
    define("LANG_STATS_CATEGORIES", "Categories"); 
    define("LANG_STATS_SMILIES", "usable smileys"); 
    define("LANG_STATS_UPLOADS", "uploaded files");  
    define("LANG_STATS_SIZE_UPLOAD","Upload folder size");
    define("LANG_STATS_SIZE_CACHE","Cache size");        

    /* Sys-Check */     
    define('LANG_CHECK_HEADLINE','System-Check');
    define('LANG_CHECK_WRITE_UPLOAD','Upload folder');        
    define('LANG_CHECK_WRITE_UPGRADES','Update folder');
    define('LANG_CHECK_WRITE_CACHE','Cache folder');
    define('LANG_CHECK_WRITE_MODULES','Modules folder');
    define('LANG_CHECK_WRITE_STYLES','Styles folder');
    define('LANG_CHECK_WRITE_DATA','Data folder');
    define('LANG_CHECK_WRITE_REVISIONS','Revision folder');
    define('LANG_CHECK_WRITE_FILMGRTHUMBS','File manager thumbnail folder');
    define('LANG_CHECK_WRITE_CONNECTTOSRV','Connections to other servers');
    define('LANG_CHECK_MAGIC_QUOTES','<b>PHP Magic Quotes</b>: This PHP functionality may cause errors and should be disabled. Please contact your host!');
    define('LANG_CHECK_REGISTER_GLOBALS','<b>PHP Register Globals</b>: This PHP functionality is a potential security issue and should be disabled. Please contact your host!');
    define('LANG_CHECK_WRITE_OK','is writable');
    define('LANG_CHECK_WRITE_FAILURE','is not writable');

    /* Letzte news Liste */
    define('LANG_LASTESTNEWS_HEADLINE','Latest written news');

    /* Admin Header */  
    define("LANG_HEADER_HELLO", "Hello %username%! <i class=\"fa fa-smile-o\"></i>");
    define("LANG_HEADER_LOGGEDINSINCE", "logged in since");
    define("LANG_HEADER_VERSIONTXT", "Version");

    /* Admin-Navi */
    define("LANG_DASHBOARD", "Dashboard");
    define("LANG_WRITE", "Add news");
    define("LANG_EDIT", "Edit news");
    define("LANG_EDIT_SUB_ALL", "Show all news");
    define("LANG_EDIT_SUB_ACTIVE", "Show active news");
    define("LANG_EDIT_SUB_ARCHIVE", "Show archieved news");	
    define("LANG_USR", "Users &amp; user rolls");
    define("LANG_OPTIONS", "Options");
    define("LANG_HELP", "Help");
    define("LANG_LOGOUT", "Logout");
    define("LANG_CACHE_CLEAR", "Clear cache");
    define("LANG_HIDENAV", "Hide/show navigation");

    /* News Editor */
  
    define("LANG_NEWSEDITOR", "News-Editor");
     
    // Bild hochladen
    define("LANG_PICUOLOAD_FILE", "Upload file"); 
    define("LANG_PICUOLOAD_FILE_EXIST", "Existing files"); 

    /* edit area */  
    define("LANG_EDIT_TITLE", "Title");
    define("LANG_EDIT_CATEGORY", "Categories");
    define("LANG_EDIT_AUTHOR", "Author");
    define("LANG_EDIT_COMMENTS", "Comments");
    define("LANG_EDIT_REVISIONS", "Revisions");
    define("LANG_EDIT_DATE", "Date");
    define("LANG_EDIT_OPENNEWS", "Open news");    

    /* comment editor area */
    define("LANG_EDITCMT_TITLE", "Edit comment");  
    define("LANG_COMMENT_WRITTENFROM", "written by");  

    /* sysconfig area */
    define("LANG_SYSCFG_PROFILE", "Profile");
    define("LANG_SYSCFG_OPTIONS", "System options");
    define("LANG_SYSCFG_CATEGORIES", "Categories");
    define("LANG_SYSCFG_PERMISSIONS", "Permissions");
    define("LANG_SYSCFG_TEMPLATES", "Templates");
    define("LANG_SYSCFG_SMILIES", "Smileys");  
    define("LANG_SYSCFG_UPLOADS", "Uploads");
    define("LANG_SYSCFG_IPBANN", "IP Banning");
    define("LANG_SYSCFG_SYSLOG", "System log");
    define("LANG_SYSCFG_MODULES", "Modules");
    define("LANG_SYSCFG_MODULES_MGR", "Manage modules");

    /* usr config area */
    define("LANG_USR_EXIST", "Active users");
    define("LANG_USR_EXIST_DISABLED", "Disabled users");
    define("LANG_USR_ROLLS", "User rolls");
    define("LANG_USR_ROLLS_MANAGE", "Manage user rolls");
    define("LANG_USR_ROLLS_NEW", "Add user roll");
    define("LANG_USR_USRADDEDDATE", "added on");
    define("LANG_USR_NEWSCOUNT", "written news"); 	
    define("LANG_USR_ADDUSRTITL", "Add user");
    define("LANG_USR_LASTLOGIN", "login on");  
    define("LANG_USR_LASTLOGOUT", "logout on");  
    define("LANG_USR_LOGOUTAUTO", "session timeout");  	

    
    /* category area */
    define("LANG_CAT_EXISTING", "Existing categories");
    define("LANG_CAT_ADDCAT", "Add category");
    define("LANG_CAT_EDIT", "Category editor");
    
    /* system config area headlines */
    define("LANG_SYS_GENERAL", "General");
    define("LANG_SYS_NEWS", "News");
    define("LANG_SYS_COMMENTS", "Comments");

    /* system config area confignames */
    define("LANG_SYS_UPDATESTATUS", "Update status");
    define("LANG_SYS_UPDATESTATUS_OUTDATED", "Your FanPress version is <b>outdated</b>!");
    define("LANG_SYS_UPDATESTATUS_CURRENT", "Your FanPress version is <b>up to date</b>!");    
    define("LANG_SYS_UPDATESTATUS_DEVELOPER", "Your FanPress version is a <b>developer version</b>!");
    define("LANG_SYS_UPDATESTATUS_START", "Start update...");
    define("LANG_SYS_UPDATESTATUS_RELEASEINFO", "Release info");
    define("LANG_SYS_UPGRADE_FPCM3X", "Upgrade to <b>FanPress CM 3.x</b> is available!");

    /* ip bann area */
    define("LANG_IPBAN_EXIST", "Blocked IP addresses");
    define("LANG_IPBANN_ADRESSBANN", "Block IP address");
    define("LANG_IPBANN_BANNEDIP", "IP address");
    define("LANG_IPBANN_BANNEDBY", "blocked by");
    define("LANG_IPBANN_BANNEDON", "blocked on");

    /* smilie area */
    define("LANG_SMILIE_EXISTING", "Existing smilies");
    define("LANG_SMILIE_ADDSMILIE", "Add smiley");  

    /* uploads area */
    define("LANG_UPLOAD_FILEMANAGER", "File manager");
    define("LANG_UPLOAD_OPENFILE", "Open file");
    define("LANG_UPLOAD_OPENTHUMB", "Open thumbnail");
    define("LANG_UPLOAD_INSERTPATH", "Insert file path into source");
    define("LANG_UPLOAD_INSERTPATH_THUMB", "Insert thumbnail path into source");
    define("LANG_UPLOAD_UPLOADER", "uploaded by");
    define("LANG_UPLOAD_UPLOADDATE", "uploaded on");
    define("LANG_UPLOAD_FILERES", "<strong>Resolution:</strong> %res_x% per %res_y% pixel");
    define("LANG_UPLOAD_THUMBNAIL", "Thumbnail");

    /* syslog area */
    define("LANG_SYSCFG_FPUSERLOG", "User log");
    define("LANG_SYSCFG_SYSTEMLOG", "System log");
    define("LANG_SYSCFG_PHPNOTLOG", "PHP log");
    define("LANG_SYSCFG_SQLLOG", "Database log");

    /* modules area */
    define('LANG_MODULES_UPDATE_AVAILABLE', 'Updates for modules are available.');
    define('LANG_MODULES_UPDATE_CURRENT', 'All modules are <b>up to date</b>.');
    define('LANG_MODULES_UPDATE_GOTO', 'Watch updates');    
    define("LANG_MODULES_LIST", "Module list");
    define("LANG_MODULES_LISTMANUAL", "Local module list");
    define("LANG_MODULES_LIST_LINK", "show installed modules");
    define("LANG_MODULES_MODULEAV", "Available modules and module-updates");
    define("LANG_MODULES_NAME", "Module name");
    define("LANG_MODULES_KEY", "Module key");
    define("LANG_MODULES_TYPE", "Module type");
    define("LANG_MODULES_ACTION", "Modul action");
    define("LANG_MODULES_VERSIONLOCAL", "Local");
    define("LANG_MODULES_VERSIONSRV", "Repository");	
    define("LANG_MODULES_UPDATE", "Update available");
    define("LANG_MODULES_UPLOADZIP", "Upload module package");

    /* auto update */
    define("LANG_AUTO_UPDATE", "Auto-Updater");
    define("LANG_AUTO_UPDATE_START", "Starting download from file ");
    define("LANG_AUTO_UPDATE_UNZIPCOPY", "Extract and copy files...");
    define("LANG_AUTO_UPDATE_EXTRACT_ERROR", "An error occurred whil extracting the package.");
    define("LANG_AUTO_UPDATE_EXTRACT_OK", "The package was extracted successfully.");
    define("LANG_AUTO_UPDATE_REMOVETEMPFILES", "Remove temporary files...");
    define("LANG_AUTO_UPDATE_TIMEFORDL", "Time required ");
    define("LANG_AUTO_UPDATE_FILELLIST", "Show file list");
    
    /* date time strings */
    define('LANG_DATETIME_MONTHS', 'January,February,March,April,May,June,July,August,September,October,November,December');    
    define('LANG_DATETIME_DAYS', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday');      
?>
