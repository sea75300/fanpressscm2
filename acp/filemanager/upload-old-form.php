<?php
 /**
   * Dateimanager - alter Uploader
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2014
   *
   */

    if(!defined("LOGGED_IN_USER")) { die(); }
?>
<form action="<?php print FP_ROOT_DIR; ?>acp/filemanager.php<?php if(isset($_GET['layer'])) : ?>?layer=false<?php endif; ?>" method="POST" enctype="multipart/form-data">
    <div class="fileInputList">
        <div class="fileInputListItemHidden">
            <input type="file" name="dateiHidden[]" size="50" maxlength="255" class="fileinput">
        </div>            
        <div>
        <?php
            print fpLanguage::replaceTextPlaceholder(
                LANG_UPLOAD_LIMITS, 
                array(
                    "%filescount%" => ini_get("max_file_uploads"),
                    "%filessize%" => ini_get("upload_max_filesize")
                )
            );
        ?>
        </div>
        <div class="fileInputListItem"><input type="file" name="datei[]" size="50" maxlength="255" class="fileinput fp-ui-button"></div>
    </div>  

    <div class="fileInputFormButtons fp-editor-buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
        <button class="add-new-upload-input"><?php fpLanguage::printLanguageConstant(LANG_EDITOR_ADDFILEINPUT); ?></button>  
        <button id="start-upload-input-old" type="submit" name="sbmfile" class="btnloader"><?php fpLanguage::printLanguageConstant(LANG_EDITOR_UPLOADPIC); ?></button>
    </div>                         
</form>