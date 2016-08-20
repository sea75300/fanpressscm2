<?php if(!isset($systemurl)) $systemurl = $templateParams['systemurl']; ?>
<script type="text/javascript">
    jQuery(function() {        
        jQuery("#accordionIntegration").accordion({
            header: "h3",
            heightStyle: "content"
            <?php if(isset($_POST["fpinegrationassitant_acc_open"])) : ?>
            , active: <?php print fpSecurity::Filter1($_POST["fpinegrationassitant_acc_open"]); ?>
            <?php endif; ?>
        });         
    });            
</script>

<p class="ui-widget-content ui-corner-all ui-state-normal" style="padding: 0.3em;">
    <?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_HEADLINE); ?>
</p>

<div id="accordionIntegration" class="options">
    <h3><?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_SHOWNEWS); ?></h3>
    <div>
        <p><?php fpLanguage::printLanguageConstant(str_replace("%file%", $systemurl, LANG_MOD_INTEGRATEASSITANT_SHOWNEWS_DESCR)); ?></p>
        <p class="htmlcode ui-widget-content ui-corner-all ui-state-normal">
        &lt;div class="news-box"&gt;
            &lt;?php include("<?php print FP_ROOT_DIR; ?>shownews.php"); ?&gt;
        &lt;/div&gt;
        </p>
        <p><?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_SHOWNEWS_CSS); ?></p>
        <p class="htmlcode ui-widget-content ui-corner-all ui-state-normal">
            #commentformbox {}<br>
            .commentformbox_top {}<br>
            .commentformbox_btm {}<br>
            .fp-comment-smiley-row { margin:0;padding:0; }<br>
            .fp-comment-smiley-row li { display: inline;margin: 1px; }<br>
            .fp-news-img{}<br>
            .fp-category-icon {}<br>
            .fpress-pgnav{}<br>
            .fp-page-navi{}<br>
            .fp-page-navi-current{}<br>
            .fp-readmore-block{}<br>
            .fp-readmore-block-link {}<br>
            .fp-readmore-block-text {}<br>
            .fp-share-buttons { margin:0; }<br>    
            .syserror{}<br>
            .sysnotic{}<br>
            .fp-preview-notice-box {}<br>
            .fp-news-toolbar{}<br>
            .fp-acp-toolbar{}<br>
        </p>                
    </div>
    
    <?php if(fpConfig::get('usemode') == "phpinc") : ?>    
        <h3><?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_NEWSHLINTITLE); ?></h3>
        <div>
            <p><?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_ADDCODE); ?></p>
            <p><?php fpLanguage::printLanguageConstant(str_replace("%file%", $systemurl, LANG_MOD_INTEGRATEASSITANT_FPCMTOOLS)); ?></p>
            <p class="htmlcode ui-widget-content ui-corner-all ui-state-normal">
                &lt;?php include("<?php print FP_ROOT_DIR; ?>fpcm_tools.php"); ?&gt;
            </p>

            <p>
                <?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_FNDESCR); ?><br>
                <form method="post" action="">
                    <input class="fp-ui-spinner" type="text" maxlength="32" value="" name="fpinegrationassitant_descr_news">
                    <input type="hidden" name="fpinegrationassitant_acc_open" value="1">                    

                    <button class="btnloader fp-ui-button" type="submit" name="sbtn_mod_fpia_news">
                        <?php fpLanguage::printOk(); ?>
                    </button>
                </form>
            </p>

            <p><?php fpLanguage::printLanguageConstant(str_replace("%file%", $systemurl, LANG_MOD_INTEGRATEASSITANT_FUNCTIONS)); ?></p>
            <p class="htmlcode ui-widget-content ui-corner-all ui-state-normal">
                &lt;?php NewsTitle(<?php if(isset($_POST['fpinegrationassitant_descr_news']) && isset($_POST["sbtn_mod_fpia_news"])&& !empty($_POST['fpinegrationassitant_descr_news'])) { print "&quot;".fpSecurity::Filter1($_POST['fpinegrationassitant_descr_news'])."&quot;"; }  ?>); ?&gt;
            </p>            
        </div>        
        <h3><?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_PAGENAMEINTITLE); ?></h3>
        <div>
            <p><?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_ADDCODE); ?></p>
            <p><?php fpLanguage::printLanguageConstant(str_replace("%file%", $systemurl, LANG_MOD_INTEGRATEASSITANT_FPCMTOOLS)); ?></p>
            <p class="htmlcode">
                &lt;?php include("<?php print FP_ROOT_DIR; ?>fpcm_tools.php"); ?&gt;
            </p>

            <p>
                <?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_FNDESCR); ?><br>
                <form method="post" action="">
                    <input class="fp-ui-spinner" type="text" maxlength="32" value="" name="fpinegrationassitant_descr_page">
                    <input type="hidden" name="fpinegrationassitant_acc_open" value="2">

                    <button class="btnloader fp-ui-button" type="submit" name="sbtn_mod_fpia_page">
                        <?php fpLanguage::printOk(); ?>
                    </button>                
                </form>
            </p>

            <p><?php fpLanguage::printLanguageConstant(str_replace("%file%", $systemurl, LANG_MOD_INTEGRATEASSITANT_FUNCTIONS)); ?></p>
            <p class="htmlcode ui-widget-content ui-corner-all ui-state-normal">
                &lt;?php PageNumber(<?php if(isset($_POST['fpinegrationassitant_descr_page']) && isset($_POST["sbtn_mod_fpia_page"]) && !empty($_POST['fpinegrationassitant_descr_page'])) { print "&quot;".fpSecurity::Filter1($_POST['fpinegrationassitant_descr_page'])."&quot;"; }  ?>); ?&gt;
            </p>             
        </div>    
        <h3><?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_SHOWLATESTNEWS); ?></h3>
        <div>
            <p><?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_ADDCODE); ?></p>
            <p><?php fpLanguage::printLanguageConstant(str_replace("%file%", $systemurl, LANG_MOD_INTEGRATEASSITANT_FPCMTOOLS)); ?></p>
            <p class="htmlcode ui-widget-content ui-corner-all ui-state-normal">
                &lt;?php include("<?php print FP_ROOT_DIR; ?>fpcm_tools.php"); ?&gt;
            </p>

            <p>
                <?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_SHOWLATESTNEWS_COUNTNEWS); ?><br>
                <form method="post" action="">
                    <input class="fp-ui-spinner" type="text" maxlength="32" value="" name="fpinegrationassitant_descr_lastestnews">
                    <input type="hidden" name="fpinegrationassitant_acc_open" value="3">

                    <button class="btnloader fp-ui-button" type="submit" name="sbtn_mod_fpia_lastestnews">
                        <?php fpLanguage::printOk(); ?>
                    </button>                  
                </form>
            </p>            

            <p><?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_SHOWLATESTNEWS_FUNCTIONS); ?></p>
            <p class="htmlcode ui-widget-content ui-corner-all ui-state-normal">
                &lt;div class="latest-news-box"&gt;
                &lt;?php LastestNews(<?php if(isset($_POST['fpinegrationassitant_descr_lastestnews']) && isset($_POST["sbtn_mod_fpia_lastestnews"]) && !empty($_POST['fpinegrationassitant_descr_lastestnews'])) { print fpSecurity::Filter1($_POST['fpinegrationassitant_descr_lastestnews']); }  ?>); ?&gt;
                &lt;/div&gt;
            </p>  
        </div>  
        <h3><?php fpLanguage::printLanguageConstant(LANG_MOD_INTEGRATEASSITANT_RSSFEED); ?></h3>
        <div>
            <p><?php fpLanguage::printLanguageConstant(str_replace("%file%", $systemurl, LANG_MOD_INTEGRATEASSITANT_RSSFEED_DESCR)); ?></p>
            <p class="htmlcode ui-widget-content ui-corner-all ui-state-normal">
                &lt;link rel="alternate" type="application/rss+xml" title="FanPress CM" href="<?php print FP_ROOT_DIR; ?>feed.php"&gt;
            </p>
        </div>    
    <?php endif; ?>
</div>