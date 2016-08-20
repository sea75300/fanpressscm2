<?php
 /**
   * Benutzer-Editor
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */

    if(!defined("LOGGED_IN_USER")) { die(); }
?>
    
<?php if($inprofile) : ?>
    <form method="post" action="profile.php">    
<?php elseif ($iseditmode) : ?>
    <form method="post" action="sysconfig.php?mod=users&amp;fn=edit&amp;userid=<?php print $fpAuthor->getId(); ?>">
<?php else : ?>
    <form method="post" action="sysconfig.php?mod=users">
<?php endif; ?>
                    
<?php if($iseditmode) : ?>
    <div id="tabsGeneral">
        
        <ul>
            <li><a href="#tabs-usercfg-general"><?php fpLanguage::printLanguageConstant(LANG_USR_PROFIL_GENERAL); ?></a></li>
            <li><a href="#tabs-usercfg-personal"><?php fpLanguage::printLanguageConstant(LANG_USR_PROFIL_POPTIONS); ?></a></li>
        </ul>
<?php endif; ?>

        <div id="tabs-usercfg-general">
            <table>
                <tr>
                    <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_USR_SYSUSR); ?>:</td>
                    <td class="tdtcontent">
                <?php if($inprofile) : ?>
                    <input style="width:400px" type="text" name="usrname" size="60" maxlength="255" value="<?php print $fpAuthor->getUserName(); ?>" readonly>
                <?php else : ?>    
                    <input style="width:400px" type="text" name="usrname" size="60" maxlength="255" value="<?php if ($iseditmode) { print htmlspecialchars($fpAuthor->getUserName(), ENT_COMPAT | ENT_HTML401, FPSPECIALCHARSET); } ?>">
                <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_USR_PASSWD); ?>:</td>
                    <td class="tdtcontent">
                        <input style="width:400px" type="password" name="password" size="60" maxlength="255"><br>
                        <small><?php fpLanguage::printLanguageConstant(LANG_USR_PASSWDREQUIREMENTS); ?></small>                
                    </td>
                </tr>
                <tr>
                    <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_USR_PASSWD_CONFIRM); ?>:</td>
                    <td class="tdtcontent">
                        <input style="width:400px" type="password" name="password_confirm" size="60" maxlength="255">           
                    </td>
                </tr>                
                <tr>
                    <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_USR_NAME); ?>:</td>
                    <td class="tdtcontent"><input style="width:400px" type="text" name="name" size="60" maxlength="255" value="<?php if ($iseditmode) { print htmlspecialchars($fpAuthor->getDisplayName()); } ?>"></td>
                </tr>
                <tr>
                    <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_USR_EMAIL); ?>:</td>
                    <td class="tdtcontent"><input style="width:400px" type="text" name="email" size="60" maxlength="255" value="<?php if ($iseditmode) { print htmlspecialchars($fpAuthor->getEmail()); } ?>"></td>
                </tr>
                <?php if(!$inprofile) : ?>
                <tr>
                    <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_USR_USRLEVEL); ?>:</td>
                    <td class="tdtcontent">
                        <select class="fp-ui-jqselect" name="ulvl" style="width:400px">
                        <?php        
                            $crows = $fpUser->getUsrLevels();
                            foreach($crows AS $crow) {
                                if($iseditmode && $crow->id == $fpAuthor->getUserRoll()) {
                                    print "<option value=\"".$crow->id."\" selected=\"selected\">".fpLanguage::replaceDBDataByLanguageData(htmlspecialchars_decode($crow->leveltitle), $langReplaceDataArray)."</option>";
                                }
                                elseif ($inprofile && $crow->id == $fpAuthor->getUserRoll()) {
                                    print "<option value=\"".$crow->id."\">".fpLanguage::replaceDBDataByLanguageData(htmlspecialchars_decode($crow->leveltitle), $langReplaceDataArray)."</option>";
                                } elseif(!$inprofile) {
                                    print "<option value=\"".$crow->id."\">".fpLanguage::replaceDBDataByLanguageData(htmlspecialchars_decode($crow->leveltitle), $langReplaceDataArray)."</option>";        
                                }
                            }
                        ?>            
                        </select>
                    </td>
                </tr>
                <?php endif; ?>
            </table>            
        </div>
        
        <?php if($iseditmode) : ?>
        <div id="tabs-usercfg-personal">
                
            <table>
                <tr>			
                    <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_LANG); ?>:</td>
                    <td class="tdtcontent">
                        <select class="fp-ui-jqselect" name="usrmeta[lang]" style="width:400px">
                        <?php
                            $languages = $fpLanguage->getInstalledLanguages();                                       
                            foreach ($languages as $language => $languageName) {
                                if ($fpAuthor->getUserMeta('lang') == $language) {
                                    print "<option value=\"".$language."\" selected=\"selected\">".$languageName."</option>";
                                }
                                else {                                                
                                    print "<option value=\"".$language."\">".$languageName."</option>";
                                }                                            
                            }											 
                        ?>
                        </select>					
                    </td>				 
                </tr>		
                <tr>			
                    <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_TIMEZONE); ?>:</td>
                    <td class="tdtcontent">
                        <select style="width:400px" class="fp-ui-jqselect" name="usrmeta[timezone]">
                        <?php foreach (fpSystem::getTimeZones() as $timezoneAreaName => $timezones) : ?>
                            <optgroup label="<?php fpLanguage::printLanguageConstant($timezoneAreaName); ?>">                                        
                                <?php foreach ($timezones as $timezone) : ?>
                                <option value="<?php print $timezone; ?>" <?php if($timezone == $fpAuthor->getUserMeta('timezone')) : ?>selected="selected"<?php endif; ?>><?php print $timezone; ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach;?>
                        </select>
                    </td>		
                </tr>						
                <tr>			
                    <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_DATETIMEMASK); ?>:</td>
                    <td class="tdtcontent"><input style="width:400px" id="sysdtmask" type="text" name="usrmeta[timemask]" size="40" maxlength="255" value="<?php print $fpAuthor->getUserMeta('timemask'); ?>"></td>		
                </tr>                      
            </table>            
        </div>        
        
    </div>
    <?php endif; ?>

    <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
        <?php if($iseditmode && !$inprofile) : ?>
        <a href="sysconfig.php?mod=users" class="btnloader fp-ui-button" ><?php fpLanguage::printBack(); ?></a>
        <?php endif; ?>
        <button type="submit" name="<?php if($iseditmode) : ?>sbmeusr<?php else : ?>sbmnusr<?php endif; ?>" class="btnloader fp-ui-button"><?php fpLanguage::printSave(); ?></button>
    </div>
</form>