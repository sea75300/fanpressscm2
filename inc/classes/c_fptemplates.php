<?php
    /**
     * FanPress CM template actions
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2012-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    final class fpTemplates {

        /**
         * Template-Datei öffnen
         * @param string $tplname Template-Datei
         * @return string Inhalt der Template-Datei
         *
         */
        public static function openTemplate($tplname) {
            return file_get_contents($tplname, false, NULL, 0, filesize($tplname));
        }

        /**
         * Template-Datei speichern
         * @param string $tplname Template-Datei
         * @param string $newTplCode neuer Inhalt der Template-Datei
         *
         */
        public static function saveTemplate($tplname, $newTplCode) {
            
            $newTplCode = fpSecurity::fpfilter(array('filterstring' => $newTplCode), array(7,4));
            
            file_put_contents($tplname, $newTplCode);

            $fpCache = new fpCache();
            $fpCache->cleanup();
        }

        /**
         * prüft Template-Code auf CSS-Angaben speichern
         * 
         * @param string $tplcode Template-Code
         * @return boolean
         */
        public static function cssInTemplate($tplcode) {
            $testresult = false;
            $tpltest = strpos($tplcode, "<style");
            if ($tpltest !== false) {
                return true;
            } else {
                $tpltest = strpos($tplcode, "</style>");
                if ($tpltest !== false) {
                    return true;
                } else {
                    $tpltest = strpos($tplcode, "<link rel=\"stylesheet\" type=\"text/css\"");
                    if ($tpltest !== false) {
                        return true;
                    } else {
                        $tpltest = strpos($tplcode, "<body>");
                        if ($tpltest !== false) {
                            return true;
                        }
                    }
                }
            }
        }
        
        /**
         * Template-Platzhalter durch Wert ersetzen
         * @param array $replaceArray Array mit Platzhaltern und Ersetzungswerten
         * @param string $templateCode Template Code
         * @since FPCM2.2
         */
        public static function replaceMarker($replaceArray, $templateCode){
            foreach ($replaceArray as $key => $value) {
                $templateCode = str_replace($key, $value,$templateCode);
            }
            
            return $templateCode;
        }

    }
?>