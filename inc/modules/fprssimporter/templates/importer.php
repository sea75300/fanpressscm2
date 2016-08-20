<form action="<?php print fpModules::getModuleStdLink('fprssimporter', '', true); ?>" method="post">
    <div id="tabsGeneral">        
        <ul>
            <li><a href="#tabs-cateditor-general"><?php fpLanguage::printLanguageConstant(LANG_MOD_RSSIMPORTER_PATHRSS); ?></a></li>
        </ul>

        <div id="tabs-cateditor-general">
            <div><input type="text" name="rssfeedurl" size="50" value="http://"></div>
        </div>        
    </div>
    
    <div class="fp-editor-buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">     
        <button id="btnextmenu" class="btnloader fp-ui-button" name="btn_importstart">
            <?php print fpLanguage::returnLanguageConstant(LANG_MOD_RSSIMPORTER_START); ?>
        </button>
    </div>    
</form>