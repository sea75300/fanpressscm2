<?php
    /**
     * WYSIWYG-Editor
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
            if ($test_br1 === false || $test_br2 === false) {	  
                $textareacontent = nl2br($textareacontent);				
                $textareacontent = $fpNews->cleanPost($textareacontent);
            }  		
        }
    ?>
    <div class="editor_textarea">
        <script type="text/javascript" src="<?php print FP_ROOT_DIR ?>inc/lib/tinymce4/tinymce.min.js" ></script> 
        <script type="text/javascript">
            
            <?php
                $classNames = array(array('title' => '', 'value' => ''));
                if(fpConfig::hasTinyMceCss()) {
                    $classes = file(FPBASEDIR.'/inc/tiny_mce.custom.css');
                    foreach ($classes as $class) {
                        $trimmedClass = trim(trim($class), '{}.');
                        $classNames[] = array('title' => $trimmedClass, 'value' => $trimmedClass);
                    }
                }
            ?>
            
            var tinymceLang         = "<?php print fpConfig::get('system_lang'); ?>";
            var tinymceElements     = "~readmore";
            var tinymcePlugins      = "advlist autolink anchor charmap code fullscreen hr image insertdatetime link lists importcss media nonbreaking searchreplace table textcolor visualblocks visualchars wordcount fpcm_emoticons fpcm_readmore autoresize <?php if(defined('FPCM_TMCE_CUSTOM_PLUGINS')) print FPCM_TMCE_CUSTOM_PLUGINS; ?>";
            var tinymceToolbar      = "<?php if(fpConfig::hasTinyMceCss()) : ?>styleselect <?php endif; ?>formatselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify outdent indent | subscript superscript removeformat table | bullist numlist | fpcm_readmore hr blockquote | link unlink anchor image media | emoticons charmap insertdatetime <?php if(defined('FPCM_TMCE_CUSTOM_BUTTONS')) print "| ".FPCM_TMCE_CUSTOM_BUTTONS; ?> | undo redo searchreplace fullscreen code";
            var tinymceContentCss   = "<?php if(fpConfig::hasTinyMceCss()) : print FP_ROOT_DIR; ?>inc/tiny_mce.custom.css <?php endif; ?>";            
            var tinymceCssClasses   = <?php print json_encode($classNames); ?>;
            var tinymceLinkList     = <?php print str_replace('label', 'title', json_encode($autocompleteLinks)); ?>;
            var tinymceUploadTitle  = '<?php fpLanguage::printLanguageConstant(LANG_UPLOAD_FILEMANAGER); ?>';
            
            initTinyMce();
        </script>

        <textarea name="newstext" class="editor-textarea"><?php print stripslashes($textareacontent); ?></textarea>
    </div>  

    <?php include FPBASEDIR.'/acp/editor/buttons.php'; ?>
</form>   