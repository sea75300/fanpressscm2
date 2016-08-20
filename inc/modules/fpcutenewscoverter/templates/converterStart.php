<?php fpMessages::showSysNotice(LANG_MOD_FPCMCONV_NOTICE); ?>
<form action="<?php print fpModules::getModuleStdLink('fpcutenewscoverter', '', true); ?>" method="post">
    <div id="tabsGeneral">        
        <ul>
            <li><a href="#tabs-path"><?php fpLanguage::printLanguageConstant(LANG_MOD_FPCMCONV_CUTENEWSPATH); ?></a></li>
            <li><a href="#tabs-version"><?php fpLanguage::printLanguageConstant(LANG_MOD_FPCMCONV_VERSION); ?></a></li>
        </ul>

        <div id="tabs-path">
            <input type="text" name="pathtonewstxt" size="50" value="/cutenews/"> 
        </div>
        
        <div id="tabs-version">          
            <ul>
                <li><strong>1.4.x:</strong> <input type="radio" name="cnversion" value="1.4.x"></li>
                <li><strong>1.5.x:</strong> <input type="radio" name="cnversion" value="1.5.x"></li>
                <li><strong>2.0.x:</strong> <input type="radio" name="cnversion" value="2.0.x"></li>
            </ul>
        </div>        
        
    </div>
    
    <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
        <button id="btnextmenu" class="btnloader fp-ui-button" name="sbtn_startconvertion">
            <?php fpLanguage::printLanguageConstant(LANG_MOD_FPCMCONV_START); ?>
        </button>                
    </div>      
</form>