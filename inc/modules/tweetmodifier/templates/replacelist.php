<p class="ui-widget-content ui-corner-all ui-state-normal" style="padding: 0.3em;">
    <?php fpLanguage::printLanguageConstant(LANG_MOD_TWEETMOD_TPL_LIST); ?>
</p>

<form method="post" action="<?php print fpModules::getModuleStdLink('tweetmodifier', '', true); ?>">
    <div id="tabsGeneral">
        <ul>
            <li><a href="#tabs-tweetmod-manage"><?php fpLanguage::printLanguageConstant(LANG_MOD_TWEETMOD_TPL_LIST_OVERVIEW); ?></a></li>            
            <li><a href="#tabs-tweetmod-add"><?php fpLanguage::printLanguageConstant(LANG_MOD_TWEETMOD_TPL_LIST_NEW); ?></a></li>
        </ul>
        
        <div id="tabs-tweetmod-manage">
            <table class="table">
                <tr>
                    <td class="tdheadline td-left"><?php fpLanguage::printLanguageConstant(LANG_MOD_TWEETMOD_TPL_NEW_TERM_ORG); ?></td>
                    <td class="tdheadline td-right"><?php fpLanguage::printLanguageConstant(LANG_MOD_TWEETMOD_TPL_NEW_TERM_RPL); ?></td>
                    <td class="tdheadline td-delete"></td>
                </tr>                   
            <?php foreach ($terms as $key => $value) : ?>
                <tr>
                    <td><?php print $key; ?></td>
                    <td><?php print $value; ?></td>
                    <td class="td-delete"><input type="checkbox" value="<?php print $key; ?>" name="termdel[]"></td>
                </tr>                
            <?php endforeach; ?>
            </table>
            
            <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                <button id="btnextmenu" class="btnloader fp-ui-button" name="delterm">
                    <?php fpLanguage::printLanguageConstant(LANG_GLOBAL_DELETE); ?>
                </button>                
            </div>             
        </div>
        
        <div id="tabs-tweetmod-add">
            <table class="table">
                <tr>
                    <td class="tdheadline2"><b><?php fpLanguage::printLanguageConstant(LANG_MOD_TWEETMOD_TPL_NEW_TERM_ORG); ?>:</b></td>
                    <td class="tdcontent"><input name="termorg" type="text" value="" size="75" maxlength="75"></td>
                </tr>
                <tr>
                    <td class="tdheadline2"><b><?php fpLanguage::printLanguageConstant(LANG_MOD_TWEETMOD_TPL_NEW_TERM_RPL); ?>:</b></td>
                    <td class="tdcontent"><input name="termrpl" type="text" value="" size="75" maxlength="75"></td>
                </tr>                
            </table>
            <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                <button id="btnextmenu" class="btnloader fp-ui-button" name="addterm">
                    <?php fpLanguage::printSave(); ?>
                </button>                
            </div>            
        </div>        
    </div>
</form>   