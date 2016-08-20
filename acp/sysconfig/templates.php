<?php  
    /**
     * Templates bearbeiten
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */

     if(!defined("LOGGED_IN_USER")) { die(); }

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("templates")) {
?>
<h2><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_TEMPLATES); ?></h2>
<?php
        
        $newsTemplatePath        = FPBASEDIR."/styles/".fpConfig::get('active_news_template').".html";
        $commentTemplatePath     = FPBASEDIR."/styles/".fpConfig::get('active_comment_template').".html";
        $latestTemplatePath      = FPBASEDIR."/styles/latest.html";
        $commentFormTemplatePath = FPBASEDIR."/styles/comment_form.html";
        
        $notWritable = array();
        if(!is_writable($newsTemplatePath)) {
            $notWritable[] = fpConfig::get('active_news_template').".html";
        }
        if(!is_writable($commentTemplatePath)) {
            $notWritable[] = fpConfig::get('active_news_template').".html";
        }
        if(!is_writable($latestTemplatePath)) {
            $notWritable[] = "latest.html";
        }
        if(!is_writable($commentFormTemplatePath)) {
            $notWritable[] = "comment_form.html";
        }        
        
        if(count($notWritable)) {            
            fpMessages::showErrorText(LANG_TEMPLATE_UNWRITABLE.'<br><i>'.  implode(', ', $notWritable).'</i>');
        }

        if(isset($_POST["btmeditntpl"])) {       
            if(!fpTemplates::cssInTemplate ($_POST["newstemplate"])) {
                fpTemplates::saveTemplate($newsTemplatePath,$_POST["newstemplate"]);       
                    fpMessages::showSysNotice(LANG_TEMPLATE_EDITMSG);          
            } else {
                fpMessages::showErrorText(LANG_TEMPLATE_NOCSSMSG);
            }
        }

        if(isset($_POST["btmeditctpl"])) {    
            if(!fpTemplates::cssInTemplate ($_POST["commenttemplate"])) {
                fpTemplates::saveTemplate($commentTemplatePath,$_POST["commenttemplate"]);
                fpMessages::showSysNotice(LANG_TEMPLATE_EDITMSG);           
            } else {
                fpMessages::showErrorText(LANG_TEMPLATE_NOCSSMSG);
            }
        }      

        if(isset($_POST["btmeditltpl"])) {    
            if(!fpTemplates::cssInTemplate($_POST["latesttemplate"])) {
                fpTemplates::saveTemplate($latestTemplatePath,$_POST["latesttemplate"]);
                fpMessages::showSysNotice(LANG_TEMPLATE_EDITMSG);           
            } else {
                fpMessages::showErrorText(LANG_TEMPLATE_NOCSSMSG);
            }
        }
        
        if(isset($_POST["btmeditcftpl"])) {    
            if(!fpTemplates::cssInTemplate($_POST["commentformtemplate"])) {
                fpTemplates::saveTemplate($commentFormTemplatePath,$_POST["commentformtemplate"]);
                fpMessages::showSysNotice(LANG_TEMPLATE_EDITMSG);           
            } else {
                fpMessages::showErrorText(LANG_TEMPLATE_NOCSSMSG);
            }
        }        

	$newstemplate        = fpTemplates::openTemplate($newsTemplatePath);
	$commenttemplate     = fpTemplates::openTemplate($commentTemplatePath);
        $commentformtemplate = fpTemplates::openTemplate($commentFormTemplatePath);
	$latesttemplate      = fpTemplates::openTemplate($latestTemplatePath);

