<?php
    /**
     * FanPress CM Installer Database Check OK
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-*2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
?>
<?php if($conOK) : ?>
<form action="index.php?isstep=3&ilang=<?php print $lang; ?>" method='post'>
    <p><?php print L_CREATECONFIG_NEXT; ?></p>
    <p class="buttons">
        <button class="fp-ui-button" type="submit" name="btn_next"><?php print L_BTNNEXT; ?></button>    
    </p>
</form>
<?php else : ?>
<form action="index.php?isstep=1&ilang=<?php print $lang; ?>" method='post'>
    <p><?php print L_NODB; ?></p>
    <p class="buttons">
        <button class="fp-ui-button" type="submit" name="btn_next"><?php print L_BTNNEXT; ?></button>    
    </p>    
</form>		
<?php endif; ?>