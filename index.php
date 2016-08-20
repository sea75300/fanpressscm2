<?php
    /**
     * FanPress CM Index
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    if (version_compare (phpversion(),"5.3.3","<")) {
        die("FanPress CM 2.4.0 requires PHP PHP 5.3.3 or higher. Contact your host for a newer version.<br>Your PHP Version is PHP ".phpversion());
    }

    if (file_exists(__DIR__."/inc/config.php")) {
        if (file_exists(__DIR__."/update/update.php")) {
            header("location: update/update.php");
        } else {
            header("location: login.php");
        }
    } else {
        if (file_exists(__DIR__."/install/index.php")) {
            header("location: install/index.php");
        } else {
            print "Can't find install files for FanPress! Check if you've uploaded the \"install\"-directory onto you server.";
        }
    }
?>