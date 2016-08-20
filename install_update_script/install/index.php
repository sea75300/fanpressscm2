<?php
    /**
     * FanPress CM Installer
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */  

    define('FPCM_INSTALL', true);

    define('FPBASEDIR',dirname(dirname(__FILE__)));
    
    define('FPBASEURL', '//'.$_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF'])).'/');

    if(!defined('ENT_HTML401'))      { define ('ENT_HTML401', 0); }
    if(!defined('FPSPECIALCHARSET')) { define ('FPSPECIALCHARSET', 'iso-8859-1'); }
    
    require_once FPBASEDIR.'/inc/functions.php';
    require_once FPBASEDIR."/err.php";    
    require_once FPBASEDIR.'/inc/central.php';
    if(file_exists(dirname(dirname(__FILE__)).'/inc/central.custom.php')) { require_once dirname(dirname(__FILE__)).'/inc/central.custom.php'; }

    spl_autoload_register('fpcmautoloader');

    define("FPCACHEFOLDER", $fpcachefolder);   
    define("FPUSRPASSWORDREGEX", $fppasswdregex);         
    
    spl_autoload_register('fpcminstallautoloader');
    function fpcminstallautoloader($class) {
        if(file_exists(FPBASEDIR.'/inc/classes/c_'.strtolower($class).'.php')) {
            require_once FPBASEDIR.'/inc/classes/c_'.strtolower($class).'.php';            
        } else {
            if(file_exists(__DIR__.'/c_'.strtolower($class).'.php')) {
                require_once __DIR__.'/c_'.strtolower($class).'.php';            
            } else {
                print ("FATAL ERROR: Loading class ".$class." failed."); 
            }            
        }        
    }
    
    if (isset($_GET["isstep"])) { $step = $_GET["isstep"]; } else { $step = 0; } 
    
    $maxSteps = 6;       
    
    if($step >= 1) {
        $lang = fpSecurity::Filter5($_GET["ilang"]);
        include_once ("ilang_".$lang.".php");
    }    
    
    $installStepStrings = array(
        0 => "Select a language",
        1 => L_DBCONNECTION,
        2 => L_CREATECONFIG,
        3 => L_CREATETABELS,
        4 => L_CREATEADMIN,
        5 => L_CONFIGSYS,
        6 => L_INSTALL_FINISHED
    );
?>
<!DOCTYPE HTML>
<HTML>
    <head>
        <title>FanPress CM News System 2 &bull; Install Assistant</title>
        <meta http-equiv="Content-Language" content="de">
        <meta http-equiv="content-type" content= "text/html; charset=iso-8859-1">
        <meta name="robots" content="noindex, nofollow">  
        <link rel="shortcut icon" href="../sysstyle/favicon.png" type="image/png" /> 
        <?php fpView::getStyleCssFiles(); ?>        
        <?php fpView::getStyleJsFiles(); ?>       
    </head> 

  <body id="body">
        <div class="wrapper">
            <div id="header" class="header">
                <div class="header-inner">
                    <div class="header-td1">
                        <div class="fp-logo-img"><img class="fp-logo" src="../sysstyle/syslogo.png" alt="FanPress CM"></div>
                        <div class="fp-logo-text"><span>FanPress CM</span> <span>News System</span></div>
                        <div class="clear"></div>
                    </div>               
                    <div class="clear"></div>
                </div>                           
            </div>

            <div style="height: 55px;"></div>
            
            <div class="content-wrapper content-wrapper-nonav">
                <div class="box box-fixed-margin" id="contentbox" style="padding-left: 15px;">
                    
                        <h1>Install Assistant</h1> 


                        <div id="fp-install-progressbar" style="margin-bottom: 15px;"></div>
                        <script>
                            jQuery(function() {
                                jQuery( "#fp-install-progressbar" ).progressbar({
                                    value: <?php print floor(100 * $step / $maxSteps); ?>
                                });
                            });
                        </script>                             

                        <div id="tabsGeneral">
                            <ul>
                                <li><a href="#tabs-install">#<?php print $step; ?> <?php print $installStepStrings[$step]; ?></a></li>
                            </ul>

                            <div id="tabs-install" style="text-align:center;">    
                                <?php require_once 'process.php'; ?>
                            </div>
                        </div>

                </div>                
            </div>

        </div>
        
        <div class="footer ui-widget-content ui-corner-all ui-state-normal">&copy; 2011-2014 <a href="http://nobody-knows.org/download/fanpress-cm/" target="_blank">nobody-knows.org</a></div>        

        <script type="text/javascript">
            jQuery("select").chosen({ disable_search: true, inherit_select_classes: true });
        </script>
        
    </body>
</html>