<div class="fpcm-dashboard-conatiner">
    <div class="fpcm-dashboard-conatiner-inner fpcm-dashboard-conatiner-inner-boxes ui-widget-content ui-corner-all ui-state-normal">
        <h3 class="ui-corner-top ui-corner-all" style="margin:0;">
            <span class="fa fa-user"></span> <?php print fpLanguage::replaceTextPlaceholder(LANG_HEADER_HELLO, array('%username%' => fpConfig::currentUser('name'))); ?>
        </h3>
        <p style="line-height: 1.25em;"><?php fpLanguage::printLanguageConstant(LANG_WELCOME_TXT); ?></p>             
    </div>
</div>