<?php
 /**
   * Dateimanager
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */
    
    $fileManagerMode = (int) $_GET['mode'];

    if($fileManagerMode == 1) {
        define('NO_HEADER','');
    }
    
    define('FILEMANAGER', '');
    
    require_once dirname(dirname(__FILE__)).'/inc/acpcommon.php';
    
    $fupl = array();

    if($fileManagerMode == 1) {
        include FPBASEDIR.'/sysstyle/styleinit.php';
        include FPBASEDIR.'/sysstyle/jsvars.php';
    }
    
    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("upload")) {
?>
        
<?php if($fileManagerMode == 2) : ?>
    <div class="box" id="contentbox"> 
        <h1><?php fpLanguage::printLanguageConstant(LANG_UPLOAD_FILEMANAGER); ?></h1>        
<?php else : ?>    
    <body id="body" class="nospaceing nogradeint" <?php if(fpConfig::get('system_editor') == 'standard') : ?>style="margin:0.4em;"<?php endif; ?>>
<?php endif; ?>        

    <div>
<?php
        $isDeleted = array();
        
        if(isset($_POST["sbmok"]) && isset($_POST["filemngractions"])) {            
            switch ($_POST["filemngractions"]) {
                case "recrtthmbs" :
                    $files = $fpFileSystem->getDirectoryContent(FPUPLOADFOLDER);
                    foreach ($files as $file) {
                        $dateiinfo = pathinfo($file);
                        if($file == 'index.html' || strpos($file, 'thumb_') !== false) continue;  
                            $fpFileSystem->escapeFileName($file);
                            $currentFileData = $fpFileSystem->getImageInfo($file);                            
                            $fpFileSystem->createImageThumb($file, true);
                    }   
                    fpMessages::showSysNotice(LANG_UPLOAD_NEWTHUMBSCREATED);
                break;
                case "rbldfindex" :
                    $fpFileSystem->rebuildFileStore();
                    fpMessages::showSysNotice(LANG_UPLOAD_FILEINDEXREBUILD);   
                break;
                case "filedelete" :                    
                    if(isset($_POST["delfile"])) {
                        $delfiles = $_POST["delfile"];

                        for($i_df=0;$i_df<count($delfiles);$i_df++) {
                            $curfile = $delfiles[$i_df];
                            $deletefile = $fpFileSystem->deleteFromFileStore($curfile);
                            switch($deletefile[0]) {
                                case "notext" :
                                    fpMessages::writeToSysLog("file delete error file not exist ".$deletefile[1]);
                                    $isDeleted['notext'][] = $deletefile[1];
                                break;
                                case "noprm" :
                                    fpMessages::writeToSysLog("file delete error not permissions ".$deletefile[1]);
                                    $isDeleted['noprm'][] = $deletefile[1];
                                break;	
                                case "fdel":
                                    fpMessages::writeToSysLog("file delete ".$deletefile[1]);
                                    $isDeleted['fdel'][] = $deletefile[1];
                                break;
                            }
                        }
                    }

                break;
            }
        }

        if(isset($isDeleted['notext'])) fpMessages::showErrorText(LANG_UPLOAD_FILENOTEXIST."<br><small>".implode(', ', $isDeleted['notext']).'</small>');
        if(isset($isDeleted['noprm'])) fpMessages::showErrorText(LANG_UPLOAD_FILENOPERMISSIONS."<br><small>".implode(', ', $isDeleted['noprm']).'</small>');
        if(isset($isDeleted['fdel'])) fpMessages::showErrorText(LANG_UPLOAD_FILENDELETED."<br><small>".implode(', ', $isDeleted['fdel']).'</small>');

        if(isset($_POST["sbmfile"])) {  
            $uploadOk = false;
            $uploadedFiles = array();
            $notSupported = array();
            
            $files = $_FILES['datei'];
            $currentFileData = array();
            
            for($i=0;$i<count($files["tmp_name"]);$i++) {
                $currentFileData["tmp_name"] = $files["tmp_name"][$i];
                $currentFileData["type"] = $files["type"][$i];
                $currentFileData["name"] = $fpFileSystem->escapeFileName($files["name"][$i]);
                $currentFileData["error"] = $files["error"][$i];
                $currentFileData["size"] = $files["size"][$i];
                
                if (is_uploaded_file($currentFileData['tmp_name']) && $currentFileData["error"] == 0) {
                    $allowedFileTypes = array("image/jpeg", "image/jpg", "image/png", "image/gif");
                    
                    if(in_array($currentFileData['type'], $allowedFileTypes)) {
                        if(!file_exists(FPUPLOADFOLDER.$currentFileData['name'])) {
                            if(move_uploaded_file($currentFileData['tmp_name'], FPUPLOADFOLDER.$currentFileData['name'])) {

                                $fpFileSystem->createImageThumb($currentFileData['name']);

                                $fpFileSystem->addUploadToFileStore($currentFileData['name'],fpConfig::currentUser('id'));

                                if(strlen($currentFileData['name']) > 50) {
                                    $shortedFileNameArray    = explode(".", $currentFileData['name']);
                                    $shortedFileNameArray[0] = substr($shortedFileNameArray[0],0,50)."...";
                                    $shortedFileName = implode(".", $shortedFileNameArray);
                                } else {
                                    $shortedFileName = $currentFileData['name'];
                                }

                                $uploadOk = true;
                                $fupl[] = $currentFileData['name'];                                        
                            }                            
                        } else {
                            fpMessages::writeToSysLog("file upload error file exists");
                            $uploadedFiles[] = $currentFileData['name'];
                        }
                    }
                    else {
                        fpMessages::writeToSysLog("file upload error type not supported");
                        $notSupported[] = $currentFileData['name'];
                    }        
                }
            }
            
            if($uploadOk) fpMessages::showSysNotice(LANG_PICUOLOAD_ISUPLOADEDMSG);
            if(count($uploadedFiles) > 0) fpMessages::showErrorText(LANG_PICUOLOAD_FILEEXISTST."<br><small>".implode(', ', $uploadedFiles).'</small>');
            if(count($notSupported) > 0) fpMessages::showErrorText(LANG_PICUOLOAD_TYPENOTSUPPORTED."<br><small>".implode(', ', $notSupported).'</small>');
        }
?>
        <div id="tabsGeneral" <?php if($fileManagerMode == 2) : ?>style="margin-bottom: 50px;"<?php endif; ?>>
            <ul>
                <li id="tabs-filemanager-list-id"><a href="#tabs-filemanager-list"><?php fpLanguage::printLanguageConstant(LANG_PICUOLOAD_FILE_EXIST); ?></a></li>
                <li><a href="#tabs-filemanager-upload"><?php fpLanguage::printLanguageConstant(LANG_PICUOLOAD_FILE); ?></a></li>
            </ul>

            <div id="tabs-filemanager-upload">
            <?php if(fpConfig::get('new_file_uploader')) : ?>

            <?php include_once FPBASEDIR.'/inc/lib/jqupload/uploadform.php'; ?>

            <?php else : ?>       
                 
            <?php include_once FPBASEDIR.'/acp/filemanager/upload-old-form.php'; ?>
                
            <?php endif; ?>
            </div>

            <div id="tabs-filemanager-list">
                <?php include_once FPBASEDIR.'/acp/filemanager/file-list.php'; ?>
            </div>            
        </div>

        <script type="text/javascript">
            var maxUploads = <?php print ini_get("max_file_uploads")-1; ?>;
            var fpNewsListActionConfirmMsg = '<?php fpLanguage::printLanguageConstant(LANG_EDIT_ACTION_CONFIRM_MSG); ?>';
            var fpBaseUrl = 'http://<?php print $_SERVER["HTTP_HOST"].FP_ROOT_DIR; ?>';
            var jsFmMode = '<?php print $fileManagerMode; ?>';
        </script>
    </div>
        
<?php
    } else {
        if(!fpSecurity::checkPermissions("upload")) { fpMessages::showNoAccess(); }
    }  
?>
<?php if($fileManagerMode == 2) : ?>
        </div>
    <?php include FPBASEDIR."/sysstyle/sysfooter.php"; ?>
<?php else : ?>    
    </body>
</html>              
<?php endif; ?>