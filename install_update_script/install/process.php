<?php
    /**
     * FanPress CM Installer processing
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-*2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    if($step >= 2) {
        if(file_exists(FPBASEDIR."/inc/config.php")) {
            require_once FPBASEDIR."/inc/config.php";            
        }

        if(!defined("DBTYPE")) {
            $dbType = fpSecurity::Filter1($_POST["dbtype"]);
        } else {
            $dbType = DBTYPE;
        }

        switch ($dbType) {
            case "pgsql" :
                include 'querystrings_postgres.php';
            break;
            default:
                include 'querystrings_mysql.php';
            break;
        }        

        $fpInstallation = new fpInstallation($sql_strings);
    }    

    $conOK    = false;
    $fpDBcon = false;

    if($step >= 3) {
        $fpDBcon = $fpInstallation->getFpDBcon();
        global $fpDBcon;
        
        require_once (FPBASEDIR."/lang/".$lang."/tz.php");
        require_once (FPBASEDIR."/lang/".$lang."/commons.php"); 
        require_once (FPBASEDIR."/lang/".$lang."/forms.php"); 
        require_once (FPBASEDIR."/lang/".$lang."/editor.php"); 
        require_once (FPBASEDIR."/lang/".$lang."/sysoptions.php");         
    }

    switch($step) {
        case 1 :
            include ("01_dbcnt.php");
        break;
        case 2 :            
            $confData['dbserver'] = fpSecurity::Filter1($_POST["dbsrv"]);
            $confData['dbuser']   = fpSecurity::Filter1($_POST["dbuser"]);
            $confData['dbtype']   = fpSecurity::Filter1($_POST["dbtype"]);
            $confData['dbpasswd'] = fpSecurity::Filter1($_POST["dbpasswd"]);
            $confData['dbname']   = fpSecurity::Filter1($_POST["dbn"]);
            $confData['dbprefix'] = fpSecurity::Filter1($_POST["dbprefix"]);
            $confData['fproot']   = fpSecurity::Filter1($_POST["rootdir"]);
            $dsn = $confData['dbtype'].':dbname='.$confData['dbname'].';host='.$confData['dbserver'];	
            try {
                $fpDBcon = new PDO($dsn, $confData['dbuser'], $confData['dbpasswd']);
                $conOK = true;
            } catch(PDOException $e) {
                fpMessages::showErrorText(L_NODB.$e);
                $conOK = false;
            }       

            if($conOK) {                
                $fpInstallation->createFPConfigFile($confData);    
            }

            include "02_dbnext.php";
        break; 
        case 3:
            include "03_dbtabc.php"; 
        break;    
        case 4:
            include ("04_dbautt.php");
        break;    
        case 5:
            $unsecureUsernames = array("admin","root","test","support","administrator","adm");
            
            if(isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["usrname"]) && isset($_POST["password"])) {
                $username = fpSecurity::Filter2($_POST["usrname"]);
                if(in_array(strtolower($username), $unsecureUsernames)) {
                    fpMessages::showErrorText(str_replace('%lang%', $lang, L_SYS_UNSECURE_USERNAME));
                } else {
                    
                    $newpass         = fpSecurity::fpfilter(array('filterstring' => $_POST["password"]), array(1,4,2));
                    $newpass_confirm = fpSecurity::fpfilter(array('filterstring' => $_POST["password_confirm"]), array(1,4,2));        

                    if(md5($newpass) != md5($newpass_confirm)) {
                        fpMessages::showErrorText(str_replace('%lang%', $lang, L_ADMINPASSWDCONFIRM));
                        break;
                    }                    

                    $fpUser = new fpAuthor();
                    $fpUser->setUserName($username);
                    $fpUser->setEmail(fpSecurity::fpfilter(array('filterstring' => $_POST["email"]), array(1,4,2)));
                    $fpUser->setUserRoll(1);
                    $fpUser->setDisplayName(fpSecurity::fpfilter(array('filterstring' => $_POST["name"]), array(1,4,2)));
                    $fpUser->setPassword($newpass);
                    $fpUser->setUserMeta(array());
                    $fpUser->setRegistertime(time());
                    
                    if($fpUser->save() === -2) {
                        fpMessages::showErrorText(L_ADMINPASSWDSEC);
                        break;                        
                    }
                }
            }
            
            $timezones = array();
            
            foreach ($time_zone_array as $timeZoneArea => $timeZoneAreaName) {
                $timezones[$timeZoneAreaName] = DateTimeZone::listIdentifiers($timeZoneArea);
            }
            
            include ("05_sysconfig.php");
        break;        
        case 6:
            $data = array(
                'system_lang'               => fpSecurity::Filter8($_POST["syslang"]),
                'system_url'                => fpSecurity::Filter8($_POST["sysurl"]),
                'system_mail'               => fpSecurity::Filter8($_POST["sysmail"]),
                'active_news_template'      => 'news',
                'active_comment_template'   => 'comments',
                'timedate_mask'             => fpSecurity::Filter8($_POST["sysdtmask"]),
                'session_length'            => "3600",
                'confirm_comments'          => '1',
                'time_zone'                 => fpSecurity::Filter8($_POST["systimezone"]),
                'cache_timeout'             => '86400',
                'system_editor'             => fpSecurity::Filter8($_POST["sys_editor"]),
                'max_img_size_x'            => '500',
                'max_img_size_y'            => '500',
                'max_img_thumb_size_x'      => '175',
                'max_img_thumb_size_y'      => '175',
                'new_file_uploader'         => fpSecurity::Filter8($_POST["new_file_uploader"]),                
                'anti_spam_question'        => fpSecurity::Filter8($_POST["sysatsq"]),
                'anti_spam_answer'          => fpSecurity::Filter8($_POST["sysatsaw"]),
                'sysemailoptional'          => '1',                    
                'news_show_limit'           => fpSecurity::Filter8($_POST["news_show_limit"]),
                'usemode'                   => fpSecurity::Filter8($_POST["sysusemode"]),
                'comment_flood'             => '30',
                'useiframecss'              => '',
                'archive_link'              => '1',
                'sys_jquery'                => '0',
                'showshare'                 => '1',
                'comlinkdescr'              => 'Comment(s)',
                'revision'                  => '1',
                'sort_news'                 => 'writtentime',
                'sort_news_order'           => 'DESC',
                'comments_enabled_global'   => '1'
            );
            
            fpConfig::update($data);

            include ("06_finished.php");

            fpMessages::clearLogs(1);
            fpMessages::clearLogs(2);
        break;    
        default :
            include ("00_start.php");
        break;
    }
?>