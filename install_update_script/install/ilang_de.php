<?php
    /**
     * FanPress CM Installer language German
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-*2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

  define("L_BTNNEXT", "weiter");
  
  /* Schritt 1 */
  
  define("L_DBCONNECTION","Datenbank-Verbindung");
  
  define("L_DBSRV","Datenbank-Server (normalerweise localhost)");
  define("L_DBTYPE","Datenbank-Typ");
  define("L_DBUSR","Datenbank-Benutzer");
  define("L_DBPWD","Datenbank-Passwort");
  define("L_DBNAME","Datenbank-Name");
  define("L_DBPFX","Datenbank-Prefix");
  define("L_FPDIR","Fanpress-Verzeichnis");  
  
  /* Schritt 2 */
  define("L_CREATECONFIG","Konfigurationsdatei erstellen");
  define("L_CREATECONFIG_NEXT","Die Verbindung zur Datenbank konnte hergestellt werden!");
  
  /* Schritt 3 */  
  define("L_NODB","Es konnte keine Verbindung zur Datenbank hergestellt werden! Bitte pr&uuml;fe die Zugangsdaten.");
  define("L_CREATETABELS","Tabellen erstellen");
  
  /* Schritt 4 */  
  define("L_CREATEADMIN","Admin-User erstellen"); 
  define("L_ADMINUNAME", "Benutzername");  
  define("L_ADMINPWD", "Passwort");  
  define("L_ADMINPWD_CONFIRM", "Passwort bestätigen");
  define("L_ADMINNAME", "angezeigter Name");  
  define("L_ADMINEMAIL", "E-Mail-Adresse");    
  define("L_ADMINPASSWDSEC", "Dein Passwort muss Gro&szlig;- und Kleinbuchstaben, Ziffern enthalten sowie min. 6 Zeichen lang sein.");
  define("L_ADMINPASSWDCONFIRM", "Die eingegebenen Passwörter stimmen nicht überein. <a href=\"javascript:location.replace('index.php?isstep=5&ilang=%lang%')\">Zurück</a>");
  
  /* Schritt 5 */  
  define("L_CONFIGSYS","System konfiguriren");   
  define("L_SYS_ANTISPAMQUESTION","Anti-Spam-Frage");
  define("L_SYS_ANTISPAMANSWER","Antwort auf Anti-Spam-Frage");
  define("L_SYS_SYSMAIL","E-Mail-Adresse");
  define("L_SYS_URL","URL f&uuml;r Artikellinks (deine index.php, wenn du phpinclude verwendest)");
  define("L_SYS_LANG","Sprache");
  define("L_SYS_UNSECURE_USERNAME","Dieser Benutzername birgt ein hohes Sicherheistrisiko und kann daher nicht verwendet werden! <a href=\"javascript:location.replace('index.php?isstep=4&ilang=%lang%')\">Zurück</a>");

  /* Schritt 6 */    
  define("L_INSTALL_FINISHED","Installation abgeschlossen"); 
  define("L_INSTALL_FINWELCOME","Herzlichen Gl&uuml;ckwunsch!"); 
  define("L_INSTALL_FINTXT","Du hast FanPress jetzt installiert und kannst mit dem System arbeiten! ^^"); 
  define("L_INSTALL_FINTOLOGIN","<a href=\"javascript:location.replace('../login.php')\">Klicke hier</a>, um dich einzuloggen."); 

  define("L_INSTALL_NOTICES","Hinweise");   
  define("L_DELETE_INSTALL_DIR", "<b>L&ouml;sche</b> den Ordner <b>install</b> im FanPress-Ordner auf deinem Webspace.");
?>
