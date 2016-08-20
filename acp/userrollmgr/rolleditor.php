<?php
 /**
   * Benutzer-Editor
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */

    if(!defined("LOGGED_IN_USER")) { die(); }
?>
<form method="post" action="<?php print $_SERVER['REQUEST_URI']; ?>">
<?php if(isset($edit) && $edit) : ?>
<div id="tabsGeneral">
    <ul>
        <li><a href="#tabs-usercfg-general"><?php fpLanguage::printLanguageConstant(LANG_USR_PROFIL_GENERAL); ?></a></li>
    </ul>
<?php endif; ?>

    <div id="tabs-usercfg-general">    
        <table>
            <tr>
                <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_USR_ROLLS_NAME); ?>:</td>
                <td class="tdtcontent"><input type="text" name="newrollname" size="60" maxlength="255" value="<?php if(isset($rollName)) print $rollName; ?>"></td>
            </tr>                
        </table>
    </div>

<?php if(isset($edit) && $edit) : ?>
    </div>
<?php endif; ?>
    
    <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
        <button type="submit" name="<?php if(isset($edit) && $edit) : ?>sbmeditroll<?php else : ?>sbmnewroll<?php endif; ?>" class="btnloader fp-ui-button"><?php fpLanguage::printSave(); ?></button>
    </div>
    
    
</form>