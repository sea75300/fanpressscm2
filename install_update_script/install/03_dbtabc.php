<?php
    /**
     * FanPress CM Installer create database tables
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-*2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    $fpInstallation->createTables();
?>
<form action="index.php?isstep=4&ilang=<?php print $lang; ?>" method="post">
    <p class="buttons">
        <button class="fp-ui-button" type="submit" name="btntbacreated"><?php print L_BTNNEXT; ?></button>    
    </p>
</form>