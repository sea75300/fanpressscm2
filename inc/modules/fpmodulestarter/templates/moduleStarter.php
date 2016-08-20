<?php
    $acpEvents = array(
        "addToHeaderCss",
        "addToHeaderJs",
        "addToNavigation",
        "onAddDashboardContainer",
        "onACPLogin",
        "onNewsSave",
        "onNewsUpdate",
        "onCommentUpdate",
        "onCreateShortLink",
        "onCreateHelpEntry",
        "onCreateTweet",
        "addToNavigationMain",
        "onAddACPMessage",
        "addEditorButton",
        "addEditorExtendedField",
        "addEditorFontSize",
        "addEditorParagraph",
        "onLinkAutocomplete",
        "onCategorySave",
        "onCategoryUpdate"
    );

    $feEvents = array (
        "feAddCss",
        "feAddJs",
        "onCommentParse",
        "onNewsSingleLoad",
        "onNewsParse",
        "onCommentSave",
        "onReplaceSpamPlugin",
        "onTestSpamAnswer",
        "onParseShareButtons"
    );
    
    sort($feEvents);
    sort($acpEvents);
?>
<form action="" method="post">
    <div id="tabsGeneral">
        <ul>
            <li><a href="#tabs-general"><?php fpLanguage::printLanguageConstant(LANG_MOD_MODULESTARTER_TAB_GENERAL); ?></a></li>
            <li><a href="#tabs-actions"><?php fpLanguage::printLanguageConstant(LANG_MOD_MODULESTARTER_TAB_ACTIONS); ?></a></li>
            <li><a href="#tabs-events"><?php fpLanguage::printLanguageConstant(LANG_MOD_MODULESTARTER_TAB_EVENTS); ?></a></li>
        </ul>
        
        <div id="tabs-general">
            <p><label><?php fpLanguage::printLanguageConstant(LANG_MOD_MODULESTARTER_MODKEY); ?></label><br>
                <input type="text" maxlength="25" size="50" name="modstarterModKey"></p>
            <p><label><?php fpLanguage::printLanguageConstant(LANG_MOD_MODULESTARTER_MODNAME); ?></label><br>
                <input type="text" maxlength="255" size="50" name="modstarterModName"></p>             
        </div>
        <div id="tabs-actions">
            <p><label><?php fpLanguage::printLanguageConstant(LANG_MOD_MODULESTARTER_MODINSTALL); ?></label><br>
               <input type="checkbox" name="modstarterInstallScript" value="1"> <?php fpLanguage::printLanguageConstant(LANG_MOD_MODULESTARTER_MODINSTALL_IN); ?><br>
               <input type="checkbox" name="modstarterUnInstallScript" value="1"> <?php fpLanguage::printLanguageConstant(LANG_MOD_MODULESTARTER_MODINSTALL_UIN); ?>
            </p>            
            
        </div>
        <div id="tabs-events">
            <p><label><?php fpLanguage::printLanguageConstant(LANG_MOD_MODULESTARTER_MODTYPE); ?></label><br>
               <input type="checkbox" id="modstarterModTypeACP" name="modstarterModType[]" value="acp"> ACP Module
               <input type="checkbox" id="modstarterModTypeFE" name="modstarterModType[]" value="fe"> FE Module
            </p>             
            <p><label><?php fpLanguage::printLanguageConstant(LANG_MOD_MODULESTARTER_MODEVENTS); ?></label></p>
            <p>
                <table class="fpmodstarterevents">
                    <tr>
                        <td>
                            <div id="fpmodstarterACPEvent">
                                <?php foreach ($acpEvents as $acpEvent) { print "<input type=\"checkbox\" name=\"modstarterModEventsACP[]\" value=\"".$acpEvent."\"> ".$acpEvent."<br>"; } ?>
                            </div>                            
                        </td>
                        <td style="width:50%;">
                            <div id="fpmodstarterFEEvent">
                                <?php foreach ($feEvents as $feEvent) { print "<input type=\"checkbox\" name=\"modstarterModEventsFE[]\" value=\"".$feEvent."\"> ".$feEvent."<br>"; } ?>         
                            </div>                             
                        </td>                        
                    </tr>
                </table>
            </p>
        </div>
    </div>
    
    <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal ui-widget-content ui-corner-all ui-state-normal">
        <button id="btnextmenu" class="btnloader fp-ui-button" name="btnModStarterCreate">
            <?php fpLanguage::printLanguageConstant(LANG_MOD_MODULESTARTER_MODCREATEBTN); ?>
        </button>                
    </div>    
</form>