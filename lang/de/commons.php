<?php
    /**
     * FanPress CM Sprachpaket Deutsch
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    define("LANG_ACP_HEADLINE", "Administrationsbereich");

    /* Login */
    define("LANG_LOGIN_LOCKED_SUBJECT", "Login-Sperre eingetreten");
    define("LANG_LOGIN_LOCKED_TXT", "Für den Benutzer %lockeduser% wurde der Login um %lockedtime% Uhr gesperrt. Der Login erfolgte von der IP %lockedip%.");

    /* Admin-Index */
    define("LANG_WELCOME_TXT", "Du befindest dich im Dashboard von FanPress CM, dem News-System speziell für Fanseiten.<br><br>"
                             . "Über die Navigation links kannst du in die verschiedenen Bereiche von FanPress CM gelangen.<br><br>"
                             . "Nachfolgend findet du einige Statistiken deines Systems und ggf. weitere Informationen.");  

    /* Index-Statistiken */
    define("LANG_INDEX_STATS", "Statistiken");  
    define("LANG_STATS_NEWS", "Newsposts (insgesamt)"); 
    define("LANG_STATS_NEWS_AC", "Newsposts (aktiv)");
    define("LANG_STATS_NEWS_AR", "Newsposts (im Archiv)");
    define("LANG_STATS_NEWSP", "Newsposts (Entwurf)");     
    define("LANG_STATS_COMMENTS", "Kommentare (insgesamt)"); 
    define("LANG_STATS_COMMENTSP", "Kommentare (privat/ nicht freigegeben)");   
    define("LANG_STATS_COMMENTSNOTAPPROVED", "Kommentare");   
    define("LANG_STATS_USERS", "Benutzer im System"); 
    define("LANG_STATS_CATEGORIES", "Kategorien"); 
    define("LANG_STATS_SMILIES", "verwendbare Smileys"); 
    define("LANG_STATS_UPLOADS", "Hochgeladene Dateien");
    define("LANG_STATS_SIZE_UPLOAD","Größe des Upload-Ordner");
    define("LANG_STATS_SIZE_CACHE","Größe des Cache");        

    /* Sys-Check */     
    define('LANG_CHECK_HEADLINE','System-Check');
    define('LANG_CHECK_WRITE_UPLOAD','Upload-Ordner');        
    define('LANG_CHECK_WRITE_UPGRADES','Update-Ordner');
    define('LANG_CHECK_WRITE_CACHE','Cache-Ordner');
    define('LANG_CHECK_WRITE_MODULES','Modul-Ordner');
    define('LANG_CHECK_WRITE_STYLES','Styles-Ordner');
    define('LANG_CHECK_WRITE_DATA','Data-Ordner');
    define('LANG_CHECK_WRITE_REVISIONS','Revisions-Ordner');
    define('LANG_CHECK_WRITE_FILMGRTHUMBS','Dateimanager-Thumbnails');    
    define('LANG_CHECK_WRITE_CONNECTTOSRV','Verbindung zu anderen Servern möglich');
    define('LANG_CHECK_MAGIC_QUOTES','<b>PHP Magic Quotes</b>: Diese PHP-Funktionalität kann zu Fehlern beim Speichern führen und sollte unbedingt deaktiviert sein. Bitte kontaktiere deinen Host.');
    define('LANG_CHECK_REGISTER_GLOBALS','<b>PHP Register Globals</b>: Diese PHP-Funktionalität stellt ein potentielles Sicherheitsrisiko da und sollte unbedingt deaktiviert sein. Bitte kontaktiere deinen Host für.');
    define('LANG_CHECK_WRITE_OK','beschreibbar');
    define('LANG_CHECK_WRITE_FAILURE','nicht beschreibbar');

    /* Letzte news Liste */
    define('LANG_LASTESTNEWS_HEADLINE','Zuletzt geschriebene News');

    /* Admin Header */  
    define("LANG_HEADER_HELLO", "Hallo %username%! <i class=\"fa fa-smile-o\"></i>");
    define("LANG_HEADER_LOGGEDINSINCE", "eingeloggt seit");
    define("LANG_HEADER_VERSIONTXT", "Version");

    /* Admin-Navi */
    define("LANG_DASHBOARD", "Dashboard");
    define("LANG_WRITE", "News schreiben");
    define("LANG_EDIT", "News bearbeiten");
    define("LANG_EDIT_SUB_ALL", "Alle News anzeigen");
    define("LANG_EDIT_SUB_ACTIVE", "Aktive News anzeigen");
    define("LANG_EDIT_SUB_ARCHIVE", "Archivierte News anzeigen");	
    define("LANG_USR", "Benutzer &amp; Rollen");
    define("LANG_OPTIONS", "Optionen");
    define("LANG_HELP", "Hilfe");
    define("LANG_LOGOUT", "Abmelden");
    define("LANG_CACHE_CLEAR", "Cache leeren");
    define("LANG_HIDENAV", "Navigation ein/ausblenden");

    /* News Editor */
    define("LANG_NEWSEDITOR", "News-Editor");

    // Bild hochladen
    define("LANG_PICUOLOAD_FILE", "Datei hochladen"); 
    define("LANG_PICUOLOAD_FILE_EXIST", "Vorhandene Dateien");

    /* edit area */  
    define("LANG_EDIT_TITLE", "Titel");
    define("LANG_EDIT_CATEGORY", "Kategorien");
    define("LANG_EDIT_AUTHOR", "Author");
    define("LANG_EDIT_COMMENTS", "Kommentare");
    define("LANG_EDIT_REVISIONS", "Versionen");
    define("LANG_EDIT_DATE", "Datum");
    define("LANG_EDIT_OPENNEWS", "News aufrufen");    

    /* comment editor area */
    define("LANG_EDITCMT_TITLE", "Kommentar bearbeiten");  
    define("LANG_COMMENT_WRITTENFROM", "geschrieben von");  

    /* sysconfig area */
    define("LANG_SYSCFG_PROFILE", "Profil");    
    define("LANG_SYSCFG_OPTIONS", "Systemeinstellungen");
    define("LANG_SYSCFG_CATEGORIES", "Kategorien");
    define("LANG_SYSCFG_PERMISSIONS", "Rechte");
    define("LANG_SYSCFG_TEMPLATES", "Templates");
    define("LANG_SYSCFG_SMILIES", "Smileys");  
    define("LANG_SYSCFG_UPLOADS", "Upload-Verwaltung");
    define("LANG_SYSCFG_IPBANN", "IP-Sperre");
    define("LANG_SYSCFG_SYSLOG", "Systemlogs");
    define("LANG_SYSCFG_MODULES", "Module");
    define("LANG_SYSCFG_MODULES_MGR", "Module verwalten");

    /* usr config area */
    define("LANG_USR_EXIST", "Aktive Benutzer");
    define("LANG_USR_EXIST_DISABLED", "Deaktivierte Benutzer");
    define("LANG_USR_ADDUSRTITL", "Benutzer hinzufügen");        
    define("LANG_USR_ROLLS", "Benutzer-Rollen");
    define("LANG_USR_ROLLS_MANAGE", "Rollen verwalten");    
    define("LANG_USR_ROLLS_NEW", "Rolle hinzufügen");    
    define("LANG_USR_USRADDEDDATE", "erstellt am");
    define("LANG_USR_NEWSCOUNT", "Beiträge");
    define("LANG_USR_LASTLOGIN", "Login am");  
    define("LANG_USR_LASTLOGOUT", "Logout am");  
    define("LANG_USR_LOGOUTAUTO", "Session-Timeout");         

    /* category area */
    define("LANG_CAT_EXISTING", "Vorhandene Kategorien");
    define("LANG_CAT_ADDCAT", "Kategorie hinzufügen");
    define("LANG_CAT_EDIT", "Kategorie-Editor");

    /* system config area headlines */
    define("LANG_SYS_GENERAL", "Allgemein");
    define("LANG_SYS_NEWS", "News");
    define("LANG_SYS_COMMENTS", "Kommentare");

    /* system config area confignames */
    define("LANG_SYS_UPDATESTATUS", "Update-Status");
    define("LANG_SYS_UPDATESTATUS_OUTDATED", "Deine FanPress-Version ist <b>veraltet</b>!");
    define("LANG_SYS_UPDATESTATUS_CURRENT", "Deine FanPress-Version ist <b>aktuell</b>!");    
    define("LANG_SYS_UPDATESTATUS_DEVELOPER", "Deine FanPress-Version ist eine <b>Entwicklerversion</b>!");    
    define("LANG_SYS_UPDATESTATUS_START", "Update Starten...");
    define("LANG_SYS_UPDATESTATUS_RELEASEINFO", "Release Info");
    define("LANG_SYS_UPGRADE_FPCM3X", "Upgrade auf <b>FanPress CM 3.x</b> ist verfügbar!");

    /* ip bann area */
    define("LANG_IPBAN_EXIST", "Gesperrte IP-Adressen");
    define("LANG_IPBANN_ADRESSBANN", "IP-Adresse sperren");
    define("LANG_IPBANN_BANNEDIP", "IP-Adresse");
    define("LANG_IPBANN_BANNEDBY", "gesperrt von");
    define("LANG_IPBANN_BANNEDON", "gesperrt am");

    /* smilie area */
    define("LANG_SMILIE_EXISTING", "Vorhandene Smilies");
    define("LANG_SMILIE_ADDSMILIE", "Smiley hinzufügen");  

    /* uploads area */
    define("LANG_UPLOAD_FILEMANAGER", "Dateimanager");
    define("LANG_UPLOAD_OPENFILE", "Datei öffnen");
    define("LANG_UPLOAD_OPENTHUMB", "Thumbnail öffnen");
    define("LANG_UPLOAD_INSERTPATH", "Datei-Pfad in Quelle einfügen");
    define("LANG_UPLOAD_INSERTPATH_THUMB", "Thumbnail-Pfad in Quelle einfügen");
    define("LANG_UPLOAD_UPLOADER", "hochgeladen durch");
    define("LANG_UPLOAD_UPLOADDATE", "hochgeladen am");
    define("LANG_UPLOAD_FILERES", "<strong>Auflösung:</strong> %res_x% x %res_y% Pixel");
    define("LANG_UPLOAD_THUMBNAIL", "Vorschau");    

    /* syslog area */
    define("LANG_SYSCFG_FPUSERLOG", "Benutzerlog");
    define("LANG_SYSCFG_SYSTEMLOG", "System-Log");
    define("LANG_SYSCFG_PHPNOTLOG", "PHP-Log");
    define("LANG_SYSCFG_SQLLOG", "Datenbank-Log");

    /* modules area */
    define('LANG_MODULES_UPDATE_AVAILABLE', 'Es sind Updates für Module verfügbar.');
    define('LANG_MODULES_UPDATE_CURRENT', 'Alle Module sind <b>aktuell</b>.');
    define('LANG_MODULES_UPDATE_GOTO', 'Updates ansehen');
    define("LANG_MODULES_LIST", "Modul-Liste");
    define("LANG_MODULES_LISTMANUAL", "Lokale Modul-Liste");
    define("LANG_MODULES_LIST_LINK", "zu installierten Modulen");
    define("LANG_MODULES_MODULEAV", "Verfügbare Module &amp; Modul-Updates");
    define("LANG_MODULES_NAME", "Modulname");
    define("LANG_MODULES_KEY", "Modulkey");
    define("LANG_MODULES_TYPE", "Modultyp");
    define("LANG_MODULES_ACTION", "Modul Aktion");	
    define("LANG_MODULES_VERSIONLOCAL", "Lokal");
    define("LANG_MODULES_VERSIONSRV", "Repository");
    define("LANG_MODULES_UPDATE", "Update verfügbar");
    define("LANG_MODULES_NOTINSTALLED", "Nicht installiert");
    define("LANG_MODULES_UPLOADZIP", "Modul-Paket hochladen");

    /* auto update */
    define("LANG_AUTO_UPDATE", "Auto-Updater");
    define("LANG_AUTO_UPDATE_START", "Starte Download von ");
    define("LANG_AUTO_UPDATE_UNZIPCOPY", "Entpacken und Kopieren des Update-Paketes...");
    define("LANG_AUTO_UPDATE_EXTRACT_ERROR", "Beim Entpacken des Paketes ist ein Fehler aufgetreten.");
    define("LANG_AUTO_UPDATE_EXTRACT_OK", "Das Paket wurde erfolgreich entpackt.");
    define("LANG_AUTO_UPDATE_REMOVETEMPFILES", "Temporäre Dateien entfernen...");
    define("LANG_AUTO_UPDATE_TIMEFORDL", "Benötigte Zeit ");
    define("LANG_AUTO_UPDATE_FILELLIST", "Dateiliste anzeigen");
    
    /* date time strings */
    define('LANG_DATETIME_MONTHS', 'Januar,Februar,März,April,Mai,Juni,Juli,August,September,Oktober,November,Dezember');    
    define('LANG_DATETIME_DAYS', 'Montag,Dienstag,Mittwoch,Donnerstag,Freitag,Samstag,Sonntag');     
?>