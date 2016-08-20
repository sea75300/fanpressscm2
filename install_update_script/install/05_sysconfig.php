<?php
    /**
     * FanPress CM Installer default system config
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-*2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
?>  

<form method="post" action="index.php?isstep=6&ilang=<?php print $lang; ?>">
    <table class="fp-ui-options" style="width: 50%;margin: 0 auto;">  
        <tr>      
          <td class="tdheadline2" style="width:50%;"><?php fpLanguage::printLanguageConstant(L_SYS_SYSMAIL); ?>:</td>
          <td class="tdtcontent" style="width:50%;"><input type="text" name="sysmail" size="40" maxlength="255" value="<?php print $fpUser->getEmail(); ?>"></td>    
        </tr>      
        <tr>      
          <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(L_SYS_URL); ?>:</td>
          <td class="tdtcontent"><input type="text" name="sysurl" size="40" maxlength="255" value="http://<?php print $_SERVER["SERVER_NAME"]; ?>/index.php"></td>    
        </tr>  
        <tr>      
          <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(L_SYS_LANG); ?>:</td>
          <td class="tdtcontent">
            <select name="syslang">
              <?php
                      $handle = opendir ("../lang");
                      while ($datei = readdir ($handle)) {
                              $dateiinfo = pathinfo($datei);							 
                              if($datei != "." && $datei != ".." && $datei != "index.html") {
                                  if ($lang == $dateiinfo['filename']) {
                                    print "<option value=\"".$dateiinfo['filename']."\" selected=\"selected\">".file_get_contents(FPBASEDIR."/lang/".$dateiinfo['filename']."/lang.cfg")."</option>";                                      
                                  } else {
                                    print "<option value=\"".$dateiinfo['filename']."\">".file_get_contents(FPBASEDIR."/lang/".$dateiinfo['filename']."/lang.cfg")."</option>";
                                  }
                              }
                      }
                      closedir($handle);												 
              ?>
            </select>           
          </td>    
        </tr>
        <tr>
            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_TIMEZONE); ?>:</td>
            <td class="tdtcontent">
                <select name="systimezone">
                <?php foreach ($timezones as $timezoneAreaName => $timezones) : ?>
                    <optgroup label="<?php fpLanguage::printLanguageConstant($timezoneAreaName); ?>">                                        
                        <?php foreach ($timezones as $timezone) : ?>
                        <option value="<?php print $timezone; ?>" <?php if($timezone == $conf['time_zone']) : ?>selected="selected"<?php endif; ?>><?php print $timezone; ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach;?>
                </select>	
            </td>
        </tr>
        <tr>
            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_DATETIMEMASK); ?>:</td>
            <td class="tdtcontent"><input id="sysdtmask" type="text" name="sysdtmask" size="40" maxlength="255" value="d.m.Y, H:i"></td>
        </tr>
        <tr>			
            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_EDITOR); ?>:</td>
            <td class="tdtcontent">
                <select name="sys_editor">
                    <option value="standard"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_EDITOR_STD); ?></option>
                    <option value="classic"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_EDITOR_CLASSIC); ?></option>
                </select>
            </td>		
        </tr>
        <tr>			
            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_NEWUPLOADER); ?>:</td>
            <td class="tdtcontent">
                <select name="new_file_uploader">
                    <option value="0"><?php fpLanguage::printNo(); ?></option>
                    <option value="1" selected="selected"><?php fpLanguage::printYes(); ?></option>
                </select>
            </td>		
        </tr>        
        <tr>			
            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_USEMODE); ?>:</td>
            <td class="tdtcontent">
                <select name="sysusemode">
                    <option value="phpinc" id="sysm_incl">phpinclude</option>
                    <option value="iframe" id="sysm_iframe">iframe</option>
                </select>
            </td>
        </tr>
        <tr>			
            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWSSHOWLIMIT); ?>:</td>
            <td class="tdtcontent">
                <select name="news_show_limit">
                    <option value="5" id="sysm_incl">5</option>
                    <option value="10" id="sysm_iframe">10</option>
                    <option value="15" id="sysm_incl">15</option>
                    <option value="20" id="sysm_iframe">20</option>
                    <option value="25" id="sysm_iframe">20</option>
                </select>
            </td>		
        </tr>        
        <tr>      
          <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(L_SYS_ANTISPAMQUESTION); ?>:</td>
          <td class="tdtcontent"><input type="text" name="sysatsq" size="40" maxlength="255" value=""></td>    
        </tr>       
        <tr>      
          <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(L_SYS_ANTISPAMANSWER); ?>:</td>
          <td class="tdtcontent"><input type="text" name="sysatsaw" size="40" maxlength="255" value=""></td>    
        </tr>
    </table>
    
    <p class="buttons">
        <button class="fp-ui-button" type="submit" name="btn_syscfg"><?php print L_BTNNEXT; ?></button>    
    </p>     
</form>