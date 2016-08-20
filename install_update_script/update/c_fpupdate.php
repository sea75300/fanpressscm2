<?php
    /**
     * Update-Klasse
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */
     
    final class fpUpdate {
        protected $newfpversion;
        protected $fpDBcon;

        protected $fpFS;
        
        /**
         * Konstruktur
         */
        public function __construct($fpDBcon = null) {
           
            if(!class_exists("PDO")) {
                fpMessages::showErrorText('FATAL ERROR: PHP PDO Class no found.');
            }                           
            
            if(is_null($fpDBcon)) { $this->fpDBcon  = new fpDB(); } else { $this->fpDBcon = $fpDBcon; }
            
            $this->fpFS = new fpFileSystem($this->fpDBcon);
        }            

        public function runUpdate() {
            $fpSystem = new fpSystem($this->fpDBcon);
            $system_version = $fpSystem->loadSysConfig("system_version");
            
            print "<ul>";
            print "<li>".fpLanguage::returnLanguageConstant(L_CURRENT_VERSION).": ".FPSYSVERSION."</li>\n";
            print "<li>".fpLanguage::returnLanguageConstant(L_LANGUAGE).": ".fpConfig::get('system_lang')."</li>\n";          
            print "</ul>";            

            if(version_compare($system_version,"2.3.0",">=")) {
                if (version_compare($system_version,"2.3.0","<") || (version_compare($system_version,"2.3.0","=") && defined("FPCM_SKIPVERSIONS"))) {
                    $this->printLanguageConstant(L_ADDITIONAL_STEPS.' 2.3.0');
                    $this->runAdditionalUpdateStepsTo230(); 	                                         
                } 
                
                if (version_compare($system_version,"2.3.2","<") || (version_compare($system_version,"2.3.2","=") && defined("FPCM_SKIPVERSIONS"))) {
                    $this->printLanguageConstant(L_ADDITIONAL_STEPS.' 2.3.2');
                    $this->runAdditionalUpdateStepsTo232(); 	                                         
                }    
                
                if (version_compare($system_version,"2.4.0","<") || (version_compare($system_version,"2.4.0","=") && defined("FPCM_SKIPVERSIONS"))) {
                    $this->printLanguageConstant(L_ADDITIONAL_STEPS.' 2.4.0');
                    $this->runAdditionalUpdateStepsTo240(); 	                                         
                }                   
                
                if (version_compare($system_version,"2.4.1","<") || (version_compare($system_version,"2.4.1","=") && defined("FPCM_SKIPVERSIONS"))) {
                    $this->printLanguageConstant(L_ADDITIONAL_STEPS.' 2.4.1');
                    $this->runAdditionalUpdateStepsTo241(); 	                                         
                }
                
                if (version_compare($system_version,"2.4.2","<") || (version_compare($system_version,"2.4.2","=") && defined("FPCM_SKIPVERSIONS"))) {
                    $this->printLanguageConstant(L_ADDITIONAL_STEPS.' 2.4.2');
                    $this->runAdditionalUpdateStepsTo242(); 	                                         
                }                
                
                if (version_compare($system_version,"2.5.0","<") || (version_compare($system_version,"2.5.0","=") && defined("FPCM_SKIPVERSIONS"))) {
                    $this->printLanguageConstant(L_ADDITIONAL_STEPS.' 2.5.0');
                    $this->runAdditionalUpdateStepsTo250(); 	                                         
                }
                
                if (version_compare($system_version,"2.5.1","<") || (version_compare($system_version,"2.5.1","=") && defined("FPCM_SKIPVERSIONS"))) {
                    $this->printLanguageConstant(L_ADDITIONAL_STEPS.' 2.5.1');
                    $this->runAdditionalUpdateStepsTo251(); 	                                         
                }
                
                $this->printLanguageConstant(L_UPDATE_VERSION_NUMBER);
                
                if(file_exists(dirname(dirname(__FILE__)).'/version.php')) {
                    include dirname(dirname(__FILE__)).'/version.php';
                    $this->newfpversion = $fpcmVersion;
                }
            
                $this->updateSystemConfig("system_version", $this->newfpversion);
                
                if(class_exists("fpCache")) {
                    $cacheObj = new fpCache();
                    $cacheObj->cleanup(0);                
                }
                
                print "<ul>";
                print "<li>".fpLanguage::returnLanguageConstant(L_NEW_VERSION).": ".$fpSystem->loadSysConfig("system_version")."</li>\n";        
                print "</ul>";
                
                fpMessages::writeToSysLog('Performed update to FanPress CM version '.$this->newfpversion);
            } else {                
                print "<p>".fpLanguage::returnLanguageConstant(L_UPDATE_NOTSUPPORTED)."</p>\n\n";                
            }
        }                   
        
        /**
         * zusätzliche Update auf FPCM2.1.2
         */
        private function runAdditionalUpdateStepsTo212 () {            
            $this->deleteFile("js/jquery-ui-1.9.0.custom.min.js");
            $this->deleteFile("js/jquery-1.8.2.js");
            $this->deleteFile("sysstyle/jquery-ui-1.9.1.custom.css");
            $this->deleteFile("inc/classes/tmhoauth/LICENSE");       
        }           
        
       /**
         * zusätzliche Update auf FPCM2.1.5
         */
        private function runAdditionalUpdateStepsTo215 () {
            if(file_exists(FPBASEDIR.'/acp/adminnavi.php')) $this->deleteFile('acp/adminnavi.php');
        }   
        
       /**
         * Update auf FPCM2.2.0
         */
        private function runAdditionalUpdateStepsTo220 () {            
            $this->createSystemConfig('cache_timeout', 21600);

            if(DBTYPE == 'mysql') {
                $this->alterDbTable('newsposts', 'ADD', 'comments_active tinyint(1) NOT NULL', 'AFTER editedtime');
                $this->alterDbTable('authors', 'ADD', 'usrmeta TEXT NOT NULL', 'AFTER usrlevel');
            } else {
                $this->alterDbTable('newsposts', 'ADD COLUMN', 'comments_active integer');
                $this->alterDbTable('authors', 'ADD COLUMN', 'usrmeta VARCHAR');
            }  

            $sql = "UPDATE ".FP_PREFIX."_newsposts SET comments_active = 1;";  
            $result = $this->fpDBcon->exec($sql);
            if($result === false) { $this->fpDBcon->getError(); }                       

            $this->deleteFile("/logs/fpcm_syslog.txt");
            $this->deleteFile("/logs/fpcm_phplog.txt");
        }
       
       /**
         * Update auf FPCM2.2.3
         * @param string $step
         */
        private function runAdditionalUpdateStepsTo223 () {                        
            $this->deleteFile('/inc/classes/c_tmhutilities.php');
            $this->deleteFile('/inc/classes/c_fpdb.legacy.php');
            $this->deleteFile('/inc/classes/tmhoauth/licence.txt');          
        }  
               
       /**
         * Update auf FPCM2.3.0
         */
        private function runAdditionalUpdateStepsTo230() {                        
            $this->fpFS->deleteRecursive('/inc/tiny_mce/');
            $this->fpFS->deleteRecursive('/sysstyle/nav/');
            $this->fpFS->deleteRecursive('/sysstyle/images/');
            
            $files = array(
                '/acp/parts/editor4.php',
                '/js/jquery-ui-1.10.1.custom.min.js',
                '/js/jquery.min.js',
                '/js/jquery.fancybox.pack.js',
                '/js/jquery-ui-1.10.1.custom.min.js',
                '/sysstyle/fancybox_sprite.png',
                '/sysstyle/fancybox_overlay.png',
                '/sysstyle/jquery-ui-1.10.1.custom.css',
                '/sysstyle/jquery.fancybox.css',
                '/sysstyle/fancybox_sprite.png',
                '/sysstyle/blank.gif'
            );
            
            foreach ($files as $file) { $this->deleteFile($file); }

            $this->createSystemConfig('max_img_size_x', '500');
            $this->createSystemConfig('max_img_size_y', '500');
            $this->createSystemConfig('max_img_thumb_size_x', '175');
            $this->createSystemConfig('max_img_thumb_size_y', '175');
            $this->createSystemConfig('new_file_uploader', '1');
                        
            $fpUser  = new fpUser($this->fpDBcon);            
            $fpPermission = $this->getStoredPermissions();              

            foreach($fpPermission AS $row) {
                $permissionArray = unserialize($row->permissions);                       
                switch($row->id) {
                    case 1 :
                        if(!isset($permissionArray["deletenews"])) $permissionArray["deletenews"] = 1;
                        if(!isset($permissionArray["editnewsarchive"])) $permissionArray["editnewsarchive"] = 1;
                        if(!isset($permissionArray["deletefiles"])) $permissionArray["deletefiles"] = 1;
                        if(!isset($permissionArray["newthumbs"])) $permissionArray["newthumbs"] = 1;
                    break;
                    case 2 :
                        if(!isset($permissionArray["deletenews"])) $permissionArray["deletenews"] = 0;
                        if(!isset($permissionArray["editnewsarchive"])) $permissionArray["editnewsarchive"] = 1;
                        if(!isset($permissionArray["deletefiles"])) $permissionArray["deletefiles"] = 0;
                        if(!isset($permissionArray["newthumbs"])) $permissionArray["newthumbs"] = 1;
                    break;                        
                    case 3 :
                        if(!isset($permissionArray["deletenews"])) $permissionArray["deletenews"] = 0;
                        if(!isset($permissionArray["editnewsarchive"])) $permissionArray["editnewsarchive"] = 0;
                        if(!isset($permissionArray["deletefiles"])) $permissionArray["deletefiles"] = 0;
                        if(!isset($permissionArray["newthumbs"])) $permissionArray["newthumbs"] = 1;
                    break;                        
                }

                $fpUser->updatePermissions($row->id, $permissionArray);
            }                
        }  

        /**
         * Update auf FPCM2.3.2
         */
        private function runAdditionalUpdateStepsTo232() {                        
            $this->fpFS->deleteRecursive('/img/editor/');
            $this->deleteFile('/acp/parts/stxhl.php');
        }        
        
        /**
         * Update auf FPCM2.4.0
         */        
        private function runAdditionalUpdateStepsTo240() {
            $this->createSystemConfig('revisions', 1);
            $this->createSystemConfig('sort_news', 'writtentime');
            $this->createSystemConfig('sort_news_order', 'DESC');
            
            $fpUser  = new fpUser($this->fpDBcon);            
            $fpPermission = $this->getStoredPermissions();              

            foreach($fpPermission AS $row) {
                $permissionArray = unserialize($row->permissions);                       
                switch($row->id) {
                    case 1 :
                        if(!isset($permissionArray["editallnews"])) $permissionArray["editallnews"] = 1;
                        if(!isset($permissionArray["deletecomments"])) $permissionArray["deletecomments"] = 1;
                        if(!isset($permissionArray["moduleinstall"])) $permissionArray["moduleinstall"] = 1;
                        if(!isset($permissionArray["moduleuninstall"])) $permissionArray["moduleuninstall"] = 1;
                        if(!isset($permissionArray["moduleendisable"])) $permissionArray["moduleendisable"] = 1;
                    break;
                    case 2 :
                        if(!isset($permissionArray["editallnews"])) $permissionArray["editallnews"] = 1;
                        if(!isset($permissionArray["deletecomments"])) $permissionArray["deletecomments"] = 1;
                        if(!isset($permissionArray["moduleinstall"])) $permissionArray["moduleinstall"] = 0;
                        if(!isset($permissionArray["moduleuninstall"])) $permissionArray["moduleuninstall"] = 0;
                        if(!isset($permissionArray["moduleendisable"])) $permissionArray["moduleendisable"] = 0;
                    break;                        
                    default :
                        if(!isset($permissionArray["editallnews"])) $permissionArray["editallnews"] = 0;
                        if(!isset($permissionArray["deletecomments"])) $permissionArray["deletecomments"] = 0;
                        if(!isset($permissionArray["moduleinstall"])) $permissionArray["moduleinstall"] = 0;
                        if(!isset($permissionArray["moduleuninstall"])) $permissionArray["moduleuninstall"] = 0;
                        if(!isset($permissionArray["moduleendisable"])) $permissionArray["moduleendisable"] = 0;
                    break;                        
                }

                $fpUser->updatePermissions($row->id, $permissionArray);
            }             
            
            $newFolders = array(
                'data',
                'data/logs',
                'data/cache',
                'data/upload',
                'data/revisions'
            );
            foreach ($newFolders as $folder) {
                if(!file_exists(FPBASEDIR.'/'.$folder)) {
                    print fpLanguage::returnLanguageConstant(fpLanguage::replaceTextPlaceholder(L_CREATE_ITEM, array('%itemname%' => $folder), false))."<br>";
                    if(mkdir(FPBASEDIR.'/'.$folder)) {
                        file_put_contents(FPBASEDIR.'/'.$folder.'/index.html', '');
                    } else {
                        print "FAILED!<br>";
                    }
                }
            }            
            
            
            $fpSys = new fpSystem($this->fpDBcon);
            
            if(file_exists(FPBASEDIR.'/upld/')) {
                fpMessages::showSysNotice(L_MOVEUPLOADS);
                fpMessages::showErrorText(L_UPDATE_ABBORT);
                $this->fpFS->copyRecursive(FPBASEDIR.'/upld/', FPBASEDIR.'/data/upload/');
            }            
            
            $folders = array(
                '/inc/classes/tmhoauth',
                '/inc/codemirror',
                '/inc/font-awesome',
                '/inc/jqupload',
                '/inc/tinymce4',
                'img/modmgr',
                'js/jquery_ui',
                '/cache',
                '/upld',
                '/logs'
            );
            
            $undelfiles = array();
            foreach ($folders as $folder) {
                if(file_exists(FPBASEDIR."/$folder/")) {
                    print fpLanguage::returnLanguageConstant(fpLanguage::replaceTextPlaceholder(L_DELETE_ITEM, array('%itemname%' => $folder), false))."<br>";
                    $this->fpFS->deleteRecursive(FPBASEDIR."/$folder/");
                    clearstatcache();
                    if(file_exists(FPBASEDIR."/$folder/")) {
                        $undelfiles[] = "$folder/";
                    }                    
                }
            }
            
            $files = array(
                '/img/opennews.png',
                '/sysstyle/hidenav.png',
                '/sysstyle/shownav.png',
                '/sysstyle/bg_forms.png',
                '/sysstyle/loader.gif',
                '/acp/parts/editor.js',
                '/acp/parts/editor.php',
                '/acp/parts/editor_classic.php',
                '/acp/parts/editor_buttons.php',
                '/acp/parts/editor_js_init.php',
                '/acp/parts/editor_category.php',
                '/acp/parts/usreditor.php',
                '/acp/newslist.php',
                '/acp/search.php',
                '/acp/clearcache.php',
                '/acp/sysconfig/modules.php',
                '/acp/sysconfig/profile.php',
            );
            
            foreach ($files as $file) {
                if(file_exists(FPBASEDIR.$file)) {
                    print fpLanguage::returnLanguageConstant(fpLanguage::replaceTextPlaceholder(L_DELETE_ITEM, array('%itemname%' => $file), false))."<br>";
                    $this->deleteFile($file);
                    clearstatcache();
                    if(file_exists(FPBASEDIR.$file)) {
                        $undelfiles[] = $file;
                    }
                }                
            }
            
            print "<h2 class=\"important-notice-text\">".fpLanguage::returnLanguageConstant(L_NOTICES)."</h2>\n";

            if(count($undelfiles)) {
                print "<p>".fpLanguage::returnLanguageConstant(L_DELETE_FOLDER_MAN)."</p>";
                fpMessages::showErrorText(implode('<br>', $undelfiles));
            } else {
                print "<p>-</p>";
            }            
        }    
        
        /**
         * Update auf FPCM2.4.1
         */
        private function runAdditionalUpdateStepsTo241() {    
            $fpUser  = new fpUser($this->fpDBcon);            
            $fpPermission = $this->getStoredPermissions();              

            foreach($fpPermission AS $row) {
                $permissionArray = unserialize($row->permissions);                       
                switch($row->id) {
                    case 1 :
                        if(!isset($permissionArray["moduleendisable"])) $permissionArray["moduleendisable"] = 1;
                    break;                       
                    default :
                        if(!isset($permissionArray["moduleendisable"])) $permissionArray["moduleendisable"] = 0;
                    break;                        
                }

                $fpUser->updatePermissions($row->id, $permissionArray);
            }            
            
            $this->createSystemConfig('comments_enabled_global', 1);
        } 
        
        /**
         * Update auf FPCM2.4.2
         */
        private function runAdditionalUpdateStepsTo242() {    
            $folder = FPBASEDIR.'/img/share';
            
            print fpLanguage::returnLanguageConstant(fpLanguage::replaceTextPlaceholder(L_DELETE_ITEM, array('%itemname%' => $folder), false))."<br>";
            $this->fpFS->deleteRecursive($folder);
        }
        
        /**
         * Update auf FPCM2.5.0
         */
        private function runAdditionalUpdateStepsTo250() {    
            $files = array(
                '/lib/codemirror/codemirror.js',
                '/lib/codemirror/codemirror.css',
                '/lib/codemirror/css.js',
                '/lib/codemirror/htmlembedded.js',
                '/lib/codemirror/htmlmixed.js',
                '/lib/codemirror/javascript.js',
                '/lib/codemirror/xml.js',
                '/acp/parts/showcommentacp.php',
                '/acp/editcomment.php'
            );
            
            foreach ($files as $file) {
                if(file_exists(FPBASEDIR.$file)) {
                    print fpLanguage::returnLanguageConstant(fpLanguage::replaceTextPlaceholder(L_DELETE_ITEM, array('%itemname%' => $file), false))."<br>";
                    $this->deleteFile($file);
                    clearstatcache();
                    if(file_exists(FPBASEDIR.$file)) {
                        $undelfiles[] = $file;
                    }
                }                
            }
            
            $this->alterDbTable('newsposts', 'ADD', 'is_deleted TINYINT NOT NULL', 'AFTER comments_active');
            
            $this->createSystemConfig('use_trash', 1);
            
            if(!is_dir(FPDATAFOLDER.'/fmgrthumbs')) {
                if(mkdir(FPDATAFOLDER.'/fmgrthumbs')) {                    
                    file_put_contents(FPDATAFOLDER.'/fmgrthumbs/index.html', '');
                };
            }
        }
        
        /**
         * Update auf FPCM2.5.1
         */
        public function runAdditionalUpdateStepsTo251() {
            if(!file_exists(FPBASEDIR.'/styles/comment_form.html') && is_writable(FPBASEDIR.'/styles/')) {
                include __DIR__.'/contents.php';
                file_put_contents(FPBASEDIR.'/styles/comment_form.html', base64_decode($comment_form_template));
            }
        }

        /**
         * Zentrale Funktion zum Update einer Option in der FPCM Config Tabelle
         * 
         * @param string $confname
         * @param string $configValue
         */
        private function updateSystemConfig($confname, $configValue) {
            $sql = "UPDATE ".FP_PREFIX."_config
                    SET config_value = '".$configValue."'
                    WHERE config_name LIKE '".$confname."';";

            $result = $this->fpDBcon->exec($sql);
            if($result === false) { $this->fpDBcon->getError(); }
        }
        
        /**
         * Zentrale Funktion zum Erstellen einer Option in der FPCM Config Tabelle
         * 
         * @param string $confname
         * @param string $configValue
         */
        private function createSystemConfig($confname, $configValue) { 
            $keyExist = $this->fpDBcon->count('config', '*', "config_name LIKE '".$confname."'");
            if($keyExist > 0) return;

            if(DBTYPE == 'mysql') {
                $sql = "INSERT INTO " . FP_PREFIX . "_config
                        (id, config_name, config_value)
                        VALUES (".fpDB::$queryAutoInc.", '".$confname."', '".$configValue."');
                ";    
            } else {
                $maxId = $this->fpDBcon->getMaxTableId('config');
                $sql = "INSERT INTO " . FP_PREFIX . "_config
                        (id, config_name, config_value)
                        VALUES (".($maxId + 1).", '".$confname."', '".$configValue."');
                ";
            }
            
            $result = $this->fpDBcon->exec($sql);
            if ($result === false) {
                $this->fpDBcon->getError();
                return false;
            }                                   
        }
        
        /**
         * Einzelne Datei löschen
         * @param string $filePath
         */
        private function deleteFile($filePath){    
            if(!file_exists(FPBASEDIR.$filePath)) return;
            if(!@unlink(FPBASEDIR.$filePath)) {
                fpMessages::writeToSysLog("UPDATE: unable to delete file ".$filePath." during update");
            }
        }
        
        /**
         * Language Konstante ausgeben
         * @param string $langConstant
         */
        private function printLanguageConstant($langConstant) {
            print "<p>".utf8_decode($langConstant)."</p>";
        }  
        
        /**
         * 
         * @return array
         */
        private function getStoredPermissions() {
            $sql = "SELECT prm.id, prm.author_level_id, prm.permissions, ulv.leveltitle
                    FROM ".FP_PREFIX."_permissions prm, ".FP_PREFIX."_usrlevels ulv
                    WHERE prm.author_level_id = ulv.id";
            $result = $this->fpDBcon->query($sql);	              
            $fpPermission = $this->fpDBcon->fetch($result, true);                
            
            return $fpPermission;
        }

        /**
         * Ändert Tabellenstruktur
         * @param string $table
         * @param string $methode
         * @param string $field
         * @param string $condition
         */
        private function alterDbTable($table, $methode, $field, $condition = "") {                       
            $sql = "ALTER TABLE ".FP_PREFIX."_$table $methode $field $condition";
            $result = $this->fpDBcon->exec($sql);
            if ($result === false) {
                $this->fpDBcon->getError();
                return false;
            }
        }
    }
?>