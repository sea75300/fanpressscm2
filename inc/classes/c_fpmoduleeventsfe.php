<?php
    /**
     * FanPress CM frontend module events
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 2.4
     */
    
    final class fpModuleEventsFE {  
        /**
         * Funktion ausführen
         * @param string $functionName Name der Funktion
         * @param array $params Parameter
         * @return mixed ggf. Rückgabewert der Funktion
         */
        private static function runFunction($functionName,$params = null) {                            
            if($params != null) { return $functionName($params); } else { return $functionName(); }           
        }        
         
        /**
         * Einhängepunkt für Module bei Test der eingegebenen Anti-Spam-Antwort
         * Rückgabe möglich
         * @since FPCM 2.1
         * @param mixed $params
         * @return boolean
         */
        public static function runOnTestSpamAnswer($params = null) {
            $modules = fpModules::getFEModulesList(); 
            
            $return  = false;
            foreach ($modules AS $module) {                   
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onTestSpamAnswer';
                    if(function_exists($functionName)) {
                        $return = (bool) self::runFunction($functionName,$params);
                    }
                }
            }
            
            return $return;
        }     
        
        /**
         * Einhängepunkt für Module, um in Kommentar-Form das Formular zu ersetzen
         * Rückgabe möglich
         * @return string, wenn anderes Plugin gefunden wurde, false wenn nicht
         * @since FPCM 2.1
         */
        public static function runOnReplaceSpamPlugin() {
            $modules = fpModules::getFEModulesList();            
            $return  = false;
            foreach ($modules AS $module) {                   
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onReplaceSpamPlugin';
                    if(function_exists($functionName)) {
                        $return = self::runFunction($functionName);
                    } 
                }
            }
            
            return $return;
        }

        /**
         * Einhängepunkt für Module um CSS-Datei in shownews.php zu laden
         * keine Rückgabe möglich
         * @since FPCM 2.0
         */
        public static function runFEAddCss() {
            $modules = fpModules::getFEModulesList();              
            foreach ($modules AS $module) {     
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_feAddCss';
                    if(function_exists($functionName)) {
                        print "<link rel=\"stylesheet\" type=\"text/css\" href=\"".self::runFunction($functionName)."\">\n";
                    }
                }
            }
            unset($modules,$functionName);
        }           
        
        /**
         * Einhängepunkt für Module um JavaScript-Datei in shownews.php zu laden
         * keine Rückgabe möglich
         * @since FPCM 2.0
         */
        public static function runFEAddJs() {
            $modules = fpModules::getFEModulesList();              
            foreach ($modules AS $module) {     
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_feAddJs';
                    if(function_exists($functionName)) {
                        print "<script type=\"text/javascript\" src=\"".self::runFunction($functionName)."\"></script>\n";
                    }
                }
            }
            unset($modules,$functionName);      
        }        
        
        /**
         * Einhängepunkt für Module beim Laden einer einzelnen News
         * keine Rückgabe möglich
         * @param int $param ID der News
         * @since FPCM 2.0
         */
        public static function runOnNewsSingleLoad($param = null) {
            $modules = fpModules::getFEModulesList();            
            foreach ($modules AS $module) {                   
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onNewsSingleLoad';                        
                    if(function_exists($functionName)) {
                        self::runFunction($functionName,$param);
                    } 
                }
            }
            unset($modules,$functionName);            
        }

        /**
         * 
         * Einhängepunkt für Module beim Parsen einer News
         * Rückgabe möglich
         * @param mixed $params
         * @return string Zeichenkette, welche von Modul-Event-Funktion zurückgegeben wurde
         * @since FPCM 2.0
         */
        public static function runOnNewsParse($params = null) {
            $modules = fpModules::getFEModulesList();            
            $eventReturnValue = $params;
            foreach ($modules AS $module) {                   
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onNewsParse';                        
                    if(function_exists($functionName)) {
                        $eventReturnValue = self::runFunction($functionName,$params);
                    } 
                }
            }
            unset($modules,$functionName);
            return $eventReturnValue;
        }        
        
        /**
         * 
         * Einhängepunkt für Module beim Speichern eines Kommentars
         * Rückgabe möglich
         * @param mixed $params
         * @return array Array mit Daten beim Speichern eines Kommentars
         * @since FPCM 2.0
         */        
        public static function runOnCommentSave($params = null) {
            $modules = fpModules::getFEModulesList(); 
            $eventReturnValue = array();
            foreach ($modules AS $module) {                   
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onCommentSave';
                    if(function_exists($functionName)) {
                        $eventReturnValue = array_merge($eventReturnValue,self::runFunction($functionName,$params));
                    } 
                }
            }
            unset($modules,$functionName);  
            return count($eventReturnValue) ? $eventReturnValue : $params;
        }

        /**
         * 
         * Einhängepunkt für Module beim Parsen einer News
         * Rückgabe möglich
         * @param mixed $params
         * @return string Zeichenkette, welche von Modul-Event-Funktion zurückgegeben wurde
         * @since FPCM 2.0
         */
        public static function runOnCommentParse($params = null) {
            $modules = fpModules::getFEModulesList();    
            $eventReturnValue = $params;
            foreach ($modules AS $module) {                   
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onCommentParse';
                    if(function_exists($functionName)) {
                        $eventReturnValue = self::runFunction($functionName,$params);
                    } 
                }
            }
            unset($modules,$functionName);
            return $eventReturnValue;
        }
        
        /**
         * Einhängepunkt für Module um Share Buttons zu ersetzten
         * Rückgabe möglich
         * @param type $params
         * @return array
         * @since FPCM 2.4.2
         */
        public static function runOnParseShareButtons($params = null) {
            $modules = fpModules::getFEModulesList();    
            $eventReturnValue = $params;
            foreach ($modules AS $module) {                   
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onParseShareButtons';
                    if(function_exists($functionName)) {
                        $functionReturn = self::runFunction($functionName,$params);
                        
                        if(!is_array($functionReturn) || !isset($functionReturn['sharebuttons'])) {
                            fpMessages::writeToSysLog(__FUNCTION__.": Return value $module was not an array or does not container a \"sharebuttons\" value!");
                            continue;
                        }
                        
                        $eventReturnValue = $functionReturn;
                    } 
                }
            }
            unset($modules,$functionName);
            return $eventReturnValue;            
        }
    }
