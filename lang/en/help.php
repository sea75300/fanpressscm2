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
<p>The <b>Dashboard</b> is your first point into FanPress CM. Here you can get various informations like update state of your system
and so on. You can create own containers with a new file in "fanpress/inc/dashcontainer" or using the module event.</p>
##########
News Editor
----------
<p>Using the <b>news editor</b> you can create and edit articles you published on your website. The editor offers includes many ways
to style your posts. You can also save your current post as draft before publish it and so on.</p>
<p>The editor offers two views:</p>
<ul>
    <li><b>WYSIWYG:</b><br>
        This view is based on TinyMCE 4 editor and brings you an easy to use <i>what you see is what you get</i> editor.
        Use the <i>HTML</i> button in it's toolbar to open a simple HTML view.
    </li>
    <li><b>HTML:</b><br>
        This view gives you a pure html editor which includes syntax hilighting for better overview.
    </li>
</ul>
<p>You can change the editor view in system options.</p>

<p>The <i>Short link</i> options at the top of the editor allows you to short the URL of saved articles using the 
<a href=http://is.gd>is.gd</a> service. One can change the service via module event.</p>

<p>You your host allows you to connect to other servers, you can create a direct connection of FanPress CM to Twitter. If you do so,
your articles headline and link will be send on Twitter after publishing or using the action menü in the <i>Edit news</i> area.</p>

<p>If you want to publish an article on a pre-defined time, open the <i>Extended</i> menu at the bottom of the screen. Check the box
<i>Postpone to</i> and choose your prefered date and time.</p>

<p>Soem functions fo FanPress CM uses <b>FanPress CM codes (FPCode)</b>. These codes are</p>
<ul>
    <li>&lt;readmore&gt; - a area that is hidden by default if news are displayed</li>
    <li>&lt;player&gt; - an simple player for audio file like MP3, Windows Media and so on</li>
</ul>

<p>The news editor can contain up to three tabs at the top. The first tab named <i>News-Editor</i> shows the editor itself and is showed
by default. This register can be followed by <i>Comments</i> and/ or <i>Revisions</i>.</p>

<p>The <i>Comments</i> tab allows you to manage all comments of an article. Click on the name of the comment author to edit a comment.
Use the systems permissions to set access to the comment editor.</p>

<p>Version 2.4 of FanPress CM introduced a simple revisions system. Every change on an article will be saved ad can be restored by a
simple click. The <i>Revisions</i> tab allows you to manage revisions of a news post. The revision system can be enabled and disbaled
in system options.</p>
##########
Edit news
----------
<p>The <b>Edit news</b> area shows you all articles in your FanPress CM. use the action menü at the bottom right of the screen to
delete, archive, etc. your articles.</p>
<p>Click the button <i>Suche & Filter</i> to search and filter your articles by various criteria.</p>
<p>The button "Open news" allows you the directly open an article on you website.</p>
##########
Filemanager
----------
<p>The <b>Filemanager</b> let you manage images you can use in you articles. A simplyfied view is also available in the news editor. It
offers you a thumbnail of the uploaded files (if the image is bigger than "Create thumbnail if image is bigger than" in system options)
and gives you futher informations about every file.</p>
<p>The filemanager offers you two modus to upload files. The first is the classic HTML form based mode and should be used if you
run an older browser or in case of problems with the second mode. The latter one is based on jQuery framework and offers more functions
and less restriction especially when you work with mutiple files to upload.</p>
<p>The filemanager mode can be set in system options.</p>
<p><b>How to insert images into an article?</b></p>
<p>To insert an image path directly into the editor form, klick the "Insert file path into source" or "Insert thumbnail path into source"
button next to the images thumbnail, depending on what image type you want to use.</p>
<p>If that's not working, click right at the open image- and/or thumbnail button. Now select "Copy link address" in the context menu
of your browser and paste the address in the "source" field ofn the news editor. The HTML mode offers an autocompletetion in
für uploaded files.</p>
##########
Profile
----------
<p>The <b>Profile</b> area can be accessed using the profile menu button in the acp header. User can modify the following
informations:</p>
<ul>
    <li><b>Password</b> for FanPress CM login</li>
    <li><b>Name</b> which is display as author name in all news</li>
    <li><b>E-mail address</b> e. g. for password resets</li>
    <li><b>Language</b> of the admin area</li>
    <li><b>Timezone</b> for calculating time</li>
    <li><b>Date and time</b>, use to display date and time data</li>
