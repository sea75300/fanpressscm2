<?php
    final class fpModule_fpModuleStarter_ModStarter {
        
        private $moduleKey = null;
        
        private $fpDBcon;

        public function __construct($moduleKey, $fpDBcon) {
            $this->moduleKey = fpSecurity::Filter5($moduleKey);
            $this->fpDBcon = $fpDBcon;
        }

        private function getModuleKey() {
            return $this->moduleKey;
        }
        
        private function createDefaultLanguageFiles($langCode, $description = null) {
            $handel = fopen(FPBASEDIR."/inc/modules/".$this->getModuleKey()."/lang/".$langCode.".php", "w");

            if($handel) {
                $content = "<?php".PHP_EOL;
                
                if($description != null) { $content .= "/**".PHP_EOL."* ".$description.PHP_EOL."*/".PHP_EOL.PHP_EOL; }
                
                $content .= PHP_EOL."?>";                    
                fwrite($handel, $content);
                fclose($handel);     
                
                return true;
            } else {
                return false;
            }              
        }

        public function createModuleFolders() {
            if(file_exists(FPBASEDIR."/inc/modules/".$this->getModuleKey()."/")) {
                return -1;
            }            
            
            if(!mkdir(FPBASEDIR."/inc/modules/".$this->getModuleKey()."/")) { return false; }  
            if(!mkdir(FPBASEDIR."/inc/modules/".$this->getModuleKey()."/lang/")) {
                return false;
            } else {
                $this->createDefaultLanguageFiles("de", "Sprachdatei Deutsch");
                $this->createDefaultLanguageFiles("en", "Language file English");
            }
            if(!mkdir(FPBASEDIR."/inc/modules/".$this->getModuleKey()."/templates")) { return false; }
            return  true;
        }
        
        public function createModuleConfigFile() {  
            $fpSystem = new fpSystem($this->fpDBcon);            
            $configFileHandel = fopen(FPBASEDIR."/inc/modules/".$this->getModuleKey()."/config.php", "w");

            if($configFileHandel) {
                $configContent = "<?php".PHP_EOL;                    
                $arrayModTypes = $_POST["modstarterModType"];
                sort($arrayModTypes);
            
                $configContent .= "\$config = array(
                    'name' => '".fpSecurity::Filter5($_POST["modstarterModName"])."',
                    'version'    => '0.0.1',
                    'type'       => '".implode(", ",$arrayModTypes)."',
                    'minlevel'   => '1'".PHP_EOL.");";

                $configContent .= PHP_EOL."?>";                    
                fwrite($configFileHandel, $configContent);
                fclose($configFileHandel);     
                
                $moduleInfoFileHandel = fopen(FPBASEDIR."/inc/modules/".$this->getModuleKey()."/moduleinfo.txt", "w");             
                
                if($moduleInfoFileHandel) {
                    if(!defined('FPSYSVERSION')) { define('FPSYSVERSION', fpConfig::get('system_version')); }
                    $moduleInfoFileContent = "!!! Notice: this file contains the necessary informations for the module repository. Include this file in your package for the repository!!!".PHP_EOL."name: ".fpSecurity::Filter5($_POST["modstarterModName"]).PHP_EOL."version: 0.0.1".PHP_EOL."type: ".implode(", ",$arrayModTypes).PHP_EOL."minversion: ".FPSYSVERSION;
                    fwrite($moduleInfoFileHandel, $moduleInfoFileContent);
                    fclose($moduleInfoFileHandel);
                    
                    return true;
                }               
            }
            
            return false;
        }
        
        public function createInstallPHP() {
            $installPHPHandel = fopen(FPBASEDIR."/inc/modules/".$this->getModuleKey()."/install.php", "w");

            if($installPHPHandel) {
                $installContent = "<?php".PHP_EOL."    // your code here ".PHP_EOL."?>";               
                fwrite($installPHPHandel, $installContent);
                fclose($installPHPHandel);                            
                
                return true;
            } else {
                return false;
            }            
        }
        
        public function createUninstallPHP() {
            $installPHPHandel = fopen(FPBASEDIR."/inc/modules/".$this->getModuleKey()."/uninstall.php", "w");

            if($installPHPHandel) {
                $installContent = "<?php".PHP_EOL."    // your code here ".PHP_EOL."?>";               
                fwrite($installPHPHandel, $installContent);
                fclose($installPHPHandel);                            
                
                return true;
            } else {
                return false;
            }            
        }        

        public function createModuleFEAction() {
            $feActionHandel = fopen(FPBASEDIR."/inc/modules/".$this->getModuleKey()."/fe_action.php", "w");

            if($feActionHandel) {
                $feActionContent = "<?php".PHP_EOL;        

                if(isset($_POST["modstarterModEventsFE"])) {
                    $arrayFEEvents = $_POST["modstarterModEventsFE"];
                    foreach($arrayFEEvents AS $arrayFEEvent) { $feActionContent .= PHP_EOL."   function ".$this->getModuleKey()."_".$arrayFEEvent."() {".PHP_EOL."        // your code here ".PHP_EOL."   }".PHP_EOL; }                                
                }

                $feActionContent .= PHP_EOL."?>";                    
                fwrite($feActionHandel, $feActionContent);
                fclose($feActionHandel);                            
                
                return true;
            } else {
                return false;
            }              
        }
        
        public function createModuleACPAction() {
            $acpActionHandel = fopen(FPBASEDIR."/inc/modules/".$this->getModuleKey()."/acp_action.php", "w");
            if($acpActionHandel) {
                $feActionContent = "<?php".PHP_EOL;                    

                if(isset($_POST["modstarterModEventsACP"])) {
                    $arrayACPEvents = $_POST["modstarterModEventsACP"];
                    foreach($arrayACPEvents AS $arrayFEEvent) { $feActionContent .= PHP_EOL."   function ".$this->getModuleKey()."_".$arrayFEEvent."() {".PHP_EOL."        // your code here ".PHP_EOL."   }".PHP_EOL; }                    
                }

                $feActionContent .= PHP_EOL."   function ".$this->getModuleKey()."_acpRun() {".PHP_EOL."        // your code here ".PHP_EOL."   }".PHP_EOL;

                $feActionContent .= PHP_EOL."?>";                    
                fwrite($acpActionHandel, $feActionContent);
                fclose($acpActionHandel); 
                
                return true;
            } else {
                return false;
            }              
        }
    }
?>
