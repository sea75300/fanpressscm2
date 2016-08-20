<?php
    /**
     * FanPress CM systemlogs handler
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 2.5
     */

    class fpLogs {
        
        public function getUserLog() {
            global $fpUser, $fpSystem;

            print "<table style=\"margin:0 auto;\">\n";
            print "<tr>\n";
            print "     <td class=\"tdheadline\" style=\"text-align:left;\">".fpLanguage::returnLanguageConstant(LANG_USR_SYSUSR)."</td>\n";
            print "     <td class=\"tdheadline\" style=\"width:200px;\">".fpLanguage::returnLanguageConstant(LANG_IPBANN_BANNEDIP)."</td>\n";        
            print "     <td class=\"tdheadline\" style=\"width:200px;\">".fpLanguage::returnLanguageConstant(LANG_USR_LASTLOGIN)."</td>\n";
            print "     <td class=\"tdheadline\" style=\"width:200px;\">".fpLanguage::returnLanguageConstant(LANG_USR_LASTLOGOUT)."</td>\n";
            print "</tr>\n";

            $users = $fpUser->getAllUsers();
            
            $userIndex = array();            
            foreach ($users as $user) { $userIndex[$user->id] = $user->sysusr; }
            unset($users);
            
            foreach($fpSystem->showUserLog() AS $row) :
                if($user) {
                    print "<tr>\n";
                    print "     <td class=\"tdtcontent\" style=\"text-align:left;\">".(isset($userIndex[$row->sysusr]) ? $userIndex[$row->sysusr] : fpLanguage::returnLanguageConstant(LANG_USR_USRDELETEDMSG))."</td>\n";
                    print "     <td class=\"tdtcontent\">".$row->ip."</td>\n";                
                    print "     <td class=\"tdtcontent\">".date(fpConfig::get('timedate_mask'), $row->login_time)."</td>\n";

                    if($row->logout_time == 0) {
                        print "     <td class=\"tdtcontent\">".fpLanguage::returnLanguageConstant(LANG_USR_LOGOUTAUTO)."</td>\n";                    
                    } else {
                        print "     <td class=\"tdtcontent\">".date(fpConfig::get('timedate_mask'), $row->logout_time)."</td>\n";
                    }
                    print "</tr>\n";
                }        
            endforeach;   

            print "</table>";            
        }
        
        public function getSysLog() {
            if(file_exists(FPLOGSFOLDER."/fpcm_syslog.txt")){
                $sysLogFile = file(FPLOGSFOLDER."/fpcm_syslog.txt");
                foreach($sysLogFile AS $sysLogFileEntry) {
                    $sysLogFileEntry = json_decode(trim($sysLogFileEntry), true);                        
                    if(empty($sysLogFileEntry['text'])) continue;
                    print "<p><b>".date(fpConfig::get('timedate_mask'), $sysLogFileEntry['time']).":</b> ".$sysLogFileEntry['text'].'</p>'.PHP_EOL;
                }            
            }            
        }
        
        public function getPhpLog() {
            if(file_exists(FPLOGSFOLDER."/fpcm_phplog.txt")){
                $phpLogFile = file(FPLOGSFOLDER."/fpcm_phplog.txt");
                foreach($phpLogFile AS $phpLogFileEntry) {                       
                    $phpLogFileEntry = json_decode(trim($phpLogFileEntry), true);                        
                    if(empty($phpLogFileEntry['text'])) continue;
                    print "<p><b>".date(fpConfig::get('timedate_mask'), $phpLogFileEntry['time']).":</b> ".$phpLogFileEntry['text'].'</p>'.PHP_EOL;
                }
            }            
        }
        
        public function getSqlLog() {
            if(file_exists(FPLOGSFOLDER."/fpcm_sqllog.txt")){
                $phpLogFile = file(FPLOGSFOLDER."/fpcm_sqllog.txt");
                foreach($phpLogFile AS $phpLogFileEntry) {                       
                    $phpLogFileEntry = json_decode(trim($phpLogFileEntry), true);                        
                    if(empty($phpLogFileEntry['text'])) continue;
                    print "<p><b>".date(fpConfig::get('timedate_mask'), $phpLogFileEntry['time']).":</b> ".$phpLogFileEntry['text'].'</p>'.PHP_EOL;
                }
            }            
        }
        

        /**
         * System-Log Dateien leeren
         * @since FPCM 2.2
         */        
        public function clearLogs($logtype) {
            switch ($logtype) {
                case 1 :
                    file_put_contents(FPLOGSFOLDER."/fpcm_syslog.txt", '');
                break;
                case 2 :
                    file_put_contents(FPLOGSFOLDER."/fpcm_phplog.txt", '');
                break;
                case 3 :
                    global $fpSystem;                    
                    $fpSystem->clearUsrLog();
                break;
                case 4 :
                    file_put_contents(FPLOGSFOLDER."/fpcm_sqllog.txt", '');
                break;
            }
        }        
        
    }