</ul>
##########
Options
----------
<p>The <b>options</b> can be used to modify all settings of Fanpress CM.</p>
<ul>
    <li><b>System options:</b><br>
        If one has permissions, he can set all central settings of Fanpress CM.
        <ul>
            <li><b>General:</b><br>
                This register shows you central settings of FanPress CM.
            </li>
            <li><b>News-Editor:</b><br>
                This tab gives you various setting for news and file management in the editor and file manager.
            </li>             
            <li><b>News:</b><br>
                The tab includes all setting that belongs to news output.
            </li>
            <li><b>Comments:</b><br>
                The third tab shows you all setting that to configure your comment output and management.
            </li>
            <li><b>Twitter connection:</b><br>
                If this tab is shown, you can manage the direct connection to Twitter on this place.<br>
                To establish the connection to Twitter, click "Click here to connect to Twitter.". A new window will open and
                direct you to Twitter.com. Insert your account login data and confirm that FanPress CM can access your account.
                if every is fine, you will be redirected to your system and a success message appears.<br>
                Top delete the connection, click the button for deletion.
            </li>
            <li><b>Update-Status:</b><br>
                Here you find the update status of you FanPress CM system again.
            </li>            
        </ul>        
    </li>
    <li><b>User and rolls:</b><br>
        If one has permissions, he can manage system users and user rolls.
    </li>
    <li><b>Permissions:</b><br>
        If one has permissions, he can change access permissions for various parts of FanPress CM. This should be accessible for
        administrators only! The permissions to access this can't by denied to the "Administrator" roll.
    </li>
    <li><b>IP Banning:</b><br>
        If one has permissions, he can manage banned/ blocked ip addresses. (f. e. because of Spam)
    </li>
    <li><b>Categories:</b><br>
        If one has permissions, he can manage categories to use for articles.
    </li>
    <li><b>Templates:</b><br>
        If one has permissions, he can modify the templates used to display articles, comments and so on. The template editor also
        offers syntax hilighting and a list of templates replacements.
    </li>
    <li><b>Smileys:</b><br>
        If one has permissions, he can manage smileys available for news, comments, etc.
    </li>
    <li><b>System log:</b><br>
        The system log area gives you a list of old user sessions, system messages of FanPress CM as well as error messages from
        PHP and your database. To prune the logs click the button <i>Clear log file</i>.
    </li>
</ul>
##########
Modules
----------
<p>The <b>Modules</b> area let you manage extesions which extend various functions using events, so there is no need
to modify the core of FanPress CM.</p>
<p>You your host allows you to connect to other servers, you can easily install and update modules (see Dashboard info). If not,
you can install/update modules by clicking the "Download" button, unzip the ZIP-file and uplaod the module folder to
"fanpress/inc/modules".</p>
<p>You can also upload a module package via "Upload module package" tab in the module manager. Select a ZIP-file
from your computer and click "Upload file(s)". The module package will be uploaded to your server and automatically extraced to the
right place in "fanpress/inc/modules". You just have to execute the install.php if ther is one. Just click the button in module manager.</p>
<p>To create an own module, install the <i>FanPress CM Module Starter</i> module or have a look at our 
<a href="http://nobody-knows.org/download/fanpress-cm/tutorial-zum-schreiben-eines-moduls/">Tutorial</a>. A documentation can be found
in our <a href="http://nobody-knows.org/fpupdate/doku/">Class documentation</a>.</p>
##########
Cache
----------
<p>FanPress CM uses a cache system to speed up the whole system, since data doesn't need to be fetched from the database on every
load. If it's content is outdated at some time, the cache get's cleaned up automatically. You can also clear the cache using the
"Clear cache" button next to the profil menü button at the top of the screen</p>
##########
Site integration
----------
<p>How to integrate FanPress CM into you site depends on it's usage.</p>
<p>Using <i>iframes</i>, just define "shownews.php" as source of your content iframe. Enter the url of your css style file in the
system options and you can use your pre-defined styles too.</p>
<p>Using <i>php-include</i>, install and enable the <i>Integration Assistant</i> module, which will help you with further informations.</p>