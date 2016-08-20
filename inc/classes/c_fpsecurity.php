<?php
    /**
     * FanPress CM security system
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2012-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 2.0
     */

    final class fpSecurity {

        private static $fpDBcon;
        
        private static $permissions = array();

        /**
         * Filter mit htmlspecialchars und strip_tags
         * 
         * @param string $argument
         * @return string
         */
        public static function Filter1($argument) {
            return self::fpfilter(array('filterstring' => $argument), array(1,2));             
        }

        /**
         * Filter mit htmlspecialchars, stripslashes und strip_tags
         * 
         * @param string $argument
         * @return string
         */
        public static function Filter2($argument) {            
            return self::fpfilter(array('filterstring' => $argument), array(1,4,2));             
        }

        /**
         * Filter mit htmlspecialchars_decode und stripslashes
         * 
         * @param string $argument
         * @return string
         */
        public static function Filter3($argument) {
            return self::fpfilter(array('filterstring' => $argument), array(4,5));             
        }

        /**
         * Filter mit htmlspecialchars, htmlentities und strip_tags
         * 
         * @param string $argument
         * @return type
         */		
        public static function Filter4($argument) {
            return self::fpfilter(array('filterstring' => $argument), array(1,3,2));             
        }	

        /**
         * Filter mit trim, htmlspecialchars und striptags
         * 
         * @param string $argument
         * @return string
         */
        public static function Filter5($argument) {
            return self::fpfilter(array('filterstring' => $argument), array(1,2,7));             
        }

        /**
         * Filter mit htmlspecialchars_decode, stripslashes und strip_tags
         * 
         * @param string $argument
         * @return string
         */
        public static function Filter6($argument) {
            return self::fpfilter(array('filterstring' => $argument), array(1,4,6));            
        }
        
        /**
         * Filter mit html_entity_decode, htmlspecialchars_decode und strip_tags
         * 
         * @param string $argument
         * @return string
         */
        public static function Filter7($argument) {
            return self::fpfilter(array('filterstring' => $argument), array(1,5,6));
        }

        /**
         * Filter mit trim, stripslashes & strip_tags
         * 
         * @param string $argument
         * @return string
         */
        public static function Filter8($argument) {
            return self::fpfilter(array('filterstring' => $argument), array(1,4,7));
        }
        
        /**
         * allgemeiner Filter, dessen vorgehen festgelegt werden kann
         * @param array $argument
         * Struktur:
         * * $argument['filterstring'] - String der gefiltert werden soll
         * * $argument['allowedtags'] - ggf. Tags, die laubt sind
         * @param array $filters
         * * 1 strip_tags
         * * 2 htmlspecialchars
         * * 3 htmlentities
         * * 4 stripslashes
         * * 5 htmlspecialchars_decode
         * * 6 html_entity_decode
         * * 7 trim
         * @return string
         * @since FPCM 2.3.0
         */
        public static function fpfilter($argument, $filters = array(1,4,7)) {            
            $filterString = $argument['filterstring'];

            if(is_array($filterString)) {

                foreach ($filterString as $key => $value) {
                    $filterString[$key] = self::fpfilter(array('filterstring' => $value), $filters);
                }                
                return $filterString;
            }
            
            $allowedTags  = (isset($argument['allowedtags'])) ? $argument['allowedtags'] : '';

            foreach ($filters as $filter) {                
                switch ($filter) {
                    case '1' :
                        $filterString = strip_tags($filterString, $allowedTags);
                    break;
                    case '2' :
                        $filterString = htmlspecialchars($filterString, ENT_COMPAT | ENT_HTML401, FPSPECIALCHARSET);
                    break;
                    case '3' :
                        $filterString = htmlentities($filterString, ENT_COMPAT | ENT_HTML401, FPSPECIALCHARSET);
                    break;
                    case '4' :
                        $filterString = stripslashes($filterString);
                    break;
                    case '5' :
                        $filterString = htmlspecialchars_decode($filterString);
                    break;
                    case '6' :
                        $filterString = html_entity_decode($filterString);
                    break;
                    case '7' :
                        $filterString = trim($filterString);
                    break;
                }
                
            }
            
            return $filterString;
            
        }

        /**
         * Prüft ob Benutzer das recht hat, auf Modul/Funktion zuzugreifen
         * 
         * @param string $pmod
         * @return boolean
         */
        public static function checkPermissions($pmod) {     
            if(isset(self::$permissions[$pmod]) && self::$permissions[$pmod] == 1) return true;
            return false;
        }                

        /**
         * Initiiert Verbindung zu Datenbank
         * @param fpDB $fpDBcon
         * @since FPCM 2.1.3
         */
        public static function init($fpDBcon) {
            self::$fpDBcon = $fpDBcon;
            if(!defined('ENT_HTML401')) define ('ENT_HTML401', 0);
            self::initPermissions();
        }

        /**
         * Benutzerrechte ermitteln 
         * @return array Array mit Benutzerrechten
         */  
        private static function initPermissions() { 
            if(!fpConfig::currentUser()) return false;

            $result = self::$fpDBcon->select('permissions', 'permissions', 'author_level_id = ?', array(fpConfig::currentUser('usrlevel')));
            if($result === false) { self::$fpDBcon->getError();return false; }            
            $result = self::$fpDBcon->fetch($result);
            
            self::$permissions = unserialize($result->permissions);
        }
        
        /**
         * Benutzerrechte in Konfiguration laden        
         * @return object mit Berechtigungen  
         */    
        public static function getPermissionsConfig() {
            $result = self::$fpDBcon->select('permissions');
            if($result === false) { self::$fpDBcon->getError();return false; }            
            return $result;    
        }         

        /**
         * Passwort-Hash erstellen
         * @param string $passwd
         * @return string
         */     
        public static function createPasswdhash($passwd) {
            for($i=0;$i<10;$i++) { $passwd = hash("sha256",$passwd); }
            return $passwd;
        }        
        
    }

?>