<?php  
    /**
     * Fehler Anzeigen
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */

    if(defined("FPCM_DEBUG") && FPCM_DEBUG) {
        error_reporting (E_ALL | E_STRICT);
    } else {
        error_reporting (0);
    }

    /**
     * Error Handler
     * @since FPCM 2.1
     */
    set_error_handler("fpcmerrorhandler");   
?>