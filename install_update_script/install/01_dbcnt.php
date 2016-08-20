<?php
    /**
     * FanPress CM Installer Database
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-*2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
?>
<div>
    <form action="index.php?isstep=2&ilang=<?php print $lang; ?>" method='post'>
        <?php print L_DBSRV; ?>:<br>
        <input type="Text" name="dbsrv" value="localhost" size="50" maxlength="255"><br>
        <?php print L_DBTYPE; ?>:<br>
        <select name="dbtype" style="width:400px;">
            <option value="mysql">MySQL</option>
        </select><br>
        <?php print L_DBUSR; ?>:<br>
        <input type="Text" name="dbuser" value="" size="50" maxlength="255"><br>
        <?php print L_DBPWD; ?>:<br>
        <input type="password" name="dbpasswd" value="" size="50" maxlength="255"><br>
        <?php print L_DBNAME; ?>:<br>
        <input type="Text" name="dbn" value="" size="50" maxlength="255"><br>
        <?php print L_DBPFX; ?>:<br>
        <input type="Text" name="dbprefix" value="fpress" size="50" maxlength="255"><br>            
        <?php print L_FPDIR; ?>:<br>
        <input type="Text" name="rootdir" value="<?php print str_replace('install/index.php', '', $_SERVER['PHP_SELF']); ?>" size="50" maxlength="255"><br>                              
        <p class="buttons">
            <button class="fp-ui-button" type="submit" name="btn_dbcfg"><?php print L_BTNNEXT; ?></button>
        </p>
    </form>       
</div> 