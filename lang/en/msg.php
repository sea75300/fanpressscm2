<?php
    /**
     * FanPress CM Language package English
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    // Bild hochladen
    define("LANG_PICUOLOAD_ISUPLOADEDMSG", "The file has been uploaded.");
    define("LANG_PICUOLOAD_TYPENOTSUPPORTED", "Uploading of this file type is not supported.");    
    define("LANG_PICUOLOAD_FILEEXISTST", "The file already exists. Rename the file before uploading."); 

    /* write area */
    define("LANG_WRITE_EMPTY", "The news form cannot be empty.");
    define("LANG_WRITE_TWITTER_ERR", "An error occurred during transferring the news to Twitter: ");
    define("LANG_WRITE_TWITTER_AUT_ERR", "An error occurred while connecting to Twitter.");
    define("LANG_WRITE_TWITTER_AUT_MSG", "The connection to Twitter was successful."); 
    define("LANG_WRITE_TWITTER_DISC_MSG", "The connection to Twitter was cut.");	
    define("LANG_WRITE_PUBLISHED", "News has been published.");
    define("LANG_WRITE_PREVIEWPUB", "News has been saved as preview.");
    define("LANG_WRITE_POSTPONE", "News will be published automatically.");
    define("LANG_WRITE_COMMENTSDISABLED", "Comments are disabled");
    define("LANG_WRITE_REVISION_RESTORED", "The selected revision has been restored.");
    define("LANG_WRITE_REVISIONS_DELETED", "The selected article revisions have been deleted.");

    /* edit area */  
    define("LANG_EDIT_SAVEDMSG", "News has been edited.");
    define("LANG_EDIT_DELETEDMSG", "News has been deleted.");
    define("LANG_EDIT_ARCHIVEDMSG", "News has been moved to the archive.");
    define("LANG_EDIT_NEWTWEETED", "News has been send to Twitter again.");
    define("LANG_EDIT_PINNEDMSG", "News has been pinned.");
    define("LANG_EDIT_UNPINNEDMSG", "News has been unpinned.");
    define("LANG_EDIT_NOTPINNEDMSG", "News has not been pinned.");
    define("LANG_EDIT_COMMENTS_ENABLED_MSG", "Comments for news has been enabled.");
    define("LANG_EDIT_COMMENTS_DISABLED_MSG", "Comments for news has been disabled.");
    define("LANG_EDIT_COMMENTS_NOTTODO_MSG", "Comments for news has not been enabled or disabled.");
    define("LANG_EDIT_ACTION_CONFIRM_MSG", "Do you want to continue with this action?");
    define("LANG_EDIT_SEARCH_WAITMSG", "Please wait at least 10 seconds before start a new search request.");
    define("LANG_EDIT_TRASH_CLEARED", "Trash has been cleared.");
    define("LANG_EDIT_TRASH_RESTORED", "News from trash has been restored.");    

    /* show_news errors */
    define("LANG_NEWS_NOTFOUND", "The news post you're looking for wasn't found.");  
    define("LANG_NEWS_NOTFOUND_LIST", "No articles found.");
    define("LANG_NEWS_PREVIEWVIEW", "The news is not published yet, it's a preview.");
    define("LANG_NEWS_STATUSPINNED", "The news has been pinned and will be displayed above all further posts.");

    /* comment editor area */
    define("LANG_EDIT_NOCOMMENTS", "This news has no comments yet.");
    define("LANG_COMMENT_EDITTEDMSG", "Comment has been edited.");

    /* usr config area */
    define("LANG_USR_USRADDEDMSG", "The user has been added.");    
    define("LANG_USR_USREXISTMSG", "The user name already exists. Please choose another one.");    
    define("LANG_USR_USREDITEDMSG", "The user has been edited.");    
    define("LANG_USR_USRDELETEDMSG", "The user has been deleted.");     
    define("LANG_USR_USRDISABLEDMSG", "The user has been disabled.");      	
    define("LANG_USR_USRENABLEDMSG", "The user has been ensabled.");    
    define("LANG_USR_NOTWONDELETE", "You cannot delete your own account!");     
    define("LANG_USR_LASTUSR", "You cannot delete or disable the last user. One user is required.");       
    define("LANG_USR_EMPTY", "The user adding form cannot be empty.");  
    define("LANG_USR_PASS_CONFIRM_FAILED", "The given password phrases do not match.");
    define("LANG_USR_PASSSEC", "The given password is to weak. Please choose another one which contains upper- and lowercase letters, numbers and at lest 6 characters.");     
    define("LANG_USR_ROLL_CREATEDMSG", "A new user roll has been created.");   
    define("LANG_USR_ROLL_DELETEDMSG", "The user roll has been deleted.");           

    /* category area */
    define("LANG_CAT_ADDCATMSG", "The category has been added.");
    define("LANG_CAT_CATEXISTMSG", "The category already exist. Please use another name.");
    define("LANG_CAT_EDITATMSG", "The category has been edited.");  
    define("LANG_CAT_DELETED", "The category has been deleted.");  
    define("LANG_CAT_EMPTY", "The category adding form cannot be empty."); 

    /* profil config area */
    define("LANG_PROFILE_EDITMSG", "Changes on your profile has been saved");

    /* system config area confignames */
    define("LANG_SYS_NEWCONFIGMSG", "Changes on system configuration has been saved."); 

    /* ip bann area */
    define("LANG_IPBANN_BANNED_MSG", "The IP address has been blocked.");
    define("LANG_IPBANN_NOT_BANNED_MSG", "The ip address blocking form cannot be empty.");

    /* permissions config area */
    define("LANG_PERM_EDITMSG", "Changes on permissions has been saved.");  

    /* template area */
    define("LANG_TEMPLATE_NOCSSMSG", "CSS-Code was found in your code. The template couldn't be saved. See Help for further informations.");
    define("LANG_TEMPLATE_EDITMSG", "Template changes has been saved.");  
    
    /* templates unwritable */
    define("LANG_TEMPLATE_UNWRITABLE", "The changes of these template files cannot be saved, because they are not writable:");  

    /* smilie area */
    define("LANG_SMILIE_ADDED", "Smiley has been added.");
    define("LANG_SMILIE_EXIST", "The smiley is already in use. Please choose another one.");  
    define("LANG_SMILIE_DELETED", "Smiley has been deleted.");
    define("LANG_SMILIE_EMPTY", "The smiley adding form cannot be empty.");   

    /* uploads area */
    define("LANG_UPLOAD_FILENDELETED", "The file has been deleted.");
    define("LANG_UPLOAD_FILENOTEXIST", "The file has been wasn't found and might has been deleted. The database entry has been deleted.");
    define("LANG_UPLOAD_FILENOPERMISSIONS", "The file can't be deleted, because you hove not the permissions to do this. Nothing will be deleted.");
    define("LANG_UPLOAD_FILEINDEXREBUILD", "The file index has been rebuild.");
    define("LANG_UPLOAD_NEWTHUMBSCREATED", "Thumbnails were re-created.");

    /* module area */
    define("LANG_MODULE_ENABLED", "The module has been enabled.");
    define("LANG_MODULE_DISABLED", "The module has been disabled.");
    define("LANG_MODULE_INSTALLED", "The module was installed.");
    define("LANG_MODULE_UNINSTALLED", "The module has been uninstalled/deleted.");
    define("LANG_MODULE_ISDISABLED", "The module is currently disabled. You have to enabled it before you can use it.");
    define("LANG_MODULE_LOADERROR", "The module could not be loaded. Check if the module exists and if it is active.");
    define("LANG_MODULE_LOADTPLERROR", "The module template could not be loaded. Check if the templates exists.");	
    define("LANG_MODULE_LOADTPLERROR_PARAMSERR", "The parameters for the module template are not given as an ARRAY.");
    define("LANG_MODULE_MANUALLY", "You need to install or update modules manually. Sorry for that.");
    define("LANG_MODULE_INSTALL_DLSUC", "The module package was downloaded successfully.");
    define("LANG_MODULE_INSTALL_DLNOSUC", "An error occurred while downloading the module package!");

    /* auto update */
    define("LANG_AUTO_UPDATE_UPDATEDATA_ERR_MSG", "An error occurred while processing update data.");
    define("LANG_AUTO_UPDATE_DLSUCCESS_MSG", "The update file was downloaded successfully!");
    define("LANG_AUTO_UPDATE_DLNOSUCCESS_MSG", "An error occurred while downloading the update file! Please try again later.");  
    define("LANG_AUTO_UPDATE_PERMFAILURE_MSG", "The directory /upgrade/ is not writable. Please chmod it to 775 or 777 using your FTP access.");
    define("LANG_AUTO_UPDATE_COPYFAILED_MSG", "An error occurred while copying the files. You can restart the auto-updater or update FanPress CM manually.");
    define("LANG_AUTO_UPDATE_UPTODATE_MSG", "No update necessary, because your version is already up to date.");
    define("LANG_AUTO_UPDATE_SUCCESS_MSG", "The update was successful.");  
    define("LANG_AUTO_UPDATE_PHP_FILE", "The updater was found.<br><a class=\"fp-ui-button\" href=\"../update/update.php\">Open updater</a>");
    define("LANG_AUTO_UPDATE_AUTOSTART", "An update is forced to run automatically. Please wait...");

    /* Updater */
    define("LANG_UPDATE_ERROR", "An error occured while checking for updates.");
    define("LANG_UPDATE_SERVERFAILED", "The update server is not available. Please try later again.");

    /* error messages */
    define("LANG_ERROR_NOACCESS", "You are <b>not</b> logged in and have no access to this site.");
    define("LANG_ERROR_NOPERMISSIONS", "You don't have permission to access this site.");
    define("LANG_ERROR_COMMENT_EMPTY", "The comment adding form cannot be empty.");
    define("LANG_ERROR_IPISBANNED", "The comment cannot be saved, because your ip address has been blocked. Contact the
    site administration to get more informations.");
    define("LANG_ERROR_FLOODPROTECTION", "You have to wait a few second before you can add another comment.");  
    define("LANG_ERROR_SPAM_FALSE", "Anti spam question has been answered incorrectly.");
    define("LANG_ERROR_LOGIN_DATA_FALSE", "Wrong username or password!");  
    define("LANG_ERROR_LOGIN_ATTEMPTS", "Your login has been locked for %timeout% minutes due to a high use of wrong username or password.");  

    /* messages */
    define("LANG_NOTICE_COMMENT_APPROVAL_REQUIRED", "Thanks for your comment. It will appear here after it's approval by an administrator.");  
?>
