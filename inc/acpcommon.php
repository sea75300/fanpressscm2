<?php 
    /**
     * FanPress CM ACP common
     * @author Stefan Seehafer aka image <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    require_once dirname(__FILE__).'/init.php';
    require_once dirname(__FILE__).'/central.php';    
    if(file_exists(dirname(__FILE__).'/central.custom.php')) { require_once dirname(__FILE__).'/central.custom.php'; }
    
    fpSecurity::init($fpDBcon);
    
    if(fpConfig::currentUser()) { define('LOGGED_IN_USER',true); } else { define('LOGGED_IN_USER',false); } 
    
    define('FPCACHEFOLDER', $fpcachefolder);
    define('FPUPLOADFOLDER', FPBASEDIR.$fpuploadfolder);
    define('FPUPLOADFOLDERURL', '//'.$_SERVER['HTTP_HOST'].FP_ROOT_DIR.ltrim($fpuploadfolder, '/'));
    define('FPREVISIONFOLDER', FPDATAFOLDER.'/revisions/');
    define('FPUSRPASSWORDREGEX', $fppasswdregex);
    define('FPOPTIONSDTMASKSAC', json_encode($fpoptiondtmasksac));
    define('FPSYSVERSION', fpConfig::get('system_version'));    
    define('FPCACHEEXPIRE', fpConfig::get('cache_timeout')); 
    define('FPUPGRADEFOLDER', FPBASEDIR.'/upgrade/');
    
    unset($fpcachefolder, $fppasswdregex, $fpuploadfolder);

    $fpSystem       = new fpSystem($fpDBcon);
    $fpCache        = new fpCache();    
    $fpUser         = new fpUser($fpDBcon);
    $fpNews         = new fpNewsPost($fpDBcon);
    $fpCategory     = new fpCategory($fpDBcon);    
    $fpComment      = new fpComments($fpDBcon);
    $fpFileSystem   = new fpFileSystem($fpDBcon);
    
    $fpLanguage     = new fpLanguage(fpConfig::get('system_lang'));
    
    $fpLanguage->loadLanguageFiles();

    $langReplaceDataArray = array(
        'Admin'  => LANG_USR_LEVEL_ADMIN,
        'Editor' => LANG_USR_LEVEL_EDITOR,
        'Autor'  => LANG_USR_LEVEL_AUTHOR
    );     
    
    // Zeitzone setzen
    date_default_timezone_set(fpConfig::get('time_zone'));  
    
    // Module laden
    $moduleConfArray = fpModules::includeAllACPModules();   

    $mobileDetect = new Mobile_Detect();
    
    // Header von System-Skin
    if(!defined('NO_HEADER')) { include_once FPBASEDIR.'/sysstyle/sysheader.php'; }
