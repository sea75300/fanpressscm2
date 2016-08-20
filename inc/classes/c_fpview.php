<?php
    /**
     * FanPress CM view class
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 2.4
     */

    final class fpView {
        
        public static $csslib = array(
            'js/jquery-ui/jquery-ui.min.css',
            'js/fancybox/jquery.fancybox.css',
            'inc/lib/font-awesome/css/font-awesome.min.css',
            'inc/lib/chosen/chosen.min.css',
            'sysstyle/fpsystem.css'
        );
        
        public static $jslib = array(
            'js/jquery/jquery-1.11.1.min.js',
            'js/jquery-ui/jquery-ui.min.js',
            'js/fancybox/jquery.fancybox.pack.js',
            'inc/lib/chosen/chosen.jquery.min.js',
            'inc/lib/spinjs/spin.min.js',
            'inc/lib/spinjs/jquery.spin.js',
            'js/common.js'
        );
        
        public static $cmJsFiles = array(
            'lib/codemirror.js',
            'addon/selection/active-line.js',
            'addon/edit/matchbrackets.js',
            'addon/edit/matchtags.js',
            'addon/edit/closetag.js',
            'addon/fold/xml-fold.js',
            'addon/hint/show-hint.js',
            'addon/hint/xml-hint.js',
            'addon/hint/html-hint.js',
            'mode/xml/xml.js',
            'mode/javascript/javascript.js',
            'mode/css/css.js',
            'mode/htmlmixed/htmlmixed.js'
        );
        
        public static $cmCssFiles = array(
            'lib/codemirror.css',
            'theme/mdn-like.css',
            'addon/hint/show-hint.css'
        );

        /**
         * Gibt Style-CSS-Dateien zurück
         * @static
         * @since FPCM 2.5
         */
        public static function getStyleCssFiles() {
            if(defined('FILEMANAGER') && fpConfig::get('new_file_uploader') == 1) {
                self::$csslib[] = "inc/lib/jqupload/css/jquery.fileupload.css";
                self::$csslib[] = "inc/lib/jqupload/css/jquery.fileupload-ui.css";
                self::$csslib[] = array('<noscript>', 'inc/lib/jqupload/css/jquery.fileupload-noscript.css', '</noscript>');
                self::$csslib[] = array('<noscript>', 'inc/lib/jqupload/css/jquery.fileupload-ui-noscript.css', '</noscript>');
            }

            $moduleFiles = fpModuleEventsAcp::runAddToHeaderCss();
            if(count($moduleFiles)) self::$csslib = array_merge(self::$csslib, $moduleFiles);
                        
            $html = array();
            foreach (self::$csslib as $cssFile) {
                if(is_array($cssFile)) {
                    $html[] = " ".$cssFile[0]."<link rel=\"stylesheet\" type=\"text/css\" href=\"".FPBASEURL.$cssFile[1]."\">".$cssFile[2];
                } else {
                    $cssFile = str_replace(FP_ROOT_DIR, '', $cssFile);
                    $html[] = "     <link rel=\"stylesheet\" type=\"text/css\" href=\"".FPBASEURL.$cssFile."\">";                    
                }
            }
            
            print implode("\n", $html);               
        }
        
        /**
         * Gibt Style-JS-Dateien zurück
         * @static
         * @since FPCM 2.5
         */        
        public static function getStyleJsFiles() {
            $html   = array();
            $moduleFiles = fpModuleEventsAcp::runAddToHeaderJs();
            if(count($moduleFiles)) self::$jslib = array_merge(self::$jslib, $moduleFiles);
            
            foreach (self::$jslib as $jsFile) {
                $jsFile = str_replace(FP_ROOT_DIR, '', $jsFile);
                $html[] = "     <script type=\"text/javascript\" src=\"".FPBASEURL.$jsFile."\"></script>";
            }
            
            print implode("\n", $html)."\n";            
        }
        
        /**
         * Baut Navigation auf
         * @return array
         * @since FPCM 2.4
         */
        public static function getNavigation() {
            
            $navigationArray = array(
                'addnews'      => array(
                    array(
                        'url'               => FPBASEURLACP.'addnews.php',
                        'permission'        => array('addnews'),
                        'description'       => LANG_WRITE,
                        'icon'              => 'ui-icon ui-icon-pencil',
                        'class'             => '',
                        'id'                => ''                        
                    )
                ),
                'editnews'      => array(
                    array(
                        'url'               => FPBASEURLACP.'#',
                        'permission'        => array('editnews'),
                        'description'       => LANG_EDIT,
                        'icon'              => 'ui-icon ui-icon-note',
                        'submenu'           => self::editorSubmenu(),
                        'class'             => 'admin-nav-noclick',
                        'id'                => 'nav-id-editnews'                        
                    )
                ),
                'comments'   => array(
                    array(
                        'url'               => FPBASEURLACP.'editcomments.php',
                        'permission'        => array('editcomments', 'editnews'),
                        'description'       => LANG_EDIT_COMMENTS,
                        'icon'              => 'ui-icon ui-icon-comment',
                        'class'             => '',
                        'id'                => ''                        
                    )
                ),
                'filemanager'   => array(
                    array(
                        'url'               => FPBASEURLACP.'filemanager.php?mode=2',
                        'permission'        => array('addnews', 'editnews', 'upload'),
                        'description'       => LANG_UPLOAD_FILEMANAGER,
                        'icon'              => 'ui-icon ui-icon-folder-open',
                        'class'             => '',
                        'id'                => ''                        
                    )
                ),
                'options'       => array(
                    array(
                        'url'               => FPBASEURLACP.'#',
                        'permission'        => array('system'),
                        'description'       => LANG_OPTIONS,
                        'icon'              => 'ui-icon ui-icon-gear',
                        'class'             => 'admin-nav-noclick',
                        'id'                => '',
                        'submenu'           => self::optionSubmenu()                        
                    )
                ),
                'modules'       => array(
                    array(
                        'url'               => FPBASEURLACP.'#',
                        'permission'        => array('modules'),
                        'description'       => LANG_SYSCFG_MODULES,
                        'icon'              => 'ui-icon ui-icon-plusthick',
                        'class'             => '',
                        'id'                => '',
                        'submenu'           => self::modulesSubmenu()                        
                    )
                ),
                'help'          => array(
                    array(
                        'url'               => FPBASEURLACP.'help.php',
                        'description'       => LANG_HELP,
                        'icon'              => 'ui-icon ui-icon-help',
                        'class'             => '',
                        'id'                => ''                        
                    )
                ),
                'after'         => array()
            );
            
            $moduleItems = fpModuleEventsAcp::runAddToNavigationMain();
            
            foreach ($navigationArray as $key => $value) {
                if(isset($moduleItems[$key])) {
                    $navigationArray[$key] = array_merge($value, $moduleItems[$key]);
                }
            }
            
            return $navigationArray;
            
        }
        
        /**
         * Erzeugt Submenü für News bearbeiten
         * @return array
         * @since FPCM 2.4
         */
        private static function editorSubmenu() {
            return array(
                array(
                    'url'               => FPBASEURLACP.'editnews.php',
                    'permission'        => array('editnews'),
                    'description'       => LANG_EDIT_SUB_ALL,
                    'class'             => '',
                    'id'                => '' 
                ),
                array(
                    'url'               => FPBASEURLACP.'editnews.php?fn=editactive',
                    'permission'        => array('editnews'),
                    'description'       => LANG_EDIT_SUB_ACTIVE,
                    'class'             => '',
                    'id'                => '' 
                ),
                array(
                    'url'               => FPBASEURLACP.'editnews.php?fn=editarchive',
                    'permission'        => array('editnewsarchive'),
                    'description'       => LANG_EDIT_SUB_ARCHIVE,
                    'class'             => '',
                    'id'                => '' 
                )                
            );
        }
        
        /**
         * Erzeugt Optionen-Submenü
         * @return array
         * @since FPCM 2.4
         */
        private static function optionSubmenu() {
            return array(
                array(
                    'url'               => FPBASEURLACP.'sysconfig.php?mod=system',
                    'permission'        => array('system'),
                    'description'       => LANG_SYSCFG_OPTIONS,
                    'class'             => '',
                    'id'                => '' 
                ),
                array(
                    'url'               => FPBASEURLACP.'sysconfig.php?mod=users',
                    'permission'        => array('user'),
                    'description'       => LANG_USR,
                    'class'             => '',
                    'id'                => '' 
                ),
                array(
                    'url'               => FPBASEURLACP.'sysconfig.php?mod=permissions',
                    'permission'        => array('permissions'),
                    'description'       => LANG_SYSCFG_PERMISSIONS,
                    'class'             => '',
                    'id'                => '' 
                ),
                array(
                    'url'               => FPBASEURLACP.'sysconfig.php?mod=ipbann',
                    'permission'        => array('system'),
                    'description'       => LANG_SYSCFG_IPBANN,
                    'class'             => '',
                    'id'                => '' 
                ),
                array(
                    'url'               => FPBASEURLACP.'sysconfig.php?mod=category',
                    'permission'        => array('category'),
                    'description'       => LANG_SYSCFG_CATEGORIES,
                    'class'             => '',
                    'id'                => '' 
                ),
                array(
                    'url'               => FPBASEURLACP.'sysconfig.php?mod=templates',
                    'permission'        => array('templates'),
                    'description'       => LANG_SYSCFG_TEMPLATES,
                    'class'             => '',
                    'id'                => '' 
                ),
                array(
                    'url'               => FPBASEURLACP.'sysconfig.php?mod=smilies',
                    'permission'        => array('smilies'),
                    'description'       => LANG_SYSCFG_SMILIES,
                    'class'             => '',
                    'id'                => '' 
                ),
                array(
                    'url'               => FPBASEURLACP.'sysconfig.php?mod=syslog',
                    'permission'        => array('system'),
                    'description'       => LANG_SYSCFG_SYSLOG,
                    'class'             => '',
                    'id'                => '' 
                )

            );
        }
        
        /**
         * Erzeugt Submenü in Module
         * @return array
         * @since FPCM 2.4
         */
        private static function modulesSubmenu() {
            $items = array(
                array(
                    'url'               => FPBASEURLACP.'modules.php',
                    'permission'        => array('system'),
                    'description'       => LANG_SYSCFG_MODULES_MGR,                    
                    'class'             => '',
                    'id'                => '',
                    'spacer'            => true
                )
            );
            
            return array_merge($items, fpModuleEventsAcp::runAddToNavigation());
        }
        
    }
