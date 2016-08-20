<?php  
 /**
   * Smiley bearbeiten
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */

    if(!defined("LOGGED_IN_USER")) { die(); }

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("smilies")) {
        print "<h2>".fpLanguage::returnLanguageConstant(LANG_SYSCFG_SMILIES)."</h2>";

        if(isset($_POST["sbmdelsmilie"]) && isset($_POST["delsmilies"])) {
            if($fpFileSystem->deleteSmilies($_POST["delsmilies"])) {
                fpMessages::showSysNotice(LANG_SMILIE_DELETED);
            }
        }
        
        if(isset($_POST["addsmilie"])) {
            if(!$fpFileSystem->isFormEmpty($_POST["slcode"],$_POST["slfilename"])) {          
                if($fpFileSystem->addSmilies($_POST["slcode"],$_POST["slfilename"])) {
                    fpMessages::showSysNotice(LANG_SMILIE_ADDED);
                }
                else {
                    fpMessages::showErrorText(LANG_SMILIE_EXIST); 
                }
            }
            else {
                fpMessages::showErrorText(LANG_SMILIE_EMPTY);
            }
        }

      $rows = $fpFileSystem->getSmilies();
  ?>
    <div id="tabsGeneral">
        <ul>
            <li><a href="#tabs-smilies-existing"><?php fpLanguage::printLanguageConstant(LANG_SMILIE_EXISTING); ?></a></li>
            <li><a href="#tabs-smilies-create"><?php fpLanguage::printLanguageConstant(LANG_SMILIE_ADDSMILIE); ?></a></li>
        </ul>
        <div id="tabs-smilies-existing">
            <form method="post" action="sysconfig.php?mod=smilies">
                <table width="100%" border="0">
                    <tr>
                        <td class="tdheadline" style="width:50px;"></td>          
                        <td class="tdheadline" style="width:100px;"><?php fpLanguage::printLanguageConstant(LANG_SMILIE_CODE); ?></td>
                        <td class="tdheadline"><?php fpLanguage::printLanguageConstant(LANG_SMILIE_FILENAME); ?></td>        
                        <td class="tdheadline" style="width:50px;"><?php fpLanguage::printLanguageConstant(LANG_GLOBAL_DELETE); ?></td>         
                    </tr>
                    <?php foreach($rows AS $row) : ?>
                    <tr>      
                        <td class="tdtcontent"><img src="<?php print FP_ROOT_DIR."img/smilies/".$row->sml_filename; ?>" alt=""></td>
                        <td class="tdtcontent"><?php print htmlspecialchars($row->sml_code, ENT_COMPAT | ENT_HTML401, FPSPECIALCHARSET); ?></td>
                        <td class="tdtcontent"><?php print htmlspecialchars($row->sml_filename, ENT_COMPAT | ENT_HTML401, FPSPECIALCHARSET); ?></td>          
                        <td class="tdtcontent"><input type="checkbox" name="delsmilies[]" value="<?php print $row->id; ?>"></td>      
                    </tr>      
                    <?php endforeach; ?>  
                </table>
                <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                    <button type="submit" name="sbmdelsmilie" class="btnloader fp-ui-button"><?php fpLanguage::printOk(); ?></button>
                </div>                
            </form> 
        </div>
        <div id="tabs-smilies-create">
            <form method="post" action="sysconfig.php?mod=smilies">
                <table class="fp-ui-options">
                    <tr>
                        <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SMILIE_CODE); ?>:</td>
                        <td class="tdtcontent"><input type="text" name="slcode" size="60" maxlength="255" value=""></td>
                    </tr>
                    <tr>
                        <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SMILIE_FILENAME); ?>:</td>
                        <td class="tdtcontent">
                            <select class="fp-ui-jqselect" name="slfilename" size="10">
                                <option selected="selected"><?php fpLanguage::printSelect(); ?></option>
                            <?php
                                $files = $fpFileSystem->getDirectoryContent(FPBASEDIR."/img/smilies/");
                                foreach ($files as $file) { print "<option value=\"$file\">$file</option>\n"; }
                            ?>
                            </select>
                        </td>
                    </tr>             
                </table>                
                
                <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                    <button type="submit" name="addsmilie" class="btnloader fp-ui-button"><?php fpLanguage::printSave(); ?></button>
                </div>                 
            </form>           
        </div>        
    </div>      
<?php
    } else {
            if(!fpSecurity::checkPermissions("smilies")) { fpMessages::showNoAccess(); }
            
    }      
?>    