<?php
    /**
     * FanPress CM Sprachpaket Deutsch
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    // Bild hochladen
    define("LANG_PICUOLOAD_ISUPLOADEDMSG", "Datei wurde hochgeladen!");
    define("LANG_PICUOLOAD_TYPENOTSUPPORTED", "Das Hochladen dieses Dateityp wird nicht unterstützt.");    
    define("LANG_PICUOLOAD_FILEEXISTST", "Die Datei existiert bereits! Benne die Datei vor dem Hochladen um.");  

    /* write area */
    define("LANG_WRITE_EMPTY", "Bitte fülle das Formular aus, sonst kann die News nicht gespeichert werden.");
    define("LANG_WRITE_TWITTER_ERR", "Bei der Übermittlung der News an Twitter ist ein Fehler aufgetreten: ");
    define("LANG_WRITE_TWITTER_AUT_ERR", "Beim Herstellen der Verbindung zu Twitter ist ein Fehler aufgetreten: ");
    define("LANG_WRITE_TWITTER_AUT_MSG", "Die Verbindung wurde erfolgreich hergestellt.");
    define("LANG_WRITE_TWITTER_DISC_MSG", "Die Verbindung wurde gelöscht.");
    define("LANG_WRITE_PUBLISHED", "News wurde veröffentlicht.");
    define("LANG_WRITE_PREVIEWPUB", "News wurde als Entwurf gespeichert.");
    define("LANG_WRITE_POSTPONE", "News wird automatisch freigeschalten.");
    define("LANG_WRITE_COMMENTSDISABLED", "Kommentare sind deaktiviert");
    define("LANG_WRITE_REVISION_RESTORED", "Die Revision des Artikels wurde wiederhergestellt.");
    define("LANG_WRITE_REVISIONS_DELETED", "Die gespeicherten Revisionen des Artikels wurden gelöscht.");

    /* edit area */  
    define("LANG_EDIT_SAVEDMSG", "News wurde editiert.");
    define("LANG_EDIT_DELETEDMSG", "News wurde gelöscht.");
    define("LANG_EDIT_ARCHIVEDMSG", "News wurde in's Archiv verschoben.");
    define("LANG_EDIT_NEWTWEETED", "News wurde erneut an Twitter gesendet.");
    define("LANG_EDIT_PINNEDMSG", "News wurde gepinnt.");
    define("LANG_EDIT_UNPINNEDMSG", "News ist nicht mehr gepinnt.");
    define("LANG_EDIT_NOTPINNEDMSG", "News wurde nicht gepinnt.");
    define("LANG_EDIT_COMMENTS_ENABLED_MSG", "Die Kommentare für News wurden aktiviert.");
    define("LANG_EDIT_COMMENTS_DISABLED_MSG", "Die Kommentare für News wurden deaktiviert.");
    define("LANG_EDIT_COMMENTS_NOTTODO_MSG", "Kommentare für wurden für keine News (de)aktiviert.");
    define("LANG_EDIT_ACTION_CONFIRM_MSG", "Was du diese Aktion wirklich durchführen?");
    define("LANG_EDIT_SEARCH_WAITMSG", "Bitte warte 10 Sekunden, bevor du einen neuen Suchvorgang startest.");
    define("LANG_EDIT_TRASH_CLEARED", "Der Papierkorb wurde geleert.");
    define("LANG_EDIT_TRASH_RESTORED", "News wurden aus dem Papierkorb wiederhergestellt.");

    /* show_news errors */
    define("LANG_NEWS_NOTFOUND", "Die von dir gesuchte News wurde nicht gefunden.");
    define("LANG_NEWS_NOTFOUND_LIST", "Es wurden keine Artikel gefunden.");
    define("LANG_NEWS_PREVIEWVIEW", "Diese News ist als Entwurf gespeichert und wird noch nicht angezeigt.");
    define("LANG_NEWS_STATUSPINNED", "Diese News ist gepinnt und wird deshalb über allen anderen angezeigt.");

    /* comment editor area */
    define("LANG_EDIT_NOCOMMENTS", "Zu dieser News gibt es keine Kommentare.");
    define("LANG_COMMENT_EDITTEDMSG", "Kommentar wurde editiert.");    

    /* usr config area */
    define("LANG_USR_USRADDEDMSG", "Benutzer wurde hinzugefügt.");    
    define("LANG_USR_USREXISTMSG", "Der Benutzername existiert bereits. Bitte wähle einen anderen.");    
    define("LANG_USR_USREDITEDMSG", "Benutzer wurde editiert.");    
    define("LANG_USR_USRDELETEDMSG", "Benutzer wurde gelöscht.");     
    define("LANG_USR_USRDISABLEDMSG", "Benutzer wurde deaktiviert.");      
    define("LANG_USR_USRENABLEDMSG", "Benutzer wurde aktiviert.");    
    define("LANG_USR_NOTWONDELETE", "Du kannst deinen eigenes Konto nicht löschen!");     
    define("LANG_USR_LASTUSR", "Du kannst den letzten Benutzer nicht löschen oder deaktivieren! Es muss mindestens einer vorhanden sein!");     
    define("LANG_USR_EMPTY", "Bitte fülle das Formular aus, sonst kann der Benutzer nicht angelegt werden.");  
    define("LANG_USR_PASS_CONFIRM_FAILED", "Die eingegebenen Passwörter stimmen nicht überein.");
    define("LANG_USR_PASSSEC", "Das angegebene Passwort ist zu schwach. Bitte wählen eines, welches Groß- und Kleinbuchstaben, sowie Zahlen und min. 6 Zeichen enthält.");
    define("LANG_USR_ROLL_CREATEDMSG", "Eine neue Benutzerrolle wurde erstellt.");   
    define("LANG_USR_ROLL_DELETEDMSG", "Die Benutzerrolle wurde gel&ouml;scht.");   

    /* category area */
    define("LANG_CAT_ADDCATMSG", "Kategorie wurde hinzufügt.");
    define("LANG_CAT_CATEXISTMSG", "Die Kategorie existiert bereits. Bitte wähle einen anderen Namen.");
    define("LANG_CAT_EDITATMSG", "Kategorie wurde bearbeitet.");  
    define("LANG_CAT_DELETED", "Kategorie wurde gelöscht.");  
    define("LANG_CAT_EMPTY", "Bitte fülle das Formular aus, sonst kann die Kategorie nicht angelegt werden."); 

    /* profil config area */
    define("LANG_PROFILE_EDITMSG", "Änderungen am Profil gespeichert");
    define("LANG_SYS_NEWCONFIGMSG", "Änderungen an der Konfiguration wurden gespeichert."); 

    /* ip bann area */
    define("LANG_IPBANN_BANNED_MSG", "Die IP-Adresse wurde gesperrt.");
    define("LANG_IPBANN_NOT_BANNED_MSG", "Es wurde keine IP-Adresse gesperrt, da das Formular leer war.");

    /* permissions config area */
    define("LANG_PERM_EDITMSG", "Änderungen an den Rechten wurden gespeichert.");  

    /* template area */
    define("LANG_TEMPLATE_NOCSSMSG", "In deinem Template-Code ist CSS-Code enthalten. Daher kann das Template nicht speichert werden.
    Mehr dazu in der Hilfe.");
    
    define("LANG_TEMPLATE_EDITMSG", "Änderungen am Template wurden gespeichert.");  
    
    /* templates unwritable */
    define("LANG_TEMPLATE_UNWRITABLE", "Änderungen an folgenden Templates können nicht gespeichert werden, da sie nicht beschreibbar sind:");      

    /* smilie area */
    define("LANG_SMILIE_ADDED", "Smiley wurde hinzugefügt.");
    define("LANG_SMILIE_DELETED", "Smiley wurde gelöscht.");
    define("LANG_SMILIE_EXIST", "Der Smiley-Code wird schon verwendet. Bitte wähle einen anderen.");  
    define("LANG_SMILIE_EMPTY", "Bitte fülle das Formular aus, sonst kann der Smiley nicht hinzugefügt werden.");   

    /* uploads area */
    define("LANG_UPLOAD_FILENDELETED", "Die Datei wurde gelöscht.");
    define("LANG_UPLOAD_FILENOTEXIST", "Die Datei wurde nicht gefunden, da sie evtl. schon gelöscht wurde. Es wird der Eintrag in der in der Datenbank gelöscht.");
    define("LANG_UPLOAD_FILENOPERMISSIONS", "Die Datei kann nicht gelöscht werden, da du nicht die entsprechenden Rechte dazu hast. Es wird deshalb nichts gelöscht.");
    define("LANG_UPLOAD_FILEINDEXREBUILD", "Der Dateiindex wurde neu aufgebaut.");
    define("LANG_UPLOAD_NEWTHUMBSCREATED", "Thumbnails wurden neu erzeugt.");

    /* module area */
    define("LANG_MODULE_ENABLED", "Das Modul wurde aktiviert.");
    define("LANG_MODULE_DISABLED", "Das Modul wurde deaktiviert.");
    define("LANG_MODULE_INSTALLED", "Das Modul wurde installiert.");
    define("LANG_MODULE_UNINSTALLED", "Das Modul wurde deinstalliert/gelöscht.");
    define("LANG_MODULE_ISDISABLED", "Das Modul ist nicht aktiv. Um es zu verwenden muss es aktiviert werden.");
    define("LANG_MODULE_LOADERROR", "Das Modul konnte nicht geladen werden. Prüfe ob es vorhanden und aktiv ist.");
    define("LANG_MODULE_LOADTPLERROR", "Das Modul-Template konnte nicht geladen werden. Prüfe ob es vorhanden ist.");
    define("LANG_MODULE_LOADTPLERROR_PARAMSERR", "Die übergebenen Paramater für das Modul-Template müssen als ARRAY übergeben werden.");
    define("LANG_MODULE_MANUALLY", "Leider kannst du Module nur manuell installieren oder aktualisieren.");
    define("LANG_MODULE_INSTALL_DLSUC", "Das Modul-Paket wurde erfolgreich heruntergeladen.");
    define("LANG_MODULE_INSTALL_DLNOSUC", "Das Modul-Paket konnte nicht heruntergeladen werden!");

    /* auto update */
    define("LANG_AUTO_UPDATE_UPDATEDATA_ERR_MSG", "Beim Verarbeiten der Update-Informationen ist ein Fehler aufgetreten.");
    define("LANG_AUTO_UPDATE_DLSUCCESS_MSG", "Die Update-Datei wurde erfolgreich heruntergeladen!");
    define("LANG_AUTO_UPDATE_DLNOSUCCESS_MSG", "Beim Herunterladen der Update-Datei ist ein Fehler aufgetreten! Bitte versuche es später noch einmal.");
    define("LANG_AUTO_UPDATE_PERMFAILURE_MSG", "Das Verzeichnis /upgrade/ ist nicht beschreibbar. Bitte setze die Zugriffsrechte per FTP auf 775 oder 777.");
    define("LANG_AUTO_UPDATE_COPYFAILED_MSG", "Beim Kopieren der Dateien ist ein Fehler aufgetreten. Start den Auto-Updater neu oder aktualisiere FanPress CM manuell.");
    define("LANG_AUTO_UPDATE_UPTODATE_MSG", "Du verwendest bereits die aktuelle Version, ein Update ist nicht nötig.");
    define("LANG_AUTO_UPDATE_SUCCESS_MSG", "Das Update wurde erfolgreich durchgeführt.");
    define("LANG_AUTO_UPDATE_PHP_FILE", "Der Updater wurde gefunden.<br><a class=\"fp-ui-button\" href=\"../update/update.php\">Zum Updater</a>");
    define("LANG_AUTO_UPDATE_AUTOSTART", "Ein Update wird automatisch installiert. Bitte warten...");

    /* Updater */
    define("LANG_UPDATE_ERROR", "Bei der Prüfung auf Updates ist ein Fehler aufgetreten.");
    define("LANG_UPDATE_SERVERFAILED", "Der Update-Server ist nicht erreichbar. Prüfe später nochmal auf Updates.");

    /* error messages */
    define("LANG_ERROR_NOACCESS", "Du bist <b>nicht</b> im System eingeloggt und hast somit keinen Zugriff auf diese Seite.");
    define("LANG_ERROR_NOPERMISSIONS", "Du hast keine Recht um auf diese Seite zuzugreifen.");
    define("LANG_ERROR_COMMENT_EMPTY", "Bitte fülle das Formular aus, sonst kann der Kommentar nicht eingetragen werden.");
    define("LANG_ERROR_IPISBANNED", "Der Kommentar konnte nicht speichert werden, da deine IP gesperrt wurde. Bitte wende dich an die Administration der Seite um mehr zu erfahren.");
    define("LANG_ERROR_FLOODPROTECTION", "Du musst einige Sekunden warten, bevor du einen neuen Kommentar schreiben kannst.");  
    define("LANG_ERROR_SPAM_FALSE", "Anti-Spam-Frage wurde falsch beantwortet.");
    define("LANG_ERROR_LOGIN_DATA_FALSE", "Benutzername oder Passwort sind falsch!");  
    define("LANG_ERROR_LOGIN_ATTEMPTS", "Du hast Benutzername oder Passwort zu oft falsch eingegeben. Der Login ist für %timeout%min gesperrt.");  

    /* messages */
    define("LANG_NOTICE_COMMENT_APPROVAL_REQUIRED", "Danke für deinen Kommentar. Er muss von einem Admin freigeschalten werden muss um sichtbar zu sein.");
