<?php
    define('FPBASEDIR',dirname(dirname(__FILE__)));
    
    // standard charset
    define('FPSPECIALCHARSET', 'iso-8859-1');
    define('FPLOGSFOLDER', FPBASEDIR."/data/logs");
    
    require_once FPBASEDIR."/inc/functions.php";    
    require_once FPBASEDIR."/err.php";    
    require_once FPBASEDIR."/inc/config.php";

    // Autoload der Klassen
    spl_autoload_register('fpcmautoloader');

    $fpDBcon = new fpDB();
    
    global $fpDBcon;

    fpConfig::init();
    
    fpMessages::init($fpDBcon);
    fpModules::init($fpDBcon);