?>
    <?php foreach (fpView::$cmCssFiles as $cmCssFile) : ?>    
    <link rel="stylesheet" href="<?php print FP_ROOT_DIR ?>inc/lib/codemirror/<?php print $cmCssFile; ?>">
    <?php endforeach; ?>    
    
    <?php foreach (fpView::$cmJsFiles as $cmJsFile) : ?>    
    <script type="text/javascript" src="<?php print FP_ROOT_DIR ?>inc/lib/codemirror/<?php print $cmJsFile; ?>"></script>    
    <?php endforeach; ?>

    <form method="post" action="sysconfig.php?mod=templates">
        <div id="tabsGeneral">
            <ul>
                <li><a href="#tabs-template-news"><?php fpLanguage::printLanguageConstant(LANG_SYS_ACTIVENEWSTEMPLATE); ?></a></li>
                <li><a href="#tabs-template-comment"><?php fpLanguage::printLanguageConstant(LANG_SYS_ACTIVECOMMENTTEMPLATE); ?></a></li>
                <li><a href="#tabs-template-commentform"><?php fpLanguage::printLanguageConstant(LANG_SYS_COMMENTFORMTEMPLATE); ?></a></li>
                <li><a href="#tabs-template-latestnews"><?php fpLanguage::printLanguageConstant(LANG_SYS_LATESTNEWSTEMPLATE); ?></a></li>
            </ul>
            
            <div id="tabs-template-news">
                <div class="templatereplacements ui-widget-content ui-corner-all ui-state-normal">
                    <p><b><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_REPLACEMENTS); ?>:</b></p>
                    <p style="margin-bottom:5px;"><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_NOCSS); ?>:</p>
                    <dl>
                            <dt class="tplreplmt">%news_headline%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_HEADLINE); ?></dd>  
                            <dt class="tplreplmt">%news_text%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_TEXT); ?></dd>         
                            <dt class="tplreplmt">%author%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_AUTHOR); ?></dd>      
                            <dt class="tplreplmt">%category%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_CAT); ?></dd>      
                            <dt class="tplreplmt">%caticon%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_CATICON); ?></dd>
                            <dt class="tplreplmt">%date%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_DATE); ?></dd>      
                            <dt class="tplreplmt">%edited%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_EDITDATE); ?></dd>
                            <dt class="tplreplmt">%comment_count%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMMENTCOUNT); ?></dd>
                            <dt class="tplreplmt">%commemts%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMMENTS); ?></dd>
                            <dt class="tplreplmt">%sharebuttons%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_SHAREBUTTONS); ?></dd>
                            <dt class="tplreplmt">[newslink] &amp; [/newslink]</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_NEWSLINK); ?></dd>
                            <dt class="tplreplmt">%status_pinned%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_NEWSLINK); ?></dd>
                    </dl>
                </div>
                <div class="editor_textarea">
                    <textarea id="newstemplate" class="template_editor" name="newstemplate" cols="70" rows="15"><?php print stripslashes($newstemplate); ?></textarea>
                </div> 
                <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                    <button type="submit" name="btmeditntpl" class="btnloader fp-ui-button"><?php fpLanguage::printSave(); ?></button>
                </div>                
            </div>

            <div id="tabs-template-comment">
                <div class="templatereplacements ui-widget-content ui-corner-all ui-state-normal">
                    <p><b><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_REPLACEMENTS); ?>:</b></p>
                    <p style="margin-bottom:5px;"><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_NOCSS); ?>:</p>
                    <dl>
                            <dt class="tplreplmt">%author%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMAUTHOR); ?></dd>      
                            <dt class="tplreplmt">%comment_text%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMTEXT); ?></dd>       
                            <dt class="tplreplmt">%email%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMAEMAIL); ?></dd>
                            <dt class="tplreplmt">%urle%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMAURL); ?></dd>
                            <dt class="tplreplmt">%date%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMDATE); ?></dd>
                            <dt class="tplreplmt">%comment_num%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMCOUNT); ?></dd>
                            <dt class="tplreplmt">[mention] &amp; [/mention]</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMMENTION); ?></dd>
                    </dl> 
                </div>		
                <div class="editor_textarea">
                    <textarea id="commenttemplate" class="template_editor" name="commenttemplate" cols="70" rows="15"><?php print stripslashes($commenttemplate); ?></textarea>
                </div>  
                <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                    <button type="submit" name="btmeditctpl" class="btnloader fp-ui-button"><?php fpLanguage::printSave(); ?></button>
                </div>                 
            </div>

            <div id="tabs-template-commentform">
                <div class="templatereplacements ui-widget-content ui-corner-all ui-state-normal">
                    <p><b><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_REPLACEMENTS); ?>:</b></p>
                    <p style="margin-bottom:5px;"><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_NOCSS); ?>:</p>
                    <dl>
                        <dt class="tplreplmt">%comment_headline%</dt>
                            <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_HEADLINE); ?></dd>  
                        <dt class="tplreplmt">%comment_form_url%</dt>
                            <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMMENTURL); ?></dd>                        
                        <dt class="tplreplmt">%comment_name_description% & %comment_name%</dt>
                            <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMAUTHOR); ?></dd>      
                        <dt class="tplreplmt">%comment_text_area%</dt>
                            <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMTEXT); ?></dd>       
                        <dt class="tplreplmt">%comment_text_tags%</dt>
                            <dd><?php fpLanguage::printLanguageConstant(LANG_COMMENT_ALLOWED_TAGS); ?></dd>
                        <dt class="tplreplmt">%comment_email_description% & %comment_email%</dt>
                            <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMAEMAIL); ?></dd>
                        <dt class="tplreplmt">%comment_url_description% & %comment_url%</dt>
                            <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMAURL); ?></dd>
                        <dt class="tplreplmt">%smiley_list_description% & %smiley_list%</dt>
                            <dd><?php fpLanguage::printLanguageConstant(LANG_COMMENT_SMILIES); ?></dd>
                        <dt class="tplreplmt">%comment_antispam_plugin%</dt>
                            <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_ANTISPAM); ?></dd>
                        <dt class="tplreplmt">%comment_private_button%</dt>
                            <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_PRIVATE); ?></dd>                            
                        <dt class="tplreplmt">%comment_submit_button% + %comment_reset_button%</dt>
                            <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_BUTTONS); ?></dd>
                    </dl>
                </div>
                <div class="editor_textarea">
                    <textarea id="commentformtemplate" class="template_editor" name="commentformtemplate" cols="70" rows="15"><?php print stripslashes($commentformtemplate); ?></textarea>
                </div> 
                <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                    <button type="submit" name="btmeditcftpl" class="btnloader fp-ui-button"><?php fpLanguage::printSave(); ?></button>
                </div>                
            </div>            

            <div id="tabs-template-latestnews">
                <div class="templatereplacements ui-widget-content ui-corner-all ui-state-normal">
                    <p><b><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_REPLACEMENTS); ?>:</b></p>
                    <p style="margin-bottom:5px;"><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_NOCSS); ?>:</p>
                    <dl>
                            <dt class="tplreplmt">%news_headline%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_HEADLINE); ?></dd>           
                            <dt class="tplreplmt">%author%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_AUTHOR); ?></dd>      
                            <dt class="tplreplmt">%date%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_DATE); ?></dd>      
                            <dt class="tplreplmt">%comment_count%</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_COMMENTCOUNT); ?></dd>
                            <dt class="tplreplmt">[newslink] &amp; [/newslink]</dt>
                                    <dd><?php fpLanguage::printLanguageConstant(LANG_TEMPLATE_NEWSLINK); ?></dd>
                    </dl>
                </div>
                <div class="editor_textarea">
                    <textarea id="latesttemplate" class="template_editor" name="latesttemplate" cols="70" rows="15"><?php print stripslashes($latesttemplate); ?></textarea>
                </div> 
                <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                    <button type="submit" name="btmeditltpl" class="btnloader fp-ui-button"><?php fpLanguage::printSave(); ?></button>
                </div>                
            </div>            
        </div>
    </form>
    
    <script type="text/javascript">         
        initCodeMirrorTA("newstemplate");
        initCodeMirrorTA("commenttemplate");
        initCodeMirrorTA("commentformtemplate");
        initCodeMirrorTA("latesttemplate");
        
        function initCodeMirrorTA(id) {
            var editor = CodeMirror.fromTextArea(document.getElementById(id), {
                lineNumbers: true,
                matchBrackets: true,
                lineWrapping: true,
                autoCloseTags: true,
                id: 'idtest',
                mode: "text/html",
                matchTags: {bothTags: true},
                extraKeys: {"Ctrl-Space": "autocomplete"},
                value: document.documentElement.innerHTML
            });

            editor.setOption('theme', 'mdn-like');
             
        }
    </script>
<?php
    } else {
            if(!fpSecurity::checkPermissions("templates")) { fpMessages::showNoAccess(); }
            
    }     
?>    