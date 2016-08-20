<?php
    /**
     * FanPress CM acp module events
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 2.4
     */
    
    final class fpModuleEventsAcp {
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
         * Einhängepunkt für Module um CSS-Datei in Header zu laden
         * @return array
         */
        public static function runAddToHeaderCss() {
            $modules = fpModules::getACPModulesList();
            
            $fileList= array();
            foreach ($modules AS $module) {     
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_addToHeaderCss';
                    if(function_exists($functionName)) {
                        $return = self::runFunction($functionName);
                        if(!$return) continue;
                        $fileList[] = $return;
                    }
                }
            }
            
            return $fileList;
            
        }           
        
        /**
         * Einhängepunkt für Module um JavaScript-Datei in Header zu laden
         * @since FPCM 2.0
         */
        public static function runAddToHeaderJs() {
            $modules = fpModules::getACPModulesList();    
            
            $fileList= array();
            foreach ($modules AS $module) {     
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_addToHeaderJs';
                    if(function_exists($functionName)) {
                        $return = self::runFunction($functionName);
                        if(!$return) continue;                        
                        $fileList[] = $return;
                    }
                }
            }
            
            return $fileList;
        }              
        
        /**
         * Einhängepunkt für Module um Eintrag zu Navigation einzufügen
         * @since FPCM 2.0
         */
        public static function runAddToNavigation() {
            $modules = fpModules::getACPModulesList();              
            $menuItems = array();
            foreach ($modules AS $module) {     
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_addToNavigation';
                    if(function_exists($functionName)) {
                        $navData = self::runFunction($functionName);
                        if(!is_array($navData) || !isset($navData['link'])) {
                            fpMessages::writeToSysLog(__FUNCTION__.": Navigation point for $module not added because of missing link");
                            continue;
                        }
                        
                        $newItem = array(
                            'url'               => $navData['link'],
                            'description'       => (isset($navData['descr']) && !empty($navData['descr'])) ? $navData['descr'] : $module,                    
                            'class'             => isset($navData['spacer']) ? $navData['spacer'] : '',
                            'id'                => isset($navData['id']) ? $navData['id'] : ''
                        );
                        
                        if(isset($navData['permission']) && is_array($navData['permission'])) {
                            $newItem['permissions'] = $navData['permission'];
                        }
                        if(isset($navData['spacer']) && is_array($navData['spacer'])) {
                            $newItem['spacer'] = $navData['spacer'];
                        }                        
                        
                        $menuItems[] = $newItem;
                    }
                }
            }
            
            unset($modules,$functionName);
            
            return $menuItems;            
        }
        
        /**
         * Einhängepunkt für Module um Eintrag in Navigation einzufügen
         * @since FPCM 2.4.0
         */
        public static function runAddToNavigationMain() {
            $modules = fpModules::getACPModulesList();                         
            $menuItems = array();
            foreach ($modules AS $module) {     
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_addToNavigationMain';
                    if(function_exists($functionName)) {
                        $navDataItems = self::runFunction($functionName);
                        
                        foreach ($navDataItems as $navData) {
                            if(!is_array($navData) || !isset($navData['link'])) {
                                fpMessages::writeToSysLog(__FUNCTION__.": Navigation point for $module not added because of missing link");                            
                                continue;
                            }

                            $newItem = array(
                                'url'               => $navData['link'],
                                'description'       => (isset($navData['descr']) && !empty($navData['descr'])) ? $navData['descr'] : $module,                    
                                'class'             => isset($navData['class']) ? $navData['class'] : '',
                                'id'                => isset($navData['id']) ? $navData['id'] : '',
                                'icon'              => (isset($navData['icon']) && !empty($navData['icon'])) ? $navData['icon'] : 'ui-icon ui-icon-document'
                            );

                            if(isset($navData['permission']) && is_array($navData['permission'])) {
                                $newItem['permissions'] = $navData['permission'];
                            }
                            if(isset($navData['spacer']) && is_array($navData['spacer'])) {
                                $newItem['spacer'] = $navData['spacer'];
                            }                        

                            $groupKey = isset($navData['group']) ? $navData['group'] : 'after';                            
                            $menuItems[$groupKey][] = $newItem;                          
                        } 
                    }
                }
            }
            
            return $menuItems;            
        }
        
        /**
         * @deprecated since FPCM 2.4.0
         */
        public static function runAddToNavigationMainPos4() {
            $modules = fpModules::getACPModulesList();              
            foreach ($modules AS $module) {     
                if(fpModules::moduleIsActive($module)) {
                    $functionName = $module.'_addToNavigationMainPos4';
                    if(function_exists($functionName)) {
                        fpMessages::writeToSysLog($module.' is calling desprecated event "addToNavigationMainPos4"!');
                    }
                }
            }
            unset($modules,$functionName);            
        } 
        
        /**
         * @deprecated since FPCM 2.4.0
         */
        public static function runAddToNavigationMainPos7() {
            $modules = fpModules::getACPModulesList();              
            foreach ($modules AS $module) {     
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_addToNavigationMainPos7';
                    if(function_exists($functionName)) {
                        fpMessages::writeToSysLog($module.' is calling desprecated event "addToNavigationMainPos7"!');
                    }
                }
            }
            unset($modules,$functionName);            
        }         
        
        /**
         * @deprecated since version 2.4.0
         */
        public static function runOnACPIndexTable() {
            $modules = fpModules::getACPModulesList(); 
            $runs = 0;
            foreach ($modules AS $module) {     
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onACPIndexTable';
                    if(function_exists($functionName)) { 
                        fpMessages::writeToSysLog($module.' is calling desprecated event "onACPIndexTable"!');
                    }
                }
            }
            unset($runs,$modules,$functionName);            
        }

        /**
         * 
         * Einhängepunkt für Module um Dashboard-Container zu erzeugen
         * keine Rückgabe möglich
         * @since 2.4.0
         */        
        public static function runOnAddDashboardContainer() {
            $modules = fpModules::getACPModulesList(); 
            $runs = 0;
            foreach ($modules AS $module) {     
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onAddDashboardContainer';
                    if(function_exists($functionName)) {
                        $functionReturnValue = self::runFunction($functionName);
                        if(!isset($functionReturnValue['containerHeadline']) && !isset($functionReturnValue['containerBody'])) {
                            fpMessages::writeToSysLog($module.' return value for "onACPIndexTable" does not conatin "containerHeadline" and/or "containerBody"!');
                            continue;
                        }
                        
                        $html   = array();
                        
                        $html[] = "<div class=\"fpcm-dashboard-container-box\">";
                        $html[] = "<div class=\"fpcm-dashboard-conatiner\">";
                        $html[] = "<div class=\"fpcm-dashboard-conatiner-inner fpcm-dashboard-conatiner-inner-boxes ui-widget-content ui-corner-all ui-state-normal\">";
                        $html[] = "<h3 class=\"ui-corner-top  ui-corner-all\">".$functionReturnValue['containerHeadline']."</h3>";
                        $html[] = "<div class=\"fpcm-dashboard-conatiner-body\">";
                        $html[] = $functionReturnValue['containerBody'];
                        $html[] = "</div>";
                        $html[] = "</div>";
                        $html[] = "</div>";
                        $html[] = "</div>";
                        
                        print implode(PHP_EOL, $html);
                    }
                }
            }
            unset($runs,$modules,$functionName);
        }

        /**
         * Fügt Eintrag auf Hilfe-Seite hinzu
         * keine Rückgabe möglich
         * @since FPCM 2.1
         */
        public static function runOnCreateHelpEntry() {
            $modules = fpModules::getACPModulesList();            
            foreach ($modules AS $module) {                  
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onCreateHelpEntry';
                    if(function_exists($functionName)) {
                        $eventReturnValue = self::runFunction($functionName);                        
                        print "<h2>".$eventReturnValue["helpHeadline"]."</h2><div>".$eventReturnValue["helpText"]."</div>\n";
                    }
                }
            }
            unset($modules,$functionName);   
        }        
        
        /**
         * Einhängepunkt für Module beim Login in's ACP
         * keine Rückgabe möglich
         * @param array $params
         * @since FPCM 2.0
         */
        public static function runOnACPLogin($params = null) {
            $modules = fpModules::getACPModulesList();            
            foreach ($modules AS $module) {                  
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onACPLogin';
                    if(function_exists($functionName)) {
                        self::runFunction($functionName,$params);
                    }
                }
            }
            unset($modules,$functionName);            
        }
        
        /**
         * 
         * Einhängepunkt für Module beim Speichern einer News
         * Rückgabe möglich
         * @param mixed $params
         * @return array Array mit Daten beim Speichern eines News-Posts
         * @since FPCM 2.0
         */
        public static function runOnNewsSave($params = null) {
            $modules = fpModules::getACPModulesList();            
            $eventReturnValue = array();
            foreach ($modules AS $module) {                  
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onNewsSave';
                    if(function_exists($functionName)) {
                        $eventReturnValue = self::runFunction($functionName,$params);
                    }
                }
            }
            unset($modules,$functionName);
            return count($eventReturnValue) ? $eventReturnValue : $params;
        }
        
        /**
         * 
         * Einhängepunkt für Module beim Aktualisieren eines News
         * Rückgabe möglich
         * @param mixed $params
         * @return array Array mit Daten beim Speichern eines News-Posts
         * @since FPCM 2.4
         */
        public static function runOnNewsUpdate($params = null) {
            $modules = fpModules::getACPModulesList();            
            $eventReturnValue = array();
            foreach ($modules AS $module) {                  
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onNewsUpdate';
                    if(function_exists($functionName)) {
                        $eventReturnValue = self::runFunction($functionName,$params);
                    }
                }
            }
            unset($modules,$functionName);
            return count($eventReturnValue) ? $eventReturnValue : $params;
        }        
        
        /**
         * 
         * Einhängepunkt für Module beim Erstellen eines Kurzlinks für eine News
         * Rückgabe möglich
         * wird nur einmal ausgeführt
         * @param mixed $params
         * @return array Array der Form
         *      "replaceIsGd" => true,
         *      "linkText" => neue Link-URL
         * @since FPCM 2.0
         */
        public static function runOnCreateShortLink($params = null) {
            $modules = fpModules::getACPModulesList();            
            foreach ($modules AS $module) {                  
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onCreateShortLink';
                    if(function_exists($functionName)) {
                        return self::runFunction($functionName,$params);
                    }
                }
            }
            unset($modules,$functionName);   
        }

        /**
         * Zeigt Nachricht im ACP an
         * Rückgabe möglich
         * @return string
         * @since FPCM 2.1
         */
        public static function runOnAddACPMessage() {
            $modules = fpModules::getACPModulesList();    
            
            $output     = array();
            $output[]   = "<div class=\"fpcm-dashboard-message-box ui-widget-content ui-corner-all ui-state-normal\">";
            
            foreach ($modules AS $module) {                  
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onAddACPMessage';
                    if(function_exists($functionName)) {
                        $output[] = "<div class=\"acpmsg_$module\">".self::runFunction($functionName)."</div>\n";
                    }
                }
            }
            
            $output[]   = "</div>";
            
            if(count($output) > 2) {
                print implode(PHP_EOL, $output);
            }
            
            unset($modules,$functionName,$output);             
        }
        
        /**
         * 
         * Einhängepunkt für Module beim Erstellen eines Tweets beim Veröffentlichen einer News
         * Rückgabe möglich
         * wird nur einmal ausgeführt
         * @param mixed $params
         * @return array Array der Form
         *      "tweetText" => Text des Tweets vor dem Link,
         *      "linkText" => Link-URL
         * @since FPCM 2.2.0
         */
        public static function runOnCreateTweet($params = null) {
            $modules = fpModules::getACPModulesList();
            foreach ($modules AS $module) {                   
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onCreateTweet';
                    if(function_exists($functionName)) {
                        return self::runFunction($functionName,$params);
                    } 
                }
            }            
        }  
        
        /**
         * Einhängepunkt für Module um neue(n) Button(s) in HTML-Editor einzufügen
         * @param array $params
         * @since FPCM 2.4.0
         */
        public static function runOnAddEditorButton($params = null) {
            $modules = fpModules::getACPModulesList();
            foreach ($modules AS $module) {                   
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_addEditorButton';
                    if(function_exists($functionName)) {
                        $newButtons = self::runFunction($functionName,$params);

                        $buttonList = array();
                        
                        if(!isset($newButtons[0])) $newButtons = array($newButtons);

                        foreach ($newButtons as $newButton) {

                            if(!is_array($newButton) || !isset($newButton['btnTitle']) || !isset($newButton['btnCssId']) || !isset($newButton['btnDescription'])) {
                                fpMessages::writeToSysLog($module.' return value for "addEditorButton" does not conatin "btnTitle", "btnCssId" and/or "btnDescription"!');
                                continue;
                            }

                            $newButton['btnTag'] = isset($newButton['btnTag']) ? rtrim(strip_tags($newButton['btnTag']), ';') : ''; 
                            
                            $btnCode = "<button title=\"{$newButton['btnTitle']}\" id=\"editor_{$newButton['btnCssId']}\" class=\"fp-editor-html-click\" htmltag=\"{$newButton['btnTag']}\"";
                            $btnCode .= ">";
                            $btnCode .= $newButton['btnDescription'];
                            $btnCode .= "</button>";

                            $buttonList[] = $btnCode;

                        }
                        
                        print implode(PHP_EOL, $buttonList);
                        
                    } 
                }
            }            
        }
        
        /**
         * Einhängepunkt für Module um Absatz-Optionen einzufügen
         * @param array $params
         * @since FPCM 2.4.0
         */
        public static function runOnAddEditorParagraph($params = null) {
            $modules = fpModules::getACPModulesList();
            foreach ($modules AS $module) {                   
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_addEditorParagraph';
                    if(function_exists($functionName)) {
                        $newButtons = self::runFunction($functionName,$params);

                        $buttonList = array();
                        
                        if(!isset($newButtons[0])) $newButtons = array($newButtons);

                        foreach ($newButtons as $newButton) {

                            if(!is_array($newButton) || !isset($newButton['optionTag']) || !isset($newButton['optionDescription'])) {
                                fpMessages::writeToSysLog($module.' return value for "addEditorParagraph" does not conatin "optionTag" and/or "optionDescription"!');
                                continue;
                            }

                            $newButton['optionTag'] = rtrim(strip_tags($newButton['optionTag']), ';');
                            
                            $btnCode = "<li class=\"fp-editor-html-click\" htmltag=\"".strip_tags($newButton['optionTag'])."\"><a>";
                            $btnCode .= $newButton['optionDescription'];
                            $btnCode .= "</a></li>";

                            $buttonList[] = $btnCode;
                        }                        
                        print implode(PHP_EOL, $buttonList);                        
                    } 
                }
            }            
        }       
        /**
         * Einhängepunkt für Module um Schriftgrößen einzufügen
         * @param array $params
         * @since FPCM 2.4.0
         */
        public static function runOnAddEditorFontSize($params = null) {
            $modules = fpModules::getACPModulesList();
            foreach ($modules AS $module) {                   
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_addEditorFontSize';
                    if(function_exists($functionName)) {
                        $newButtons = self::runFunction($functionName,$params);

                        $buttonList = array();
                        
                        if(!isset($newButtons[0])) $newButtons = array($newButtons);

                        foreach ($newButtons as $newButton) {

                            if(!is_array($newButton) || !isset($newButton['optionSize']) || !isset($newButton['optionDescription'])) {
                                fpMessages::writeToSysLog($module.' return value for "addEditorParagraph" does not conatin "optionSize" and/or "optionDescription"!');
                                continue;
                            }

                            $btnCode = "<li class=\"fp-editor-html-fontsize\" htmltag=\"".strip_tags($newButton['optionSize'])."\"><a>";
                            $btnCode .= $newButton['optionDescription'];
                            $btnCode .= "</a></li>";

                            $buttonList[] = $btnCode;
                        }                        
                        print implode(PHP_EOL, $buttonList);                        
                    } 
                }
            }            
        }           
        
        
        /**
         * Einhängepunkt für Module um neue(s) Feld(er) in Erweitert-Menu von Editor einzufügen
         * @param array $params
         * @since FPCM 2.4.0
         */
        public static function runOnAddEditorExtendedField($params = null) {
            $modules = fpModules::getACPModulesList();
            foreach ($modules AS $module) {                   
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_addEditorExtendedField';
                    if(function_exists($functionName)) {
                        $newFields = self::runFunction($functionName,$params);
                       
                        $fieldList = array();
                        
                        if(!is_array($newFields[0])) $newFields = array($newFields);

                        foreach ($newFields as $newField) {

                            if(!is_array($newField) || !isset($newField['fieldDescription']) || !isset($newField['fieldType']) || !isset($newField['fieldName']) || !isset($newField['fieldDefaultValue'])) {
                                fpMessages::writeToSysLog($module.' return value for "addEditorExtendedField" does not conatin "fieldType", "fieldName" and/or "fieldDefaultValue"!');
                                continue;
                            }
                            
                            $fieldCode = "<tr>";
                            $fieldCode .= "<td  class=\"dialog-label\"><label>{$newField['fieldDescription']}</label></td>";
                            $fieldCode .= "<td>";
                            switch ($newField['fieldType']) {
                                case 'select' :
                                    $fieldCode .= "<select class=\"chosen-select-dialog\" name=\"{$newField['fieldName']}\" id=\"{$newField['fieldName']}_id\">";
                                    
                                    if(!isset($newField['fieldOptions']) || !is_array($newField['fieldOptions'])) {
                                        fpMessages::writeToSysLog('No options defined for field '.$newField['fieldName']);
                                        continue;
                                    }
                                    
                                    foreach ($newField['fieldOptions'] as $key => $value) {
                                        if($newField['fieldDefaultValue'] == $value) {
                                            $fieldCode .= "<option value=\"$value\" selected=\"selected\">$key</option>";                                            
                                        } else {
                                            $fieldCode .= "<option value=\"$value\">$key</option>";
                                        }
                                    }
                                    
                                    $fieldCode .= "</select>";
                                    break;
                                default:
                                    $fieldCode .= "<input type=\"text\" ";
                                    $fieldCode .= "name=\"{$newField['fieldName']}\" id=\"{$newField['fieldName']}_id\" ";
                                    $fieldCode .= "value=\"{$newField['fieldDefaultValue']}\">";                                    
                                    break;
                            }
                            $fieldCode .= "</td>";
                            $fieldCode .= "</tr>";
                            
                            $fieldList[] = $fieldCode;

                        }
                        
                        print implode(PHP_EOL, $fieldList);
                        
                    } 
                }
            }            
        }
        
        /**
         * 
         * Einhängepunkt für Module beim Aktualisieren eines Kommentars
         * Rückgabe möglich
         * @param mixed $params
         * @return array Array mit Daten beim Aktualisieren eines Kommentars
         * @since FPCM 2.4
         */
        public static function runOnCommentUpdate($params = null) {
            $modules = fpModules::getACPModulesList();            
            $eventReturnValue = array();
            foreach ($modules AS $module) {                  
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onCommentUpdate';
                    if(function_exists($functionName)) {
                        $eventReturnValue = self::runFunction($functionName,$params);
                    }
                }
            }
            unset($modules,$functionName);
            return count($eventReturnValue) ? $eventReturnValue : $params;
        }
        
        /**
         * Erzeugen einer Liste für Autocomplete-Funktion von Link-Einfügen-Form in News-Editor
         * @return array
         * @since FPCM 2.4
         */
        public static function runOnLinkAutocomplete() {
            $modules = fpModules::getACPModulesList();            
            $eventReturnValue = array();
            foreach ($modules AS $module) {                  
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onLinkAutocomplete';
                    if(function_exists($functionName)) {
                        $fnRes = self::runFunction($functionName);
                        if(!is_array($fnRes)) continue;
                        
                        if(isset($fnRes['value']) || isset($fnRes['label'])) {
                            $fnRes = array($fnRes);
                        }
                        
                        foreach ($fnRes as $index => $value) {
                            if(!isset($value['value']) || !isset($value['label'])) {
                                unset($fnRes[$index]);
                            }                            
                        }
                        
                        if(!count($fnRes)) {
                            fpMessages::writeToSysLog($module.' return value for "onLinkAutocomplete" does not conatin "value" and/or "label"!');
                            continue;                            
                        }
                        
                        $eventReturnValue = array_merge($eventReturnValue, $fnRes);
                    }
                }
            }
            unset($modules,$functionName);
            
            return $eventReturnValue;            
        }
        
        /**
         * 
         * Einhängepunkt für Module beim Erzeugen einer Kategorie
         * Rückgabe möglich
         * @param mixed $params
         * @return array Array mit Daten beim Erzeugen einer Kategorie
         * @since FPCM 2.4.4
         */
        public static function runOnCategorySave($params = null) {
            $modules = fpModules::getACPModulesList();            
            $eventReturnValue = array();
            foreach ($modules AS $module) {                  
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onCategorySave';
                    if(function_exists($functionName)) {
                        $eventReturnValue = self::runFunction($functionName,$params);
                    }
                }
            }
            unset($modules,$functionName);
            return count($eventReturnValue) ? $eventReturnValue : $params;
        }        
        
        /**
         * 
         * Einhängepunkt für Module beim Aktualisieren einer Kategorie
         * Rückgabe möglich
         * @param mixed $params
         * @return array Array mit Daten beim Speichern einer Kategorie
         * @since FPCM 2.4.4
         */
        public static function runOnCategoryUpdate($params = null) {
            $modules = fpModules::getACPModulesList();            
            $eventReturnValue = array();
            foreach ($modules AS $module) {                  
                if(fpModules::moduleIsActive($module)) {                        
                    $functionName = $module.'_onCategoryUpdate';
                    if(function_exists($functionName)) {
                        $eventReturnValue = self::runFunction($functionName,$params);
                    }
                }
            }
            unset($modules,$functionName);
            return count($eventReturnValue) ? $eventReturnValue : $params;
        }        
    }
