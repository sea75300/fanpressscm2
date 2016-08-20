<?php
    /**
     * FanPress CM module actions
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2012-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 2.0
     */

    class fpModules { 
        
        private static $fpDBcon;
        protected static $acpModules = array();
        protected static $feModules  = array();

        /**
         * Initiiert Verbindung zu Datenbank
         * @param fpDB $fpDBcon
         * @since FPCM 2.1.3
         */
        public static function init($fpDBcon) {
            self::$fpDBcon = $fpDBcon;
        }         
        
        /**
         * Fügt Module als ACP Module in Liste hinzu
         * 
         * @param string $moduleKey
         */
        public static function addToACPModulesList($moduleKey) {            
            array_push(self::$acpModules, $moduleKey);
        }
        
        /**
         * Liefert Liste von ACP Modulen zurück
         * 
         * @return array Liste von ACP Modulen
         */
        public static function getACPModulesList() {
            return self::$acpModules;
        }

        /**
         * Fügt Module als FE Module in Liste hinzu
         * 
         * @param string $moduleKey
         */
        public static function addToFEModulesList($moduleKey) {
            array_push(self::$feModules, $moduleKey);
        }        

        /**
         * Gibt Verbindung zur Datenbank zurück
         * @return fpDB
         * @since FPCM 2.1.3
         */
        public static function getDBConnection() {
            return self::$fpDBcon;
        }

        /**
         * Liefert Liste von FE Modulen zurück
         * 
         * @return array Liste von FE Modulen
         */
        public static function getFEModulesList() {
            return self::$feModules;
        }      
        
        /**
         * Komplette Liste aller Module
         * @return array
         */
        public static function getLocalModuleList() {
            $moduleConfArray = array();            
            $handle = opendir (FPBASEDIR."/inc/modules/");
            while ($module = readdir ($handle)) {
                if(is_dir(FPBASEDIR."/inc/modules/".$module) && $module != "." && $module != ".." && $module != "index.html") {
                    $moduleConfArray[$module] = self::loadModuleConf($module);
                }
            }   
            return $moduleConfArray;
        }

        /**
         * Config-Eintrag für Modul erstellen
         * @param string $moduleKey Key des Moduls
         * @param string $configOption
         * Struktur:
         *      'config_name' => Name des Konfigurations-Wertes
         *      'config_value' => Wert des Konfigurations-Wertes
         * @since FPCM 2.0.2
         */
        public static function addModuleConfigOption($moduleKey,$configOption) {
            $fpSystem = new fpSystem(self::$fpDBcon);
            $configOption['config_name'] = "fpmod_".$moduleKey."_".$configOption["config_name"];            
            $fpSystem->createSingleSystemConfig($configOption);
            
        }

        /**
         * Config-Eintrag für Modul aktualisieren
         * @param string $moduleKey Key des Moduls
         * @param type $configOption
         * Struktur:
         *      'confname' => Name des Konfigurations-Wertes
         *      'confvalue' => Wert des Konfigurations-Wertes
         * @since FPCM 2.0.2
         */
        public static function updateModuleConfigOption($moduleKey,$configOption) {
            $configOption['confname'] = "fpmod_".$moduleKey."_".$configOption["confname"];
            
            $fpSystem = new fpSystem(self::$fpDBcon);
            $fpSystem->updateSingleSystemConfig($configOption);
            
        }        
        
        /**
         * Module-Package hochladen, muss eine ZIP-Datei sein
         * @since FPCM 2.4.0
         */
        public static function uploadModulePackage() {
            move_uploaded_file($_FILES['modulefile']['tmp_name'], FPBASEDIR.'/upgrade/'.$_FILES['modulefile']['name']);

            $unzipFolder = FPBASEDIR.'/upgrade/'.substr($_FILES['modulefile']['name'], 0, strlen($_FILES['modulefile']['name']) - 4);
            
            $fpFS = new fpFileSystem(self::$fpDBcon);
            if(!$fpFS->updaterUnzipAndCopy(FPBASEDIR.'/upgrade/'.$_FILES['modulefile']['name'], $unzipFolder, true)){
                fpMessages::showErrorText(LANG_AUTO_UPDATE_EXTRACT_ERROR);
            } else {
                fpMessages::showSysNotice(LANG_AUTO_UPDATE_EXTRACT_OK);
            }
            $fpFS->deleteRecursive($unzipFolder);
            unlink(FPBASEDIR.'/upgrade/'.$_FILES['modulefile']['name']);
            
            return true;
        }

        /**
         * Config-Eintrag für Modul löschen
         * @param string $moduleKey Key des Moduls
         * @param type $configOptionName Name des Konfigurations-Wertes
         * @since FPCM 2.0.2
         */
        public static function removeModuleConfigOption($moduleKey,$configOptionName) {
            $fpSystem = new fpSystem(self::$fpDBcon);
            $fpSystem->deleteSingleSystemConfig("fpmod_".$moduleKey."_".$configOptionName);            
        }        

        /**
         * Gibt den absoluten Pfad zum Module zurück
         * 
         * @param string $moduleKey Key des Moduls
         * @return string
         */
        public static function getModuleRootPath($moduleKey) {
            return FPBASEDIR."/inc/modules/".$moduleKey;
        }
        
        /**
         * Gibt den relativen Pfad zum Module zurück
         * 
         * @param string $moduleKey Key des Moduls
         * @return string
         */
        public static function getModuleRelPath($moduleKey) {
            return FP_ROOT_DIR."inc/modules/".$moduleKey;
        }        
        
        /**
         * Standard-Modul-Link für ACPConfig/Ausfürungs-Seite
         * @param string $moduleKey Key des Moduls
         * @param string $description Beschreibung des Links
         * @return string Standard-Modul-Link
         */
        public static function getModuleStdLink($moduleKey,$description,$linkOnly=false) {
            $link = FPBASEURLACP."modules.php?ext=$moduleKey&amp;act=open";            
            if($linkOnly) { return $link; }
            
            return "<a href=\"$link\">".$description."</a>";
        }
        
        /**
         * Lädt die Sprachdateie der Systemsprache
         * @param string $moduleKey Key des Moduls
         */
        public static function includeLanguageFile($moduleKey) {
            $fpSystem = new fpSystem(self::$fpDBcon);           
            if(file_exists(self::getModuleRootPath($moduleKey).'/lang/'.fpConfig::get('system_lang').".php")) {
                require_once self::getModuleRootPath($moduleKey).'/lang/'.fpConfig::get('system_lang').".php";
            }
        }

        /**
         * Liefert die Konfigurations des Moduls zurück
         * @param string $moduleKey Key des Moduls
         * @return array Modul-Konfiguration
         */
        public static function loadModuleConf($moduleKey) {
            if(!file_exists(FPBASEDIR."/inc/modules/".$moduleKey."/config.php")) return array();
            
            include FPBASEDIR."/inc/modules/".$moduleKey."/config.php";
            return $config;
        }
        
        /**
         * Gibt Wert eines des Konfigurations-Wertes eines Modules zurück
         * @param string $moduleKey Key des Moduls
         * @param string $configOption Name des Konfigurations-Wertes
         * @return string Wert des Konfigurations-Schlüssels
         */
        public static function loadModuleConfigOption($moduleKey,$configOption) {
            $fpSystem = new fpSystem(self::$fpDBcon);
            return $fpSystem->loadSysConfig("fpmod_".$moduleKey."_".$configOption);
        }

        /**
         * Modul-Template includen
         * @param string $moduleKey Key des Moduls
         * @param string $templateName Name des Templates
         * @param mixed $templateParams Werte von Variablen, Array, etc. welche im Template verwende werden
         * @since FPCM 2.0.2
         */
        public static function includeModuleTemplate($moduleKey, $templateName, $templateParams = null) {
            if(file_exists(self::getModuleRootPath($moduleKey)."/templates/".$templateName.".php")) {
                if(!is_null($templateParams)) {
                    if(is_array($templateParams)) {
                        foreach ($templateParams as $key => $value) { $$key = $value; }
                    } else {
                        fpMessages::writeToSysLog('Module Template Error: Template parameters not given as array');
                        fpMessages::showErrorText($templateName);
                    }
                }
                include_once self::getModuleRootPath($moduleKey)."/templates/".$templateName.".php";
            } else {
                fpMessages::showErrorText(LANG_MODULE_LOADTPLERROR);
            }
        }

        /**
         * Lädt Modul-Teil für die Ausgabe
         * @param string $moduleKey Key des Moduls
         */
        public static function includeFEModule($moduleKey) {
            if(self::moduleIsActive($moduleKey)) {
                $modConf = self::loadModuleConf($moduleKey);
                if(is_dir(FPBASEDIR."/inc/modules/".$moduleKey) && file_exists(FPBASEDIR."/inc/modules/".$moduleKey."/fe_action.php") && defined('FEMODE')) {
                    self::includeLanguageFile($moduleKey);
                    self::addToFEModulesList($moduleKey);
                    require_once FPBASEDIR."/inc/modules/".$moduleKey."/fe_action.php";
                } else {
                    if((strpos($modConf['type'], 'fe') === false && file_exists(FPBASEDIR."/inc/modules/".$moduleKey."/fe_action.php")) || (strpos($modConf['type'], 'fe') === true && !file_exists(FPBASEDIR."/inc/modules/".$moduleKey."/fe_action.php"))) {
                        fpMessages::showErrorText("FE Module ERROR: ".$moduleKey."<br>".LANG_MODULE_LOADERROR);
                    }
                }  
            } else {
                fpMessages::showErrorText(LANG_MODULE_ISDISABLED);
            }            
        }
        
        /**
         * Lädt alle FE Module in shownews.php
         */
        public static function includeAllFEModules() {
            $handle = opendir (FPBASEDIR."/inc/modules/");
            while ($module = readdir ($handle)) {
                if(is_dir(FPBASEDIR."/inc/modules/".$module) && $module != "." && $module != ".." && $module != "index.html") {                    
                    if(self::moduleIsActive($module)) { self::includeFEModule($module); }
                }
            }              
        }

        /**
         * Lädt Modul-Teil für das ACP
         * @param string $moduleKey Key des Moduls
         */
        public static function includeACPModule($moduleKey) { 
            if(self::moduleIsActive($moduleKey)) {
                if(is_dir(FPBASEDIR."/inc/modules/".$moduleKey) && file_exists(FPBASEDIR."/inc/modules/".$moduleKey."/acp_action.php")) {
                    self::includeLanguageFile($moduleKey);
                    self::addToACPModulesList($moduleKey);
                    require_once FPBASEDIR."/inc/modules/".$moduleKey."/acp_action.php";    
                } else {
                    fpMessages::showErrorText("ACP Module ERROR: ".$moduleKey."<br>".LANG_MODULE_LOADERROR);
                }                
            } else {
                fpMessages::showErrorText(LANG_MODULE_ISDISABLED);
            }
        }
        
        /**
         * Lädt alle ACP Module in acpcommon.php
         * @return array config.php Inhalte des Modules
         */
        public static function includeAllACPModules() {
            $moduleConfArray = array();            
            $handle = opendir (FPBASEDIR."/inc/modules/");
            while ($module = readdir ($handle)) {
                if(is_dir(FPBASEDIR."/inc/modules/".$module) && $module != "." && $module != ".." && $module != "index.html") {                    
                    if(self::moduleIsActive($module)) { self::includeACPModule($module); }
                    $moduleConfArray[$module] = self::loadModuleConf($module);
                }
            }   
            return $moduleConfArray;
        }        
        
        /**
         * Modul aktivieren
         * @param string $moduleKey Key des Moduls
         */
        public static function enableFPModule($moduleKey) {
            if(!self::moduleIsActive($moduleKey)) {
                $tmpActiveFile = fopen(FPBASEDIR."/inc/modules/".$moduleKey."/is_active", 'w');
                fclose($tmpActiveFile);
                fpMessages::writeToSysLog("enable module ".$moduleKey);
            }              
        }
        
        /**
         * Modul deaktivieren
         * @param string $moduleKey Key des Moduls
         */        
        public static function disableFPModule($moduleKey) {
            if(self::moduleIsActive($moduleKey)) {
                unlink(FPBASEDIR."/inc/modules/".$moduleKey."/is_active");
                fpMessages::writeToSysLog("disable module ".$moduleKey);
            }            
        }

        /**
         * Modul installieren
         * @param array $modulePackageNameArray
         * @param bool $auto Modul-Installation automatisch oder manuell
         */
        public static function installFPModule($modulePackageNameArray, $auto = true) {
            if($auto) {
                $start_time = time();
                
                $modulePackageName = $modulePackageNameArray[0]."_".$modulePackageNameArray[1];
                $updateExtractFolder = FPBASEDIR."/upgrade/".$modulePackageName."/";

                if(!mkdir($updateExtractFolder) && !is_writable($updateExtractFolder)) {	
                    fpMessages::showErrorText(LANG_AUTO_UPDATE_PERMFAILURE_MSG);
                } else {                        
                    $updateFileNameRemote = $modulePackageName.".zip";                
                    $updateFileRemote = FPMODUPDATEINSTALL.$updateFileNameRemote;

                    $fpFileSystem = new fpFileSystem(self::$fpDBcon);

                    fpMessages::showSysNotice(LANG_AUTO_UPDATE_START.$updateFileRemote);
                    if($fpFileSystem->downloadPackage($updateExtractFolder, $updateFileNameRemote, $updateFileRemote)) {
                        fpMessages::showSysNotice(LANG_MODULE_INSTALL_DLSUC);
                        if(!$fpFileSystem->updaterUnzipAndCopy($updateExtractFolder.$updateFileNameRemote, $updateExtractFolder, true)){
                            fpMessages::showErrorText(LANG_AUTO_UPDATE_EXTRACT_ERROR);
                        } else {
                            fpMessages::showSysNotice(LANG_AUTO_UPDATE_EXTRACT_OK);
                        }
                    } else {
                        fpMessages::showErrorText(LANG_MODULE_INSTALL_DLNOSUC);
                    }
                }  

                $fpFileSystem->deleteRecursive($updateExtractFolder);     

                if(file_exists(FPBASEDIR."/inc/modules/".$updateFileNameRemote)) {
                    unlink(FPBASEDIR."/inc/modules/".$updateFileNameRemote);
                }
                
                if(file_exists(FPBASEDIR."/inc/modules/".$modulePackageNameArray[0]."/install.php")) {
                    include FPBASEDIR."/inc/modules/".$modulePackageNameArray[0]."/install.php";
                }
                
                fpMessages::writeToSysLog("install module ".$modulePackageName);
                
                $end_time = time();

                $runtime = $end_time - $start_time;	
                print "<p>".fpLanguage::returnLanguageConstant(LANG_AUTO_UPDATE_TIMEFORDL).": ".date("s", $runtime)."sec</p>\n";                            
            } else {
                define('FPCM_MODULE_NOAUTO_INSTALL', true);
                if(file_exists(FPBASEDIR."/inc/modules/".$modulePackageNameArray."/install.php")) {
                    include FPBASEDIR."/inc/modules/".$modulePackageNameArray."/install.php";
                }
                
                fpMessages::writeToSysLog("install module ".$modulePackageNameArray);
            }

            print "<p><a class=\"btnloader fp-ui-button\" href=\"modules.php\">".fpLanguage::returnLanguageConstant(LANG_MODULES_LIST_LINK)."</a></p>\n";            
            
            $fpCache = new fpCache();
            $fpCache->cleanup();            
        }        
        
        /**
         * Modul deinstallieren
         * @param string $moduleKey
         */
        public static function uninstallFPModule($moduleKey, $auto = true) {
            if(!$auto) {
                define('FPCM_MODULE_NOAUTO_UNINSTALL', true);
            }
            
            if(file_exists(FPBASEDIR."/inc/modules/".$moduleKey."/uninstall.php")) {
                include FPBASEDIR."/inc/modules/".$moduleKey."/uninstall.php";
            }
            $fpFileSystem = new fpFileSystem(self::$fpDBcon);
            $fpFileSystem->deleteRecursive(FPBASEDIR."/inc/modules/".$moduleKey);
            
            $fpCache = new fpCache();
            $fpCache->cleanup();
            
            fpMessages::writeToSysLog("uninstall module ".$moduleKey);
        }        

        /**
         * Aktualisiert gewältes Module
         * @param array $modulePackageNameArray
         */
        public static function updateModule($modulePackageNameArray) {
            $modulePackageName = $modulePackageNameArray[0]."_".$modulePackageNameArray[1];
            $updateExtractFolder = FPBASEDIR."/upgrade/".$modulePackageName."/";

            if(!mkdir($updateExtractFolder) && !is_writable($updateExtractFolder)) {	
                fpMessages::showErrorText(LANG_AUTO_UPDATE_PERMFAILURE_MSG);
            } else {            
                $start_time = time();                
                $updateFileNameRemote = $modulePackageName.".zip";                
                $updateFileRemote = FPMODUPDATEINSTALL.$updateFileNameRemote;
                
                $fpFileSystem = new fpFileSystem(self::$fpDBcon);
                fpMessages::showSysNotice(LANG_AUTO_UPDATE_START.$updateFileRemote);
                if($fpFileSystem->downloadPackage($updateExtractFolder, $updateFileNameRemote, $updateFileRemote)) {
                    if(!$fpFileSystem->updaterUnzipAndCopy($updateExtractFolder.$updateFileNameRemote, $updateExtractFolder, true)) {
                        fpMessages::showErrorText(LANG_AUTO_UPDATE_EXTRACT_ERROR);
                    } else {
                        fpMessages::showSysNotice(LANG_AUTO_UPDATE_EXTRACT_OK);
                    }
                    $fpFileSystem->deleteRecursive($updateExtractFolder);
                    if(file_exists(FPBASEDIR."/inc/modules/".$updateFileNameRemote)) {
                        unlink(FPBASEDIR."/inc/modules/".$updateFileNameRemote);
                    }                    
                }
            }  
            
            $end_time = time();

            $runtime = $end_time - $start_time;	
            print "<p>".fpLanguage::returnLanguageConstant(LANG_AUTO_UPDATE_TIMEFORDL).": ".date("s", $runtime)."sec</p>\n";            
            
            print "<p><a class=\"btnloader fp-ui-button\" href=\"modules.php\">".fpLanguage::returnLanguageConstant(LANG_MODULES_LIST_LINK)."</a></p>\n";
            
            fpMessages::writeToSysLog("update module ".$modulePackageNameArray[0]);
            
            $fpCache = new fpCache();
            $fpCache->cleanup();
        }        

        /**
         * Prüfen, ob Modul installiert ist
         * @param string $moduleKey Key des Moduls
         * @return boolean
         * @since FPCM 2.0.2
         */
        public static function moduleIsInstalled($moduleKey) {
            if(file_exists(FPBASEDIR."/inc/modules/".$moduleKey."/")) { return true; } else { return false; }
        }        
        
        /**
         * Prüfen, ob Modul aktiv ist
         * @param string $moduleKey Key des Moduls
         * @return boolean
         */
        public static function moduleIsActive($moduleKey) {
            if(file_exists(FPBASEDIR."/inc/modules/".$moduleKey."/is_active")) { return true; } else { return false; }
        }

    }

?>
