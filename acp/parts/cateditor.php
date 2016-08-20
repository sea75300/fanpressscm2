<?php
 /**
   * Kategorie-Editor
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */

    if(!defined("LOGGED_IN_USER")) { die(); }
?>
                
<form method="post" action="sysconfig.php?mod=category<?php print $editActionString; ?>">      
    <?php if($iseditmode == 1) : ?>
    <div id="tabsGeneral">        
        <ul>
            <li><a href="#tabs-cateditor-general"><?php fpLanguage::printLanguageConstant(LANG_CAT_EDIT); ?></a></li>
        </ul>

        <div id="tabs-cateditor-general">
    <?php endif; ?>
            <table class="fp-ui-options">
                <tr>
                    <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_CAT_NAME); ?>:</td>
                    <td class="tdtcontent"><input type="text" name="catname" size="60" maxlength="255" value="<?php if($iseditmode == 1) { print htmlspecialchars_decode($category->getCatName()); } ?>"></td>
                </tr>
                <tr>
                    <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_CAT_ICONPATH); ?>:</td>
                    <td class="tdtcontent">
                        <input type="text" name="iconpath" size="60" maxlength="255" value="<?php if($iseditmode == 1) { print htmlspecialchars_decode($category->getIconPath()); } ?>">           
                    </td>
                </tr>
                <tr>
                    <td class="tdheadline2">
                        <?php fpLanguage::printLanguageConstant(LANG_CAT_MINLEVEL); ?>:<br>
                        <small><?php fpLanguage::printLanguageConstant(LANG_CAT_ULVL); ?></small>
                    </td>
                    <td class="tdtcontent">
                        <select class="fp-ui-jqselect" name="ulvl">
                        <?php        
                            $crows = $fpUser->getUsrLevels();
                            foreach($crows AS $crow) {
                                if($iseditmode == 1 && $crow->id == $category->getMinlevel()) {  
                                    print "<option value=\"".$crow->id."\" selected=\"selected\">".fpLanguage::replaceDBDataByLanguageData(htmlspecialchars_decode($crow->leveltitle), $langReplaceDataArray)."</option>";
                                } else {
                                    print "<option value=\"".$crow->id."\">".fpLanguage::replaceDBDataByLanguageData(htmlspecialchars_decode($crow->leveltitle), $langReplaceDataArray)."</option>";        
                                }
                            }
                        ?>            
                        </select>
                    </td>
                </tr>               
            </table> 

            <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                <?php if($iseditmode) : ?>
                <a href="sysconfig.php?mod=category" class="btnloader fp-ui-button" ><?php fpLanguage::printBack(); ?></a>
                <?php endif; ?>                
                <button type="submit" name="<?php if($iseditmode == 1) : ?>sbmecat<?php else : ?>sbmcat<?php endif; ?>" class="btnloader fp-ui-button"><?php fpLanguage::printSave(); ?></button>
            </div>
        <?php if($iseditmode == 1) : ?> 
        </div>
        <?php endif; ?>
    </div>    
</form>