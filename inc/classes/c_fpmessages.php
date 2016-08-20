<?php
    /**
     * Fanpress-System Nachrichten-Ausgabe
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */
    final class fpMessages {
        
        private static $fpDBcon;
        
        /**
         * "Keine Rechte"-meldung ausgeben
         */      
        public static function showNoAccess() {
            self::showErrorText(LANG_ERROR_NOPERMISSIONS);
            self::writeToSysLog("Accees to acp without permissions from ".$_SERVER["REMOTE_ADDR"]);
        }   

        /**
         * Fehlermeldungen ausgeben
         * @param string $txt
         * @param bool $return
         * @return string
         */      
        public static function showErrorText($txt, $return = false) {
            $msg    = array();
            $msg[]  = "<div class=\"fp-message-box syserror\">";
            $msg[]  = "<div class=\"fp-msg-icon\"><span class=\"fa fa-exclamation-triangle\"></span></div>";
            $msg[]  = "<div class=\"fp-msg-text\">".utf8_decode($txt)."</div>";
            $msg[]  = "<div class=\"clear\"></div>";
            $msg[]  = "</div>";
            
            if($return) return implode(PHP_EOL, $msg);
            
            print implode(PHP_EOL, $msg);
        }    

        /**
         * Hinweise ausgeben
         * @param string $txt Meldungstext      
         */       
        public static function showSysNotice($txt, $return = false) {
            $msg    = array();
            $msg[]  = "<div class=\"fp-message-box sysnotic\">";
            $msg[]  = "<div class=\"fp-msg-icon\"><span class=\"fa fa-info-circle\"></span></div>";
            $msg[]  = "<div class=\"fp-msg-text\">".utf8_decode($txt)."</div>";
            $msg[]  = "<div class=\"clear\"></div>";
            $msg[]  = "</div>";
            
            if($return) return implode(PHP_EOL, $msg);
            
            print implode(PHP_EOL, $msg);
        }   
        
        /**
         * Meldung in System-Log schreiben
         * @param string $txt
         * @since FPCM 2.1
         */
        public static function writeToSysLog($txt = null) {                       
            $sysLogFile = FPLOGSFOLDER."/fpcm_syslog.txt";            
            if(is_array($txt) || is_object($txt)) { $txt = print_r($txt, true); }
            $LogLine = json_encode(array('time' => time(),'text' => $txt));
            file_put_contents($sysLogFile, $LogLine.PHP_EOL, FILE_APPEND);
        }
        
        /**
         * Meldung in System-Log schreiben
         * @param string $txt
         * @since FPCM 2.1
         */
        public static function writeToSqlLog($txt = null) {                       
            $sysLogFile = FPLOGSFOLDER."/fpcm_sqllog.txt";            
            if(is_array($txt) || is_object($txt)) { $txt = print_r($txt, true); }
            $LogLine = json_encode(array('time' => time(),'text' => $txt));
            file_put_contents($sysLogFile, $LogLine.PHP_EOL, FILE_APPEND);
        }

        /**
         * Initiiert Verbindung zu Datenbank
         * @param fpDB $fpDBcon
         * @since FPCM 2.1.3
         */
        public static function init($fpDBcon) {
            self::$fpDBcon = $fpDBcon;
        }        
        
        /**
         * 
         * @param array $mailData Array mit Daten der Email
         * Struktur:
         *      'mailTo'       => Empfänger (optional)
         *      'mailFrom'     => Ansender (optional)
         *      'mailSubject'  => Betreff
         *      'mailText'     => Text der E-Mail
         * @since FPCM 2.0.3
         */
        public static function sendEMail($mailData) {            
            if(!isset($mailData["mailFrom"])) { $mailData["mailFrom"] = "FanPress"; }           
            if(!isset($mailData["mailTo"])) { $mailData["mailTo"] = fpConfig::get('system_mail'); }
           
            mail($mailData["mailTo"],
                 $mailData["mailSubject"],
                 $mailData["mailText"],
                 "From: ".$mailData["mailFrom"]
            ); 
            self::writeToSysLog("mail was send to ".$mailData["mailTo"]." ".$mailData["mailSubject"]);
        }
    }
?>
