<?php
    /**
     * FanPress CM lanuage abstraction class
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2012-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 2.1
     */

    final class fpLanguage {
        
        private $language = null;
        private $languageFiles = array();
        
        public function __construct($langCode = 'en') {
            $this->language = $langCode;
            $this->languageFiles = glob(FPBASEDIR.'/lang/'.$langCode."/*.php");
        }

        /**
         * Gibt Sprach-String zurück
         * @return string
         * @since FPCM 2.1
         */
        public function getCurrentLanguage() {
            return $this->language;
        }
        
        /**
         * Setzt aktuelle Sprache
         * @param string $language
         * @since FPCM 2.2
         */
        public function setCurrentLanguage($language) {
            $this->language = $language;
        }        

        /**
         * Gibt installierte Sprach-Pakete zurück
         * @return array Aray mit installierten Sprachen
         * @since FPCM 2.1
         */
        public function getInstalledLanguages() {
            $handle = opendir (FPBASEDIR. "/lang/");
            $languagesArray = array();
            while ($datei = readdir ($handle)) {
                $dateiinfo = pathinfo($datei);							 
                if($datei != "." && $datei != ".." && $datei != "index.html") {                    
                    $languagesArray[$dateiinfo['filename']] = file_get_contents(FPBASEDIR."/lang/".$dateiinfo['filename']."/lang.cfg");
                }
            }
            closedir($handle);	
            
            return $languagesArray;
        }

        /**
         * Lädt Sprachdateien für FPCM
         * @since FPCM 2.1
         */
        public function loadLanguageFiles() {
            if(!$this->languagesFilesExists()) {
                fpMessages::writeToSysLog("FATAL ERROR: No language files for \"".$this->language."\" were found.");
                die();
            }
            
            foreach ($this->languageFiles as $languageFile) {
                if(in_array(basename($languageFile), array('lang.cfg', 'tz.php', 'help.php'))) continue;
                require_once($languageFile);
            }
        } 
        
        /**
         * Gibt "Ja" Beschriftung zurück
         * @since FPCM 2.1
         */
        public static function printYes() {
            print utf8_decode(LANG_GLOBAL_YES);
        }
        
        /**
         * Gibt "Nein" Beschriftung zurück
         * @since FPCM 2.1
         */        
        public static function printNo() {
            print utf8_decode(LANG_GLOBAL_NO);
        }

        /**
         * Gibt "Speichern" Beschriftung zurück
         * @since FPCM 2.1
         */        
        public static function printSave() {
            print utf8_decode(LANG_GLOBAL_SAVE);
        }

        /**
         * Gibt "Erweitert" Beschriftung zurück
         * @since FPCM 2.4
         */        
        public static function printExtended() {
            print utf8_decode(LANG_GLOBAL_EXTENDED);
        }
        
        /**
         * Gibt "Aktualisieren" Beschriftung zurück
         * @since FPCM 2.4
         */        
        public static function printReload() {
            print utf8_decode(LANG_GLOBAL_RELOAD);
        }       
        
        /**
         * Gibt "Zurück" Beschriftung zurück
         * @since FPCM 2.4
         */        
        public static function printBack() {
            print utf8_decode(LANG_GLOBAL_BACK);
        }          
        
        /**
         * Gibt "Schließen" Beschriftung zurück
         * @since FPCM 2.4
         */        
        public static function printClose() {
            print utf8_decode(LANG_GLOBAL_CLOSE);
        }          
        
        /**
         * Gibt "Bitte wählen" Beschriftung zurück
         * @since FPCM 2.5
         */        
        public static function printSelect() {
            print utf8_decode(LANG_GLOBAL_SELECT);
        }         
        
        /**
         * Gibt "OK" Beschriftung zurück
         * @since FPCM 2.2
         */        
        public static function printOk() {
            print utf8_decode('OK');
        }
        /**
         * Schreibt übergebene Sprach-Konstante zurück
         * @param string $langConstant Sprach-Konstante aus Sprachdatei
         * @since FPCM 2.1
         */
        public static function printLanguageConstant($langConstant) {
            print utf8_decode($langConstant);
        }

        /**
         * Gibt übergebene Sprach-Konstante zurück
         * @param string $langConstant Sprach-Konstante aus Sprachdatei
         * @since FPCM 2.1
         */        
        public static function returnLanguageConstant($langConstant) {
            return utf8_decode($langConstant);
        }
        
        /**
         * Ersetzt Platzhalter in einem Text, welche von $replaceArray übergeben werden
         * @param string $orgText Text, welcher Platzhalter enthält
         * @param array $replaceArray Array mit Platzhaltern in der Form "%replacer%" => "ReplaceValue"
         * @param bool $Utf8Conversion soll bereits UTF8-konvertierter String zurückgegeben werden
         * @return string
         * @since FPCM 2.1
         */
        public static function replaceTextPlaceholder($orgText, $replaceArray, $Utf8Conversion = true) {
            foreach($replaceArray as $placeholder => $placeholderValue) {
                $orgText = str_replace($placeholder, $placeholderValue, $orgText);
            }
            if($Utf8Conversion) { return utf8_decode($orgText); } else { return $orgText; }
        }

        /**
         * Ersetzt Eintrag in der Datenbank durch Eintrag in Sprachdatei
         * @param string $dataToReplace
         * @param array $dbDataArray
         * @since FPCM 2.1
         */
        public static function replaceDBDataByLanguageData($dataToReplace,$dbDataArray) {
            if(isset($dbDataArray[$dataToReplace])) {
                return str_replace($dataToReplace, $dbDataArray[$dataToReplace], $dataToReplace);
            } else {
                return $dataToReplace;
            }
        }        
        
        /**
         * Liefert Liste mit Monaten
         * @param int $month Monats-Index (1-12)
         * @return array|string
         * @since FPCM 2.2
         */
        public static function getMonthList($month = 0) {
            $months = array('');
            $months = array_merge($months, explode(',', fpLanguage::returnLanguageConstant(LANG_DATETIME_MONTHS)));             
            if($month == 0) { return $months; } else { return $months[$month]; }
        }
        
        /**
         * Liefert Liste mit Tagen
         * @param int $month Tag-Index (1-7)
         * @return array|string
         * @since FPCM 2.2
         */
        public static function getDayList($day = 0) {
            $days = array('');
            return array_merge($days, explode(',', fpLanguage::returnLanguageConstant(LANG_DATETIME_DAYS)));
            
            if($day == 0) { return $days; } else { return $days[$day]; }
        }        
        
        /**
         * Prüft, ob alle Dateien von Sprach-Paket vorhanden sind
         * @return boolean true, wenn alle Dateien von Sprachpaket existieren
         * @since FPCM 2.1
         */
        private function languagesFilesExists() {            
            foreach ($this->languageFiles as $languageFile) {
                if(!file_exists($languageFile)) {
                    return false;
                }
            }            
            return true;
        }
    }

?>
