<?php
    /**
     * FanPress CM main system class
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    final class fpSystem {

        private $fpDBcon;
        private $undeletableConfig = array();

        /**
         * Der Konstruktor
         * @return void
         */
        public function __construct($fpDBcon) {
            $this->fpDBcon = $fpDBcon;
            $this->undeletableConfig = is_array(fpConfig::get()) ? array_keys(fpConfig::get()) : array();
        }

        /**
         * Der Destruktor
         */
        public function __destruct() {
            $this->fpDBcon = null;
        }
        
        /**
         * Login einer Benutzers
         * @param array $logindata
         * @return boolean
         */
        public function UsrLogin(array $logindata) {

            $result = $this->fpDBcon->select('authors', 'id, usrlevel', "sysusr LIKE '".$logindata['loginusrname']."' AND passwd LIKE '".fpSecurity::createPasswdhash($logindata['loginpasswd'])."'");
            
            if ($result === false) {
                $this->fpDBcon->getError();
                return false; 
            }

            $row = $this->fpDBcon->fetch($result);
            
            if ($row && $row->usrlevel) {                
                $this->createSession(fpConfig::get('session_length'), $row->id);
                return true;
            }

            return false;
        }
        
        /**
         * Erzeugt neuen Eintrag für Session in Userlog-Tabelle
         * @param int $seslen
         * @return boolean
         * @since FPCM2.2.0
         */
        private function createSession($seslen, $userId) {
            $logintime = time();
            $cktime = $logintime + $seslen;
            $fpsession_id = md5(str_shuffle((time() . $_SERVER["REMOTE_ADDR"] . rand(0, 100000))));

            $result = $this->fpDBcon->insert('usrlogs', 'sessionid, sysusr, login_time, logout_time, ip', '?,?,?,?,?', array($fpsession_id, $userId, $logintime, 0, $_SERVER["REMOTE_ADDR"]));
            if ($result === false) {
                $this->fpDBcon->getError();
                return false;
            }
            if ($result === false) {
                $this->fpDBcon->getError();
                return false;
            }

            setcookie("fpsessionid", $fpsession_id, $cktime, '/', $_SERVER["SERVER_NAME"], false, true);
            return true;
        }

        /**
         * Liefert Daten für aktuelle Session
         * @return array
         * @since FPCM2.2.0
         */
        private function getSessionData() {            
            $result = $this->fpDBcon->select('usrlogs', 'login_time, logout_time', "sessionid LIKE ?", array($this->getSessionCookieValue()));
            if ($result === false) {
                $this->fpDBcon->getError();
                return false;
            }
            return $this->fpDBcon->fetch($result);            
        }

        /**
         * Prüft, ob Config Key in Datenbank existiert
         * @param string $confKey
         * @return true
         * @since FPCM 2.3.0
         */
        private function configKeyExists($confKey) {
            $confKeyExists = $this->fpDBcon->count("config", "*", "config_name LIKE '$confKey'");
            return ($confKeyExists == 0) ? false : true;
        }

        /**
         * User-ID des aktuell eingeloggten Benutzers
         * @return string
         */
        public function getCurrentLoginUsr() {
            $result = $this->fpDBcon->select('usrlogs', 'sysusr', "sessionid LIKE ?", array($this->getSessionCookieValue()));
            if ($result === false) {
                $this->fpDBcon->getError();
                return false;
            }
            $row = $this->fpDBcon->fetch($result);
            if(!$row) { return false; }
            
            return $row->sysusr;
        }

        /**
         * Daten des aktuell eingeloggten Benutzers
         * @return string
         */
        public function getCurrentUser() {
            $tables = array('authors au', 'usrlogs ul');
            $fields = "au.name, au.email, au.sysusr, au.usrlevel, ul.login_time, au.id, au.usrmeta";
            $where  = "ul.sessionid LIKE ? AND au.id = ul.sysusr AND ul.logout_time = 0";            
            $params = array($this->getSessionCookieValue());
            
            $result = $this->fpDBcon->select($tables, $fields, $where, $params);
            if ($result === false) {
                $this->fpDBcon->getError();
                return false;
            }

            $row = $this->fpDBcon->fetch($result);

            if($row == false) { return false; }

            $return_usr = array(
                'name'      => $row->name,
                'sysuser'   => $row->sysusr,
                'logintime' => $row->login_time,
                'usrlevel'  => $row->usrlevel,
                'email'     => $row->email,
                'id'        => $row->id,
                'meta'      => $row->usrmeta
            );

            return $return_usr;
        }

        /**
         * Daten in Session Cookie ausgeben
         * @return string
         */
        public function getSessionCookieValue() {           
            return isset($_COOKIE["fpsessionid"]) ? fpSecurity::Filter5($_COOKIE["fpsessionid"]) : '';
        }

        /**
         * Logout einer Benutzers
         * @return void
         */
        public function UsrLogout() {
            $result = $this->fpDBcon->update('usrlogs', array('logout_time'), array(time(), $this->getSessionCookieValue()), "sessionid LIKE ?");
            if ($result === false) {
                $this->fpDBcon->getError();
                return false;
            }
            setcookie('fpsessionid', '', (time() - time() * 2));
        }

        /**
         * einzelne System-Option in DB aktualisieren
         * @param array $param
         * Struktur:
         *      'confname' => Name der Konfigurations-Wertes
         *      'confvalue' => Name der Konfigurations-Wertes
         * @return void
         */
        public function updateSingleSystemConfig($param) {            
            if($this->configKeyExists($param['confname']) && 
               $this->fpDBcon->update('config', array('config_value'), array($this->fpDBcon->parseSingleQuotes($param['confvalue'])), "config_name LIKE '{$param['confname']}'")
            ) {
                return true;
            }
            
            return false;
        }        

        /**
         * einzelne System-Option in DB erstellen
         * @param array $param Werte
         * Struktur:
         *      'config_name' => Name der Konfigurations-Wertes
         *      'config_value' => Name der Konfigurations-Wertes
         * @return void
         */
        public function createSingleSystemConfig($param) { 
            if(!in_array($param['config_name'], $this->undeletableConfig) && !$this->configKeyExists($param['config_name'])) {                    
                return $this->fpDBcon->insert('config', 'config_name, config_value', '?, ?', array($this->fpDBcon->parseSingleQuotes($param['config_name']), $this->fpDBcon->parseSingleQuotes($param['config_value'])));
            }
            
            return false;
        }
        
        /**
         * einzelne System-Option in DB LÖSCHEN
         * @param array $configToDelete Name des Konfig-Wertes, der gelöscht werden soll
         * @return void
         */
        public function deleteSingleSystemConfig($configToDelete) { 
            if(!in_array($configToDelete, $this->undeletableConfig) && $this->configKeyExists($configToDelete)) {
                return $this->fpDBcon->delete('config', 'config_name like ?', array($configToDelete));                 
            }
            
            return false;
        }        

        /**
         * Systemkonfiguration laden
         * @param string|array $config_name Name des Konfigurations-Schlüssels
         * @return string Wert des Konfigurations-Schlüssels  
         */
        public function loadSysConfig($config_name) {
            if(is_array($config_name)) {                
                $where  = "config_name IN ('".implode("','", $config_name)."')";
                $params = null;
            } else {
                $where  = "config_name LIKE ?";
                $params = array($config_name);
            }

            $result = $this->fpDBcon->select('config', '*', $where, $params);            
            if ($result === false) {
                $this->fpDBcon->getError();
            }
            $row = $this->fpDBcon->fetch($result);

            if(is_array($config_name)) {
                $return = array();                
                foreach ($row as $value) {
                    if(isset($value->config_value)) {
                        $return[$value->config_name] = @htmlspecialchars($value->config_value, ENT_COMPAT | ENT_HTML401, FPSPECIALCHARSET);
                    }
                }                
                return $return;
            } else {
                if(isset($row->config_value)) {
                    return @htmlspecialchars($row->config_value, ENT_COMPAT | ENT_HTML401, FPSPECIALCHARSET);
                } else {
                    return '';
                }
            }
        }

        /**
         * Systemstatistik ausgeben
         * @return array
         */
        public function getStats($countdb = true) {
            $stats = array();

            if($countdb) {
                $stats['newscount'] = $this->fpDBcon->count('newsposts', '*', 'is_deleted = 0');
                $stats['newcountprev'] = $this->fpDBcon->count('newsposts', '*', 'ispreview = 1 AND is_deleted = 0');
                $stats['newspostactive'] = $this->fpDBcon->count('newsposts', '*', 'is_archived = 0 AND is_deleted = 0');
                $stats['newspostarchive'] = $this->fpDBcon->count('newsposts', '*', 'is_archived = 1 AND is_deleted = 0');
                $stats['commentcount'] = $this->fpDBcon->count('comments');
                $stats['commentcountpriv'] = $this->fpDBcon->count('comments', '*', 'status <> 0');
                $stats['authorscount'] = $this->fpDBcon->count('authors');
                $stats['categorycount'] = $this->fpDBcon->count('categories');
                $stats['smiliescount'] = $this->fpDBcon->count('smilies');
                $stats['uploadcount'] = $this->fpDBcon->count('uploads');                
            }
            
            $cacheDir = opendir(FPCACHEFOLDER);
            $stats['cachesize'] = 0;
            while($cacheFile = @readdir($cacheDir)) {
                if($cacheFile == 'index.html' || $cacheFile == '.' || $cacheFile == '..') { continue; }
                $stats['cachesize'] += filesize(FPCACHEFOLDER.'/'.$cacheFile);                
            }
            
            if($stats['cachesize'] / 1024 / 1024 / 1024 > 1) {
                $stats['cachesize'] = round(($stats['cachesize'] / 1024 / 1024 / 1),2).'GB';
            } elseif($stats['cachesize'] / 1024 / 1024 > 1) {
                $stats['cachesize'] = round(($stats['cachesize'] / 1024 / 1024),2).'MB';
            } else {
                $stats['cachesize'] = round(($stats['cachesize'] / 1024),2).'KB';
            }
            
            $uploadDir = opendir(FPUPLOADFOLDER);
            $stats['uploadsize'] = 0;
            while($uploadFile = @readdir($uploadDir)) {
                if($uploadFile == 'index.html' || $uploadFile == '.' || $uploadFile == '..') { continue; }                
                $stats['uploadsize'] += filesize(FPUPLOADFOLDER.$uploadFile);                
            }
            if($stats['uploadsize'] / 1024 / 1024 / 1024 > 1) {
                $stats['uploadsize'] = round(($stats['uploadsize'] / 1024 / 1024 / 1),2).'GB';
            } elseif($stats['uploadsize'] / 1024 / 1024 > 1) {
                $stats['uploadsize'] = round(($stats['uploadsize'] / 1024 / 1024),2).'MB';
            } else {
                $stats['uploadsize'] = round(($stats['uploadsize'] / 1024),2).'KB';
            }            

            return $stats;
        }

        /**
         * gesperrte IP-Adressen anzeigen
         * @param mixed $ipid
         *      "all" => zeigt alle an
         *      eine ID => zeigt nur Eintrag mit ID an      
         */
        public function getBannedIP($ipid) {
            if ($ipid == "all") {
                $where  = "b.bann_by = aut.id";
                $params = null;                
            } else {
                $where  = "id LIKE ? AND b.bann_by = aut.id";
                $params = array($ipid);
            }

            $result = $this->fpDBcon->select(array('bannedips b', 'authors aut'), 'b.id, b.ip, b.bann_by, b.bann_time, aut.name', $where, $params);
            if ($result === false) {
                $this->fpDBcon->getError();
                return false;
            }

            return $result;
        }

        /**
         * IP-Adresse sperren
         * @param string $ip IP-Addresse
         * @return boolean
         */
        public function setIPBanned($ip) {
            $result = $this->fpDBcon->insert('bannedips', 'ip, bann_by, bann_time', '?, ?, ?', array($ip,fpConfig::currentUser('id'), time()));
            if ($result === false) {
                $this->fpDBcon->getError();
                return false;
            }
        }

        /**
         * IP-Adress-Sperre aufheben
         * @param int $ipid ID des Eintrags
         * @return boolean
         */
        public function deleteBannedIP($ipid) {
            return $this->fpDBcon->delete('bannedips', 'id = ?', array($ipid));
        }

        /**
         * auf System-Updates prüfen
         * @return void
         */
        public function checkForUpdates() {
            
            $updateData = array(
                'version'   => FPSYSVERSION,
                'lang'      => fpConfig::get('system_lang'),
                'auto'      => $this->canConnect()
            );
            
            $updateData = FPUPDATESCRIPT.'?data='.str_rot13(base64_encode(json_encode($updateData)));

            if ($this->canConnect()) {
                $fpUpdateCache = new fpCache('updatecheck');                
                if ($fpUpdateCache->isExpired()) {
                    $testSrv = @fsockopen(FPUPDATESERVER, "80");
                    if (!$testSrv) {
                        fpMessages::writeToSysLog("update error update server not available");
                        fpMessages::showSysNotice(LANG_UPDATE_SERVERFAILED);
                        fclose($testSrv);
                        return false;
                    }

                    $updateContent = file_get_contents($updateData);
                    if(!$updateContent) {
                        fpMessages::writeToSysLog("update error update failed");
                        fpMessages::showErrorText(LANG_UPDATE_ERROR);
                        fclose($testSrv);
                        return false;
                    }
                    
                    $fpUpdateCache->write($updateContent, FPCACHEEXPIRE);

                    fclose($testSrv);
                } else {
                    $updateContent = $fpUpdateCache->read();
                }
                
                $updateCheckData = json_decode(base64_decode(str_rot13($updateContent)), true);
                
                if(version_compare($updateCheckData['version'], FPSYSVERSION, '<')) {
                    print "<div class=\"version_dev\">".fpLanguage::returnLanguageConstant(LANG_SYS_UPDATESTATUS_DEVELOPER)."</div>";
                } elseif(version_compare($updateCheckData['version'], FPSYSVERSION, '>')) {
                    
                    $data   = base64_encode(json_encode(array("newversion" => $updateCheckData['version'], 'versionfile' => $updateCheckData['filepath'])));
                    
                    if($updateCheckData['force']) {
                        fpMessages::writeToSysLog('FPCM update: Forced to install FanPress CM update after update check!');
                        $fpUpdateCache->cleanup();
                        
                        $html   = array();
                        $html[] = "<div id=\"fpcm-force-update-notice\"><p>".fpLanguage::returnLanguageConstant(LANG_AUTO_UPDATE_AUTOSTART)."</p></div>";
                        $html[] = "<script type=\"text/javascript\">";
                        $html[] = "jQuery(function() {";
                        $html[] = "jQuery('.menu').menu({ disabled: true });";
                        $html[] = "showForceUpdateDialog();";
                        $html[] = "showLoader(true);";                        
                        $html[] = "relocate('../acp/auto-update.php?newver=$data&force=1');";
                        $html[] = "});";
                        $html[] = "</script>";
                        
                        print implode(PHP_EOL, $html);
                    }
                    
                    $msg    = array();
                    $msg[]  = "<div class=\"version_old\">";
                    $msg[]  = fpLanguage::returnLanguageConstant(LANG_SYS_UPDATESTATUS_OUTDATED);
                    $msg[]  = " <br><a class=\"btnloader fp-ui-button\" href=\"../acp/auto-update.php?newver=".$data."\">".fpLanguage::returnLanguageConstant(LANG_SYS_UPDATESTATUS_START)."</a>";
                    $msg[]  = " <div class=\"fp-small-text\">";
                    if($updateCheckData['message']) $msg[]  = $updateCheckData['message']." &bull; ";
                    if($updateCheckData['notice']) $msg[]  = " <a target=\"_blank\" href=\"".$updateCheckData['notice']."\">".fpLanguage::returnLanguageConstant(LANG_SYS_UPDATESTATUS_RELEASEINFO)."</a>";
                    $msg[]  = " </div>";                    
                    $msg[]  = "</div>";
                    
                    print implode(PHP_EOL, $msg);
                } else {
                    $msg    = array();
                    $msg[]  = "<div class=\"version_cur\">";
                    $msg[]  = fpLanguage::returnLanguageConstant(LANG_SYS_UPDATESTATUS_CURRENT);
                    $msg[]  = " <div class=\"fp-small-text\">";
                    if($updateCheckData['message']) $msg[]  = $updateCheckData['message']." &bull; ";
                    if($updateCheckData['notice']) $msg[]  = " <a target=\"_blank\" href=\"".$updateCheckData['notice']."\">".fpLanguage::returnLanguageConstant(LANG_SYS_UPDATESTATUS_RELEASEINFO)."</a>";
                    $msg[]  = " </div>";
                    $msg[]  = "</div>";

                    print implode(PHP_EOL, $msg);
                }
                
                return true;
            }
            
            print "<iframe scrolling=\"no\" src=\"" . $updateData . "\"></iframe>";
        }

        /**
         * auf Modul-Updates prüfen
         * @param string $localModules serialisierter String von installierten Modulen
         * @param bool $checkOnly soll nur geprüft werden, ob Updates verfügbar sind
         * @param bool $manual nur maneulles Update möglich?
         */
        public function checkForModuleUpdates($checkOnly = false) {         
            $updateData = array(
                'version'   => FPSYSVERSION,
                'lang'      => fpConfig::get('system_lang'),
                'auto'      => $this->canConnect(),
                'checkonly' => $checkOnly
            );
            
            $updateData = FPMODUPDATESCRIPT.'?data='.str_rot13(base64_encode(json_encode($updateData)));
            
            if ($this->canConnect()) {                        
                $fpUpdateCache = new fpCache('moduleupdatecheck');                
                if ($fpUpdateCache->isExpired()) {
                    $testSrv = @fsockopen(FPUPDATESERVER, "80");
                    if (!$testSrv) {
                        fpMessages::writeToSysLog("update error update server not available");
                        fpMessages::showSysNotice(LANG_UPDATE_SERVERFAILED);
                        fclose($testSrv);
                        return false;
                    }

                    $updateContent = file_get_contents($updateData);
                    if(!$updateContent) {
                        fpMessages::writeToSysLog("update error update failed");
                        fpMessages::showErrorText(LANG_UPDATE_ERROR);
                        fclose($testSrv);
                        return false;
                    }

                    $fpUpdateCache->write($updateContent, FPCACHEEXPIRE);

                    fclose($testSrv);
                } else {
                    $updateContent = $fpUpdateCache->read();
                }                    
                    
                $remoteModuleList   = json_decode(base64_decode(str_rot13($updateContent)), true);
                $localModuleList    = fpModules::getLocalModuleList();
                
                $updateStatus = false;
                foreach ($localModuleList as $moduleKey => $moduleInfo) {
                    if(!isset($remoteModuleList[$moduleKey])) {
                        $remoteModuleList[$moduleKey] = array('name' => $moduleInfo['name'], 'version' => '-', 'description' => $moduleInfo['name']);
                        continue;
                    }
                    
                    if(version_compare($remoteModuleList[$moduleKey]['version'], $moduleInfo['version'], '>')) {
                        $updateStatus = true;
                        $localModuleList[$moduleKey]['updateav'] = true;
                    }
                }
                
                if($checkOnly) {
                    if($updateStatus) {
                        $msg    = array();
                        $msg[]  = "<div class=\"version_old\">";
                        $msg[]  = fpLanguage::returnLanguageConstant(LANG_MODULES_UPDATE_AVAILABLE);
                        $msg[]  = "<br><a class=\"fp-ui-button\" href=\"../acp/modules.php\">";
                        $msg[]  = fpLanguage::returnLanguageConstant(LANG_MODULES_UPDATE_GOTO);
                        $msg[]  = "</a>";
                        $msg[]  = "</div>";

                        print implode(PHP_EOL, $msg);
                    } else {
                        print "<div class=\"version_cur\">".fpLanguage::returnLanguageConstant(LANG_MODULES_UPDATE_CURRENT)."</div>";
                    }
                }
                
                return array('locallist' => $localModuleList, 'remotelist' => $remoteModuleList);
            }
            
            if($checkOnly) {
                fpMessages::showErrorText(LANG_MODULE_MANUALLY);
                return;
            }
            
            print "<iframe style=\"width:100%;height:500px;border:0;\" seamless=\"seamless\" src=\"" . $updateData. "\"></iframe>";
        }

        /**
         * Können Verbindungen zu externen Servern hergestellt werden?
         * @return bool true, wenn Verbidnungen möglich
         */
        public function canConnect() {
            if(defined('FPCM_DISABLE_REMOTE')) return false;
            
            return (ini_get('allow_url_fopen') == 1) ? true : false;
        }


        /**
         * User-Log anschauen
         * @return mixed
         */
        public function showUserLog() {
            $result = $this->fpDBcon->select('usrlogs', '*', 'sessionid NOT LIKE ?', array($this->getSessionCookieValue()));
            if ($result === false) {
                $this->fpDBcon->getError();
                return false;
            }
            return $this->fpDBcon->fetch($result, true);
        }        
        
        /**
         * User Logs leeren   
         * @return void  
         */
        public function clearUsrLog() {            
            $timer = time() - fpConfig::get('session_length');
            $where = "sessionid NOT LIKE '{$this->getSessionCookieValue()}' AND logout_time >= 0 OR login_time < $timer";

            $result = $this->fpDBcon->delete('usrlogs', $where);
            if ($result === false) {
                $this->fpDBcon->getError();
                return false;
            }

            fpMessages::writeToSysLog("syslog user cleared");
        }

        /**
         * Erstellt eine Seitenavigation für aktive News und Archiv
         * 
         * @param bool $archive Navigation für News-Archiv?
         * @return string
         */
        public function createPageNavigation($archive = false) {
            $fpNewsObj = new fpNewsPost($this->fpDBcon);

            $plimit = 0;

            $showLimit  = fpConfig::get('news_show_limit');
            $sysUrl     = fpConfig::get('system_url');
            
            $codeCache = "<div class=\"fpress-pgnav\">";
            if ($archive) {
                $pagescount = ceil($fpNewsObj->countNewsPosts('archive') / fpConfig::get('news_show_limit'));
                if (isset($_GET["apid"])) { $curpage = (int) $_GET["apid"]; } else { $curpage = 0; }

                for ($i = 1; $i <= ceil($pagescount); $i++) {
                    if ($curpage == $plimit) {
                        if($plimit == 0) {
                            if(empty($sysUrl)) { $sysUrl = $_SERVER['SCRIPT_NAME']; }
                            $codeCache .= "<span class=\"fp-page-navi\"><a class=\"fp-page-navi-current\" href=\"" .$sysUrl. "?fn=archive\">$i</a></span> ";
                        } else {
                            $codeCache .= "<span class=\"fp-page-navi\"><a class=\"fp-page-navi-current\" href=\"" .$sysUrl. "?fn=archive&amp;apid=$plimit\">$i</a></span> ";
                        }
                    } else {
                        if($plimit == 0) {
                            if(empty($sysUrl)) { $sysUrl = $_SERVER['SCRIPT_NAME']; }
                            $codeCache .= "<span class=\"fp-page-navi\"><a href=\"" .$sysUrl. "?fn=archive\">$i</a></span> ";
                        } else {
                            $codeCache .= "<span class=\"fp-page-navi\"><a href=\"" .$sysUrl. "?fn=archive&amp;apid=$plimit\">$i</a></span> ";
                        }
                    }
                    $plimit += $showLimit;
                }
            } else {
                $pagescount = ceil($fpNewsObj->countNewsPosts() / $showLimit);
                if (isset($_GET["npid"])) { $curpage = (int) $_GET["npid"]; } else { $curpage = 0; }
                for ($i = 1; $i <= $pagescount; $i++) {
                    if ($curpage == $plimit) {
                        if($plimit == 0) {
                            if(empty($sysUrl)) { $sysUrl = $_SERVER['SCRIPT_NAME']; }
                            $codeCache .= "<span class=\"fp-page-navi\"><a class=\"fp-page-navi-current\" href=\"" .$sysUrl. "\">$i</a></span> ";
                        } elseif($plimit > 1 && $i > 1) {
                            $codeCache .= "<span class=\"fp-page-navi\"><a class=\"fp-page-navi-current\" href=\"" .$sysUrl. "?npid=$plimit\">$i</a></span> ";
                        }
                    } else {
                        if($plimit == 0) {
                            if(empty($sysUrl)) { $sysUrl = $_SERVER['SCRIPT_NAME']; }                             
                            $codeCache .= "<span class=\"fp-page-navi\"><a href=\"" .$sysUrl. " \">$i</a></span> ";
                        } elseif($plimit > 1 && $i > 1) {                                                  
                            $codeCache .= "<span class=\"fp-page-navi\"><a href=\"" .$sysUrl. "?npid=$plimit\">$i</a></span> ";
                        }
                    }
                    $plimit += $showLimit;
                }

                if (fpConfig::get('archive_link')) {
                    $codeCache .= " <span class=\"fp-page-navi\"><a href=\"" . isset($SELF_PHP) . "?fn=archive\">" . LANG_ARCHIVE_LINK_DESCRIPTION . "</a></span>";
                } else {
                    $codeCache .= "</span>";
                }
            }
            $codeCache .= "</div>";

            return $codeCache;
        }
        
        /**
         * Gibt Zeitzonen zurück
         * @return array
         * @since FPCM 2.5
         */
        public static function getTimeZones() {
            include_once FPBASEDIR."/lang/".fpConfig::get('system_lang')."/tz.php";
            
            $timezones = array();
            
            foreach ($time_zone_array as $timeZoneArea => $timeZoneAreaName) {
                $timezones[$timeZoneAreaName] = DateTimeZone::listIdentifiers($timeZoneArea);
            }
            return $timezones;
        }
    }
?>