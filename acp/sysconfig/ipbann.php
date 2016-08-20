<?php
 /**
   * IP Adresse sperren
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */

    if(!defined("LOGGED_IN_USER")) { die(); }

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("system")) {
        print "<h2>".fpLanguage::returnLanguageConstant(LANG_SYSCFG_IPBANN)."</h2>";
        
        if (isset($_POST["sbmiptobann"])) {
            if ($_POST["iptobann"] != "") {
                $fpSystem->setIPBanned (fpSecurity::Filter2($_POST["iptobann"]));
                fpMessages::showSysNotice(LANG_IPBANN_BANNED_MSG);
            } else {
                fpMessages::showErrorText(LANG_IPBANN_NOT_BANNED_MSG);
            }
        }
        if (isset($_POST["sbmdelbip"]) && isset($_POST["delipadd"])) {
            $fpSystem->deleteBannedIP ($_POST["delipadd"]);
        }

    $aresult = $fpSystem->getBannedIP("all");
    $rows    = $fpDBcon->fetch($aresult, true);
?>
    <div id="tabsGeneral">
        <ul>
            <li><a href="#tabs-ipban-existing"><?php fpLanguage::printLanguageConstant(LANG_IPBAN_EXIST); ?></a></li>
            <li><a href="#tabs-ipban-create"><?php fpLanguage::printLanguageConstant(LANG_IPBANN_ADRESSBANN); ?></a></li>
        </ul>
        <div id="tabs-ipban-existing">    
            <form method="post" action="sysconfig.php?mod=ipbann">
                <table width="100%" border="0">
                    <tr>
                        <td class="tdheadline" style="text-align:left;"><?php fpLanguage::printLanguageConstant(LANG_IPBANN_BANNEDIP); ?></td>
                        <td class="tdheadline" style="width:225px;"><?php fpLanguage::printLanguageConstant(LANG_IPBANN_BANNEDBY); ?></td>
                        <td class="tdheadline" style="width:150px;"><?php fpLanguage::printLanguageConstant(LANG_IPBANN_BANNEDON); ?></td>           
                        <td class="tdheadline" style="width:25px;"><?php fpLanguage::printLanguageConstant(LANG_GLOBAL_DELETE); ?></td>         
                    </tr>
                    <?php foreach($rows AS $row) : ?>
                    <tr>      
                        <td><?php print $row->ip; ?></td>
                        <td class="tdtcontent"><?php print $row->name; ?></td>
                        <td class="tdtcontent"><?php print date(fpConfig::get('timedate_mask'), $row->bann_time); ?></td>
                        <td class="tdtcontent"><input type="radio" name="delipadd" value="<?php print $row->id; ?>"></td>      
                    </tr>      
                    <?php endforeach; ?>
                </table>
                <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                    <button type="submit" name="sbmdelbip" class="btnloader fp-ui-button"><?php fpLanguage::printOk(); ?></button>
                </div>                
            </form>  
        </div>
        <div id="tabs-ipban-create">
            <form method="post" action="sysconfig.php?mod=ipbann">
                <table class="fp-ui-options">
                    <tr>
                        <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_IPBANN_ADRESSTOBANN); ?>:</td>
                        <td class="tdtcontent"><input type="text" name="iptobann" size="50" maxlength="255" value=""></td>
                    </tr>             
                </table>                 
                <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                    <button type="submit" name="sbmiptobann" class="btnloader fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_IPBANN_BANN); ?></button>
                </div>
            </form>              
        </div>
    </div>
<?php
    } else {
        if(!fpSecurity::checkPermissions("system")) { fpMessages::showNoAccess(); }
        
    }     
?>    