<?php
    /**
     * FanPress CM Config class
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 2.4
     */

    final class fpConfig {

        private static $configData;
        
        private static $currentUser;
        
        private static $configKeys = array(
            'active_comment_template',
            'active_news_template',
            'anti_spam_answer',
            'anti_spam_question',
            'archive_link',
            'cache_timeout',
            'comlinkdescr',
            'comment_flood',
            'comments_enabled_global',
            'confirm_comments',
            'max_img_size_x',
            'max_img_size_y',
            'max_img_thumb_size_x',
            'max_img_thumb_size_y',
            'new_file_uploader',
            'news_show_limit',
            'revisions',
            'session_length',
            'showshare',
            'sort_news',
            'sort_news_order',
            'sys_jquery',
            'sysemailoptional',
            'system_editor',
            'system_lang',
            'system_mail',
            'system_url',
            'system_version',
            'time_zone',
            'timedate_mask',
            'twitter_access_token',
            'twitter_access_token_secret',
            'twitter_consumer_key',
            'twitter_consumer_secret',
            'useiframecss',
            'usemode',
            'use_trash'
        );

        /**
         * Initiiert Verbindung zu Datenbank
         * @since FPCM 2.4.0
         */
        public static function init() {
            if(!defined('ENT_HTML401'))      { define ('ENT_HTML401', 0); }
            if(!defined('FPSPECIALCHARSET')) { define ('FPSPECIALCHARSET', 'iso-8859-1'); }
            
            self::loadConfig();
        }

        /**
         * Aktualisiert Config
         * @since FPCM 2.4.0
         */        
        public static function update($newConfig) {
            
            global $fpDBcon;
            
            $fpSystem = new fpSystem($fpDBcon);
            
            foreach (self::$configKeys as $configKey) {                
                if(!isset($newConfig[$configKey])) continue;

                $conf = array('confname' => $configKey,'confvalue' => $newConfig[$configKey]);
                $fpSystem->updateSingleSystemConfig($conf);
                
                if($configKey == 'revisions' && !$newConfig[$configKey]) {
                    self::cleanRevisions();
                }
                
            }
            
            self::loadConfig();

            return true;
        }

        /**
         * Gibt aktuellen Benutzer und seine Daten zurück
         * @since FPCM 2.4.0
         */        
        public static function currentUser($data = null) {
            if(isset(self::$currentUser[$data])) return self::$currentUser[$data];
            
            return self::$currentUser;
        }

        /**
         * Gibt Config-Variable zurück
         * @since FPCM 2.4.0
         */        
        public static function get($configOption = null) {
            if(isset(self::$configData[$configOption])) return self::$configData[$configOption];
                
            return self::$configData;
        }
        
        /**
         * Prüft ob 'tiny_mce.custom.css' angelegt wurde
         * @since FPCM 2.5.0
         */        
        public static function hasTinyMceCss() {
            if(file_exists(FPBASEDIR.'/inc/tiny_mce.custom.css') && filesize(FPBASEDIR.'/inc/tiny_mce.custom.css') > 0) {
                return true;
            }
            
            return false;
        }

        /**
         * Lädt Config
         * @since FPCM 2.4.0
         */         
        private static function loadConfig() {
            
            global $fpDBcon;
            
            $fpSystem = new fpSystem($fpDBcon);
            
            self::$currentUser = $fpSystem->getCurrentUser();
            
            self::$configData = $fpSystem->loadSysConfig(self::$configKeys);

            $userMeta = json_decode(self::$currentUser['meta']);
            
            if(isset($userMeta->lang) && $userMeta->lang) {
                self::$configData['system_lang'] = $userMeta->lang;                
            }
            if(isset($userMeta->timezone) && $userMeta->timezone) {
                self::$configData['time_zone'] = $userMeta->timezone;                
            }
            if(isset($userMeta->timemask) && $userMeta->timemask) {
                self::$configData['timedate_mask'] = $userMeta->timemask;                
            }            
        }
        
        /**
         * Räumt Revisionen auf
         * @since FPCM 2.4.0
         */                 
        private static function cleanRevisions() {
            
            global $fpDBcon;
            $fpFS = new fpFileSystem($fpDBcon);
            
            $fpFS->deleteRecursive(FPREVISIONFOLDER);
            mkdir(FPREVISIONFOLDER);
            
        }
        
    }
