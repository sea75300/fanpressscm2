<?php
    /**
     * FanPress CM Language package English
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    if(!defined("LOGGED_IN_USER")) { die(); }
?>
##########
Dashboard
----------
<p>Im <b>Dashboard</b> findest du verschiedene Informationen u. a. zum Update-Satus deiner FanPress CM Installation, etc. Du kannst auch
eigene Dashboard-Conatiner erzeugen. Erzeuge dazu eine neue Conatainer-Datei unter "fanpress/inc/dashcontainer" oder über das
entsprechende Modul-Event.</p>
##########
News Editor
----------
<p>Mit dem <b>News-Editor</b> kannst du Artikel schreiben und/oder bearbeiten. Hierbei hast du vielfältige Gestaltungsmöglichkeiten, welche
durch Module erweitert werden können. Du kannst einem Artikel Kategorien zuweisen, ihn "anpinnen", so dass er über allen anderen Artikeln
dargestellt wird und verschiedene weitere Einstellungen vornehmen.</p>
<p>Der News-Editor hat zwei verschiedene Ansichten:</p>
<ul>
    <li><b>WYSIWYG-Ansicht:</b><br>
        Diese basiert auf dem Editor TinyMCE 4 und zeigt dir direkt alle Formatierungen an, welche du vornimmst.
        Über den Button <i>HTML</i> in der Format-Leiste kannst du eine einfache HTML-Ansicht öffnen.
    </li>
    <li><b>HTML-Ansicht:</b><br>
        Die HTML-Ansicht ist ein reiner HTML-Editor, welcher neben verschiedenen Formatierungsmöglichkeiten u. a. auch Syntax-Hilighting
        bietet.        
    </li>
</ul>
<p>Die Ansicht des Editors kannst du in den Systemeinstellungen ändern.</p>

<p>Über den Link <i>Kurzlink</i> am oberen Kopf des News-Editors ist es bei gespeicherten Artikeln möglich, die URL über den Dienst
<a href=http://is.gd>is.gd</a> kürzen zu lassen. Der genutzte Dienst kann über ein Modul-Event geändert werden</p>

<p>Sofern es dein Host zulässt, kannst du in den Systemeinstellungen FanPress CM direkt mit Twitter verbinden. Somit werden Artikel beim
Veröffentlichen oder über das Aktions-Menü unter <i>News bearbeiten</i> direkt bei Twitter bekannt gemacht werden.</p>

<p>Um eine News zu einem bestimmten Zeitpunkt automatisch freizuschalten, öffne das <i>Erweitert</i>-Menü über den Button am unteren
Bildschirmrand. Setzte den Haken bei <i>Eintrag freischalten am </i> um wählen anschließend Datum und Uhrzeit aus.</p>

<p>Für einige Funktionen verwendet FanPress CM sogenannte <b> FanPress CM-Codes (FPCode)</b>. Dazu gehören u. a. folgende Tags:</p>
<ul>
    <li>&lt;readmore&gt; - in News standardmäßig ausgeblendeter Bereich. z. B. zum posten von Spoilern oder bei längeren Texten</li>
    <li>&lt;player&gt; - Player für Audio-Dateien wie MP3, u. ä.</li>
</ul>

<p>Der News-Editor kann am oberen Rand bis zu drei Tabs enthalten. Immer angezeigt wird der Tab <i>News-Editor</i>, welcher den Editor
an sich umfasst. Als weitere Tabs können <i>Kommentare</i> und/oder <i>Versionen</i> folgen.</p>

<p>Unter <i>Kommentare</i> erhälst du eine Auflistung aller Kommentare, welche zu zum ausgewählten Artikel geschrieben wurden. Die Liste
bietet dir die Möglichkeit, einzelne Kommentare zu löschen. Über einen Klick auf den Namen des Verfassern kannst du in einem einfachen
Editor die Kommentare bearbeiten, freischalten, auf privat setzten, etc. Den Zugriff auf die Kommentare können du über die
Berechtigungen geregelt werden.</p>

<p>Seit Version 2.4 besitzt FanPress CM ein einfaches Revisions-System, d. h. bei Änderungen wird der vorherige Zustand gesichert und
kann jederzeit wiederhergestellt werden. Die Revisionen kannst du über den Tab <i>Versionen</i> verwalten. Die Revisionen können über
die Systemeinstellungen (de)aktiviert werden.</p>
##########
News bearbeiten
----------
<p>Im Bereich <b>News bearbeiten</b> kannst findest du alle gespeicherten Artikel in FanPress CM. Über das Aktions-Menü unten rechts kannst
du verschiedene Dinge durchführen, bspw. Artikel löschen oder archivieren.</p>
<p>Über den Button <i>Suche & Filter</i> kannst du mithilfe eines Dialogs die angezeigten Artikel anhand verschiedener Kriterien
weiter eingrenzen. Über die Hauptnavigation kannst du bereits eine Vorauswahl treffen, welche Artikel dir angezeigt werden sollen.</p>
<p>Über den Button "News aufrufen" kannst du einen Artikel direkt auf deiner Seite aufrufen.</p>
##########
Dateimanager
----------
<p>Im <b>Dateimanager</b> kannst du Bilder hochladen, welche du in deinen Artikeln verwendet willst. Eine vereinfachte Ansicht lässt
sich auch direkt aus dem News-Editor heraus aufrufen. Er zeigt neben einem Vorschau-Bild noch einige zusätzliche Informationen zur
hochgeladenen Datei an.</p>
<p>Der Dateimanager bietet zwei verschiedene Modi. Einmal die klassischen Version, welche mittels HTML-Formularen arbeitet und vor allem
für ältere Browser zu empfehlen ist. Alternativ steht der - standardmäßig aktive - Dateimanager auf Basis von jQuery zu Verfügung.
Dieser bietet mehr Komfort und unterliegt weniger Beschränkungen v. a. beim Hochladen mehrerer Dateien.</p>
<p>Welcher Modus genutzt wird, kann über die Systemeinstellungen festgelegt werden.</p>
<p><b>Ich möchte ein Bild in einen Artikel einfügen, wie geht das?</b></p>
<p>Um den Pfad eines Bildes direkt in den "Bild einfügen"-Formular zu kopieren, klicke auf die Buttons
"Thumbnail-Pfad in Quelle einfügen" bzw. "Datei-Pfad in Quelle einfügen" zwischen dem Thumbnail und den Meta-Informationen des
jeweiligen Bildes, je nachdem was du nutzen möchtest.</p>
<p>Alternativ mache in der Dateiliste einen Rechtsklick auf den Bild- und/oder Thumbnail öffnen Button. Wähle nun im Kontext-Menü des
jeweiligen Browsers "Link-Adresse kopieren", "Verknüpfung kopieren", o. ä. Füge den Pfad anschließend in das Feld "Quelle" im Editor
ein. Im HTML-Editor kannst du auch einfach anfangen, den Dateinamen einzutippen. Hier öffnet sich dann eine Autovervollständigung.</p>
##########
Profil
----------
<p>Das eigene <b>Profil</b> können alle Benutzer über das Profil-Menü oben rechts aufrufen. Jeder Benutzer kann dort folgende Dinge
anpassen:</p>
<ul>
    <li><b>Passwort</b> zum Login</li>
    <li><b>Name</b> welcher in den News als Author-name angezeigt wird</li>
    <li><b>E-Mail-Adresse</b> an die bspw. ein zurückgesetztes Passwort gesendet wird</li>
    <li><b>Sprache</b> des FanPress CM Admin-Bereichs</li>
    <li><b>Zeitzone</b> welche für die Umrechnung von Zeitangaben genutzt wird</li>
    <li><b>Datum- und Zeitanzeige</b>, welche für die Darstellung von Zeitangaben genutzt wird</li>
</ul>
##########
Optionen
----------
<p>Im Bereich <b>Optionen</b> können sämtliche Einstellungen von FanPress CM verändert werden.</p>
<ul>
    <li><b>Systemeinstellungen:</b><br>
        Benutzer mit den entsprechenden Rechten können hier zentrale Einstellungen von FanPress CM ändern.
        <ul>
            <li><b>Allgemein:</b><br>
                Der Tab enthält verschiedene zentrale Einstellungen für FanPress CM.
            </li>
            <li><b>News-Editor:</b><br>
                Der Tab umfasst Einstellungen zum News-Editor und Dateimanager.
            </li>            
            <li><b>News:</b><br>
                Der Tab enthält verschiedene Einstellungen zur News-Ausgabe.
            </li>
            <li><b>Kommentare:</b><br>
                Der Tab enthält verschiedene Einstellungen zur Ausgabe von Artikel-Kommentaren und deren Verwaltung.
            </li>
            <li><b>Twitter-Verbindung:</b><br>
                Sofern dieser Tab angezeigt wird, kannst du hier die Verbindung zu Twitter aktivieren, deren Status einsehen oder
                löschen.<br>
                Um die Verbindung herzustellen, wähle "Klicke hier, um die Verbindung herzustellen.". Es öffnet sich nun ein neues Fenster,
                welches dich zu Twitter weiterleitet. Logge dich dort mit deinen Zugangsdaten ein und bestätige, dass FanPress CM auf deinen
                Twitter-Account zugreifen darf. War dies erfolgreich, wirst du zurück zu FanPress CM geleitet und eine Erfolgsmeldung wird
                ausgegeben.<br>
                Um Die Verbindung zu löschen, wähle den entsprechenden Button.
            </li>
            <li><b>Update-Status:</b><br>
                Dieser Tab zeigt dir, analog zum Dashboard-Conatiner, nochmals den Update-Status deiner FanPress CM Installtion an.
            </li>            
        </ul>
    </li>
    <li><b>Benutzer & Rollen:</b><br>
        Benutzer mit entsprechenden Rechten können Benutzer und Benutzer-Rollen anlegen und veralten.
    </li>
    <li><b>Rechte:</b><br>
        Benutzer mit entsprechenden Rechten können hier die Zugriffsrechte auf verschiedene Dinge von FanPress CM ändern und
        den Zugriff einschränken. Der Bereich sollte nur von Administratoren nutzbar sein! Der Rolle "Administrator" kann der Zugriff
        auf die Rechte-Einstellungen nicht verweigert werden.
    </li>
    <li><b>IP-Sperre:</b><br>
        Benutzer mit Rechten zur Änderung der Systemeinstellungen können hier IP-Adressen sperren oder Sperren wieder aufheben.
        (z. B. wegen Spam)
    </li>
    <li><b>Kategorien:</b><br>
        Benutzer mit entsprechenden Rechten  können hier neue Kategorien, sowie bestehende ändern oder löschen.
    </li>
    <li><b>Templates:</b><br>
        Benutzer mit entsprechenden Rechten können die Templates zur Ausgabe von Artikeln, Kommentaren, etc. bearbeiten.
        Für eine bessere Übersicht bietet der Template-Editor Syntax-Hilighting und eine Liste der verfügbaren Platzhalter.
    </li>
    <li><b>Smileys:</b><br>
        Benutzer mit den entsprechenden Rechten können die nutzbaren Smileys verwalten.
    </li>
    <li><b>Systemlog:</b><br>
        Im Bereich der Systemlogs findest du eine Auflistung aller bisherigen Benutzer-Logins, System-Meldungen von FanPress und
        Fehlermeldungen durch PHP selbst oder der Datenbank. Über den Button <i>Log-Datei leeren</i> kannst du Meldungen, etc. löschen
        lassen.
    </li>
</ul>
##########
Module
----------
<p>Im Bereich <b>Module</b> kannst du Erweiterungen installieren und verwalten, welche die Funktionalität von FanPress CM erweitern
können. Je nachdem, welche internen Events der Ersteller eines Moduls verwendet, können diese auch direkt in der Hauptnavigation
erscheinen.</p>
<p>Am kompfortabelsten kannst du Module verwalten, wenn dein Host Verbindungen zu anderen Servern zulässt (siehe Info im Dashboard).
Musst du Erweiterungen manuell installieren/aktualisieren, so klick in der Modul-Liste auf "Herunterladen". Entpacke auf deinem PC die
ZIP-Datei und lade den Ordner anschließend auf deinen Server in den Ordner "fanpress/inc/modules".</p>
<p>Du kannst Module auch über den Tab "Modul-Paket hochlanden" installieren. Wähle hierzu einfach die entsprechenden ZIP-Datei aus und
klicke auf "Datei(en) hochladen". Die Datei wird nun auf den Server geschoben und automatisch in das richtige Verzeichnis unter
"fanpress/inc/modules" entpackt. Du musst nun nur noch die install.php ausführen, sofern das Modul eine besitzt. Wähle hierzu den
entsprechenden Button im Modul-Manager.</p>
<p>Wenn du selbst ein Modul erstellen willst, installieren das fertige Module <i>FanPress CM Module Starter</i> oder schau in das
<a href="http://nobody-knows.org/download/fanpress-cm/tutorial-zum-schreiben-eines-moduls/">Tutorial</a>. Eine Dokumentation findest du
in der <a href="http://nobody-knows.org/fpupdate/doku/">Klassen-Doku</a>.</p>
##########
Cache
----------
<p>FanPress CM besitzt ein Cache-System, welche die Ladenzeiten und System-Belastung deutlich reduzieren, da Daten nicht bei jedem
Seiten-Aufruf aus der Datenbank gezogen werden müssen. Bei Aktionen, in denen der Cache-Inhalt als veraltet gilt, wird er i. d. R
automatisch geleert. Sollte dies jedoch einmal nicht geschehen, so kann du über den Punkt "Cache leeren" neben dem Profil-Menü eine
manuelle Löschung des Caches anstoßen.</p>
##########
Integration in deine Seite
----------
<p>Wie du FanPress CM auf deiner Seite verwendest, hängt davon ab wie du den Inhalt in die Seite einbindest.</p>
<p>Sofern du <i>iframes</i> verwendest, musst du einfach die Datei "shownews.php" als Quelle des iframes angeben. Trage in den
Systemeinstellungen anschließend noch die URL zu deiner CSS-Datei ein und auch dein Design wird automatisch angewandt.</p>
<p>Für <i>php-include</i> installiere und aktiviere das Modul <i>Integration Assistant</i> und folge den Anweisungen.</p>