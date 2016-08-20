<?php
    if(!isset($_POST['ajax']) && !defined("LOGGED_IN_USER")) { die(); }
    
    if(isset($_POST['ajax']) && isset($_POST['fmmode'])) {            
        $fileManagerMode = (int) $_POST['fmmode'];
    }    
    
    if(isset($_POST['ajax'])) {        
        header("Content-Type: text/html; charset=iso-8859-1");
        
        if($fileManagerMode == 1 || isset($_POST['ajax'])) {
            define('NO_HEADER','');
        }
        
        require_once dirname(dirname(__DIR__)).'/inc/acpcommon.php';
        
        if(LOGGED_IN_USER && !fpSecurity::checkPermissions ("upload")) {
            die();
        }
        
        $fupl = array();
    }
    
    $uploaders = array();
?>
<form action="<?php print FP_ROOT_DIR; ?>acp/filemanager.php?mode=<?php print $fileManagerMode; ?>" method="POST" enctype="multipart/form-data">
    <table width="100%" border="0">
        <tr>
            <td class="tdheadline"></td>
            <td class="tdheadline"></td>
            <td class="tdheadline"></td>
            <td class="tdheadline" style="width:75px;"><input type="checkbox" id="fileselectboxall" value=""></td>         
        </tr>
        <?php foreach($fpFileSystem->getFileStore() AS $row) : ?>

        <?php
            if(!isset($uploaders[$row->uploaderid])) $uploaders[$row->uploaderid] = new fpAuthor($row->uploaderid);
            
            $uploader = $uploaders[$row->uploaderid];
        
            $fileExists = file_exists(FPUPLOADFOLDER.$row->filename);
        
            if(strlen($row->filename) > 30) {
                $newShortedFileNameArray = explode(".", $row->filename);
                $newShortedFileNameArray[0] = substr($newShortedFileNameArray[0],0,24)."...";
                $filenamestring = implode(".", $newShortedFileNameArray);
            } else {
                $filenamestring = $row->filename;
            }

            if($fileExists) {
                $fileResInfo = $fpFileSystem->getImageInfo($row->filename);
                $resolutionString = fpLanguage::replaceTextPlaceholder(
                        LANG_UPLOAD_FILERES, 
                        array(
                            "%res_x%" => $fileResInfo['width'],
                            "%res_y%" => $fileResInfo['height']
                        )
                );
                
                if(file_exists(FPUPLOADFOLDER."thumb_".$row->filename)) {
                    $thumbResInfo = $fpFileSystem->getImageInfo("thumb_".$row->filename);
                }
            } else  {
                $fileResInfo = array(0,0);
                $resolutionString = fpLanguage::returnLanguageConstant(LANG_UPLOAD_FILENDELETED);
            }
            
            if(!file_exists(FPFMGRTHUMBS.'/thumb_'.$row->filename)) {
                $fpFileSystem->createImageThumbSquare($row->filename);
            }

        ?>        
        
        <tr class="upload-file-list <?php if(in_array($row->filename, $fupl)) : ?>upload-file-list-highlight<?php endif; ?>">
            <td class="tdtcontent fp-file-list-thumb">
            <?php if(file_exists(FPFMGRTHUMBS.'/thumb_'.$row->filename)) : ?>                    
                <a href="<?php print FPUPLOADFOLDERURL.$row->filename; ?>" target="_blank" class="fp-file-list-img" rel="fp-file-list-img">
                    <img src="<?php print dirname(FPUPLOADFOLDERURL).'/'.basename(FPFMGRTHUMBS).'/thumb_'.$row->filename; ?>" width="100" height="100" title="<?php print $row->filename; ?>">
                </a>
            <?php else : ?>                
                <img src="<?php print FP_ROOT_DIR; ?>img/fmgr_thumb_dummy.png" width="100" height="100" title="">                
            <?php endif; ?>
            </td>
            <td class="tdtcontent fp-file-list-actions">
                <?php if(file_exists(FPUPLOADFOLDER.'thumb_'.$row->filename)) : ?>
                <a href="<?php print FPUPLOADFOLDERURL; ?>thumb_<?php print $row->filename; ?>" class="fp-file-list-link-thumb-thumb fp-ui-button-filelist" target="_blank"><?php fpLanguage::printLanguageConstant(LANG_UPLOAD_OPENTHUMB); ?></a>
                <?php endif; ?>
                <a href="<?php print FPUPLOADFOLDERURL.$row->filename; ?>" target="_blank" class="fp-ui-button-filelist fp-file-list-link <?php if(!$fileExists) : ?>important-notice-text<?php endif; ?>" rel="fp-file-list-link"><?php fpLanguage::printLanguageConstant(LANG_UPLOAD_OPENFILE); ?></a>
                <?php if($fileManagerMode == 1) : ?>
                <a href="<?php print FPUPLOADFOLDERURL; ?>thumb_<?php print $row->filename; ?>" imgtxt="<?php print basename($row->filename); ?>" class="fp-tinymce-insert-thumb fp-ui-button-filelist"><?php fpLanguage::printLanguageConstant(LANG_UPLOAD_INSERTPATH_THUMB); ?></a>
                <a href="<?php print FPUPLOADFOLDERURL.$row->filename; ?>" imgtxt="<?php print basename($row->filename); ?>" class="fp-tinymce-insert-full fp-ui-button-filelist"><?php fpLanguage::printLanguageConstant(LANG_UPLOAD_INSERTPATH); ?></a>
                <?php endif; ?>                
            </td>            
            <td class="tdtcontent fp-file-list-meta">
                <strong><?php fpLanguage::printLanguageConstant(LANG_UPLOAD_UPLOADDATE); ?>:</strong> <?php print date(fpConfig::get('timedate_mask'),$row->uploadtime); ?><br>
                <strong><?php fpLanguage::printLanguageConstant(LANG_UPLOAD_UPLOADER); ?>:</strong> <?php print htmlspecialchars($uploader->getDisplayName(), ENT_COMPAT | ENT_HTML401, FPSPECIALCHARSET); ?><br>
                <?php print $resolutionString; ?>
            </td>

            <td class="tdtcontent fp-file-list-checkboxes"><input type="checkbox" class="fileselectbox" name="delfile[]" value="<?php print $row->id; ?>"></td>
        </tr>      
        <?php endforeach; ?>

    </table> 
    
    <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
        <?php include FPBASEDIR.'/acp/filemanager/buttons.php'; ?>
    </div>
    
</form>