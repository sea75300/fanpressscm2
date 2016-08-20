<?php
    /**
     * HTML-Editor
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */

    if(!LOGGED_IN_USER) { die(); }
    
    if(!isset($iseditmode)) $iseditmode = 0;
    if(!isset($ntitle))     $ntitle     = '';
    if(!isset($frmempty))   $frmempty   = FALSE;
    
?>
    <script type="text/javascript" src="<?php print FP_ROOT_DIR ?>acp/editor/editor.js"></script>
    <script type="text/javascript" src="<?php print FP_ROOT_DIR ?>inc/lib/leela-colorpicker/leela.colorpicker-1.0.2.jquery.min.js"></script>
    
    <link rel="stylesheet" href="<?php print FP_ROOT_DIR ?>inc/lib/codemirror/lib/codemirror.css">
    <link rel="stylesheet" href="<?php print FP_ROOT_DIR ?>inc/lib/codemirror/theme/mdn-like.css">
    <link rel="stylesheet" href="<?php print FP_ROOT_DIR ?>inc/lib/codemirror/addon/hint/show-hint.css">

    <?php foreach (fpView::$cmCssFiles as $cmCssFile) : ?>    
    <link rel="stylesheet" href="<?php print FP_ROOT_DIR ?>inc/lib/codemirror/<?php print $cmCssFile; ?>">
    <?php endforeach; ?>    
    
    <?php foreach (fpView::$cmJsFiles as $cmJsFile) : ?>    
    <script type="text/javascript" src="<?php print FP_ROOT_DIR ?>inc/lib/codemirror/<?php print $cmJsFile; ?>"></script>    
    <?php endforeach; ?>
    
    <?php include_once "init.php";  ?>
 
    <?php if (isset($_GET["fn"]) == "edit") : ?>      
        <form method="post" action="editnews.php?fn=edit&nid=<?php print $fpNewsPost->getId(); ?>" name="nform" class="editor_form" accept-charset="ISO-8859-1">
    <?php else : ?>
        <form method="post" action="addnews.php" name="nform" class="editor_form" accept-charset="ISO-8859-1">
    <?php endif; ?>
    
    <?php if ($iseditmode == 1) : ?>    
        <?php include FPBASEDIR.'/acp/editor/usermeta.php'; ?>
    <?php endif; ?>                
      
    <div class="editor_title">
        <input type="text" name="titel" maxlength="255" value="<?php print htmlspecialchars_decode(fpSecurity::Filter5($ntitle)); ?>">
    </div>

    <?php include FPBASEDIR.'/acp/editor/categories.php'; ?>
            
    <div class="editorbtn ui-widget-content ui-corner-all ui-state-normal">        
        <div class="fp-buttonset fp-buttonset-select">
            <?php if(fpConfig::hasTinyMceCss()) : ?>            
            <div class="editor-select-box">
                <button class="fp-ui-button editor-select-button" id="styles"><?php fpLanguage::printLanguageConstant(LANG_EDITOR_SELECTSTYLES); ?></button>
                <div class="editor-select">
                    <ul class="smenu">
                    <?php
                        $customeStyleFileContent = file(FPBASEDIR.'/inc/tiny_mce.custom.css');

                        foreach ($customeStyleFileContent as $customeStyle) :
                            if(!empty($customeStyle) && strpos($customeStyle, '.') !== false && strpos($customeStyle, '{}') !== false) :
                                $customeStyle = str_replace(array('.', '{}'), '', trim($customeStyle));

                                print "<li onclick=\"insert(' class=&quot;$customeStyle&quot;','');return false;\"><a>$customeStyle</a></li>";

                            endif;
                        endforeach;
                    ?>                     
                    </ul>              
                </div>                
            </div>            
            <?php endif; ?>
            
            <div class="editor-select-box">
                <button class="fp-ui-button editor-select-button" id="paragraphs"><?php fpLanguage::printLanguageConstant(LANG_EDITOR_PARAGRAPH); ?></button>
                <div class="editor-select">
                    <ul class="smenu">
                        <li class="fp-editor-html-click" htmltag="p"><a>Paragraph</a></li>
                        <li class="fp-editor-html-click" htmltag="h1"><a><?php fpLanguage::printLanguageConstant(LANG_EDITOR_PARAGRAPH_HEADLINE); ?> 1</a></li>
                        <li class="fp-editor-html-click" htmltag="h2"><a><?php fpLanguage::printLanguageConstant(LANG_EDITOR_PARAGRAPH_HEADLINE); ?> 2</a></li>
                        <li class="fp-editor-html-click" htmltag="h3"><a><?php fpLanguage::printLanguageConstant(LANG_EDITOR_PARAGRAPH_HEADLINE); ?> 3</a></li>
                        <li class="fp-editor-html-click" htmltag="h4"><a><?php fpLanguage::printLanguageConstant(LANG_EDITOR_PARAGRAPH_HEADLINE); ?> 4</a></li>
                        <li class="fp-editor-html-click" htmltag="h5"><a><?php fpLanguage::printLanguageConstant(LANG_EDITOR_PARAGRAPH_HEADLINE); ?> 5</a></li>
                        <li class="fp-editor-html-click" htmltag="h6"><a><?php fpLanguage::printLanguageConstant(LANG_EDITOR_PARAGRAPH_HEADLINE); ?> 6</a></li>
                        <li class="fp-editor-html-click" htmltag="pre"><a>pre</a></li>  
                        <?php fpModuleEventsAcp::runOnAddEditorParagraph(); ?>                      
                    </ul>              
                </div>                
            </div>

            <div class="editor-select-box">
                <button class="fp-ui-button editor-select-button" id="fontsizes"><?php fpLanguage::printLanguageConstant(LANG_EDITOR_SELECTFS); ?></button>
                <div class="editor-select">
                    <ul class="smenu">
                        <li class="fp-editor-html-fontsize" htmltag="8"><a>8pt</a></li>
                        <li class="fp-editor-html-fontsize" htmltag="9"><a>9pt</a></li>
                        <li class="fp-editor-html-fontsize" htmltag="10"><a>10pt</a></li>
                        <li class="fp-editor-html-fontsize" htmltag="12"><a>12pt</a></li>
                        <li class="fp-editor-html-fontsize" htmltag="14"><a>14pt</a></li>
                        <li class="fp-editor-html-fontsize" htmltag="16"><a>16pt</a></li>
                        <?php fpModuleEventsAcp::runOnAddEditorFontSize(); ?>                     
                    </ul>              
                </div>                
            </div>
        </div>
        
        <div class="fp-buttonset fp-buttonset-buttons">
            <button title="Bold (Ctrl + B)" class="fp-editor-html-click" htmltag="b"><i class="fa fa-bold"></i></button>
            <button title="Italic (Ctrl + I)" class="fp-editor-html-click" htmltag="i"><i class="fa fa-italic"></i></button>
            <button title="Underline (Ctrl + U)" class="fp-editor-html-click" htmltag="u"><i class="fa fa-underline"></i></button>
            <button title="Strike (Ctrl + O)" class="fp-editor-html-click" htmltag="s"><i class="fa fa-strikethrough"></i></button>
            <button title="Color (Ctrl + Shift + F)" id="editor_link_insertcolor" onclick="return false;"><i class="fa fa-tint"></i></button>
            <button title="Superscript (Ctrl + Y)" class="fp-editor-html-click" htmltag="sup"><i class="fa fa-superscript"></i></button>
            <button title="Subscript (Ctrl + Shift + Y)" class="fp-editor-html-click" htmltag="sub"><i class="fa fa-subscript"></i></button>
            <button title="Align left (Ctrl + Shift + L)" onclick="insertAlignTags('left');return false;"><i class="fa fa-align-left"></i></button>
            <button title="Align center (Ctrl + Shift + C)" onclick="insertAlignTags('center');return false;"><i class="fa fa-align-center"></i></button>
            <button title="Align right (Ctrl + Shift + R)" onclick="insertAlignTags('right');return false;"><i class="fa fa-align-right"></i></button>
            <button title="Align justify (Ctrl + Shift + J)" onclick="insertAlignTags('justify');return false;"><i class="fa fa-align-justify"></i></button>            
            <button title="List (Ctrl + .)" onclick="insertListToFrom('ul');return false;"><i class="fa fa-list-ul"></i></button>
            <button title="Numbered list (Ctrl + #)" onclick="insertListToFrom('ol');return false;"><i class="fa fa-list-ol"></i></button>            
            <button title="Quotation (Ctrl + Q)" class="fp-editor-html-click" htmltag="blockquote"><i class="fa fa-quote-left"></i></button>
            <button title="Link (Ctrl + L)" id="editor_link_insertlink"><i class="fa fa-link"></i></button>
            <button title="Picture (Ctrl + P)" id="editor_link_insertimage"><i class="fa fa-picture-o"></i></button>
            <button title="Player (Ctrl + Shift + Z)" onclick="insertPlayer();return false;"><i class="fa fa-youtube-play"></i></button>
            <button title="iFrame (Ctrl + F)" onclick="insert('<iframe src=&quot;http://&quot; class=&quot;fp-newstext-iframe&quot;>','</iframe>');return false;"><i class="fa fa-puzzle-piece"></i></button>
            <button title="More (Ctrl + M)" onclick="insertMoreArea();return false;"><i class="fa fa-plus-square"></i></button>
            <button title="Table (Ctrl + Shift + T)" id="editor_link_inserttable"><i class="fa fa-table"></i></button>
            <button title="<?php fpLanguage::printLanguageConstant(LANG_SYSCFG_SMILIES) ?> (Ctrl + Shift + E)" id="editor_link_insertsmilies"><i class="fa fa-smile-o"></i></button>

            <?php fpModuleEventsAcp::runOnAddEditorButton(); ?>
        </div>
    </div>
    <?php
        $textareacontent = "";

        if ($iseditmode == 1) {
            $textareacontent = $fpNewsPost->getContent();
        } elseif($iseditmode == 0 && $frmempty) {
            $textareacontent = $_POST["newstext"];
        }

        if($textareacontent != "") {
            $test_br1 = strpos($textareacontent,"<br>");
            $test_br2 = strpos($textareacontent,"<br />");
            if ($test_br1 !== false || $test_br2 !== false) {	  
                $textareacontent = nl2br($textareacontent);				
                $textareacontent = $fpNews->cleanPost($textareacontent);
            }  		
        }
    ?>

    <div class="editor_textarea_box editor_textarea_box_classic">
        <textarea id="newstextarea" name="newstext" class="editor-textarea editor-textarea-classic"><?php print fpSecurity::Filter3($textareacontent); ?></textarea>
    </div>         
            
    <script type="text/javascript">
        var cmColors = <?php print json_encode($editorColors); ?>;
        initCodeMirror();    
    </script>
            
    <?php include FPBASEDIR.'/acp/editor/buttons.php'; ?>

</form>