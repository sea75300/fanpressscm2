<?php
    /**
     * FanPress CM Installer MySQL query strings
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-*2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    $sql_strings = array(        
        "authors" => "
            CREATE TABLE IF NOT EXISTS %dbprefix%_authors (
            id int(11) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            registertime int(11) NOT NULL,
            sysusr varchar(255) NOT NULL,
            passwd varchar(255) NOT NULL,
            usrlevel int(1) NOT NULL,
            usrmeta text NOT NULL,
            PRIMARY KEY (id)
            ) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;             
        ",
        "categories" => "
            CREATE TABLE IF NOT EXISTS %dbprefix%_categories (
            id int(11) NOT NULL AUTO_INCREMENT,
            catname varchar(255) NOT NULL,
            icon_path varchar(255) NOT NULL,
            minlevel int(1) NOT NULL,
            PRIMARY KEY (id)
            ) DEFAULT CHARSET=latin1 AUTO_INCREMENT=2;              
        ",
        "comments" => "
            CREATE TABLE IF NOT EXISTS %dbprefix%_comments (
            id int(11) NOT NULL AUTO_INCREMENT,
            newsid int(11) NOT NULL,
            author_name varchar(255) NOT NULL,
            author_email varchar(255) NOT NULL,
            author_url varchar(255) NOT NULL,
            comment_text text NOT NULL,
            status tinyint(1) NOT NULL,
            comment_time int(11) NOT NULL,
            ip varchar(64) NOT NULL,
            PRIMARY KEY (id)
            ) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;               
        ",
        "config" => "
            CREATE TABLE IF NOT EXISTS %dbprefix%_config (
            id int(11) NOT NULL AUTO_INCREMENT,
            config_name varchar(255) NOT NULL,
            config_value varchar(255) NOT NULL,
            PRIMARY KEY (id)
            ) DEFAULT CHARSET=latin1 AUTO_INCREMENT=12;              
        ",
        "newsposts" => "
            CREATE TABLE IF NOT EXISTS %dbprefix%_newsposts (
            id int(11) NOT NULL AUTO_INCREMENT,
            category varchar(255) NOT NULL,
            author int(11) NOT NULL,
            titel varchar(255) NOT NULL,
            content text NOT NULL,
            ispreview tinyint(1) NOT NULL,
            is_archived tinyint(1) NOT NULL,
            is_pinned tinyint(1) NOT NULL,
            writtentime int(11) NOT NULL,
            editedtime int(11) NOT NULL,
            comments_active tinyint(1) NOT NULL,
            is_deleted tinyint(1) NOT NULL,
            PRIMARY KEY (id)
            ) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;              
        ",
        "permissions" => "
            CREATE TABLE IF NOT EXISTS %dbprefix%_permissions (
            id int(11) NOT NULL AUTO_INCREMENT,
            author_level_id int(11) NOT NULL,
            permissions text NOT NULL,
            PRIMARY KEY (id)
            ) DEFAULT CHARSET=latin1 AUTO_INCREMENT=4;            
        ",
        "smilies" => "
            CREATE TABLE IF NOT EXISTS %dbprefix%_smilies (
            id int(11) NOT NULL AUTO_INCREMENT,
            sml_code varchar(16) NOT NULL,
            sml_filename varchar(255) NOT NULL,
            PRIMARY KEY (id)
            ) DEFAULT CHARSET=latin1 AUTO_INCREMENT=39;            
        ",
        "uploads" => "
            CREATE TABLE IF NOT EXISTS %dbprefix%_uploads (
            id int(11) NOT NULL AUTO_INCREMENT,
            filename varchar(128) NOT NULL,
            uploaderid int(11) NOT NULL,
            uploadtime int(11) NOT NULL,
            PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;            
        ",
        "usrlevels" => "
            CREATE TABLE IF NOT EXISTS %dbprefix%_usrlevels (
            id int(11) NOT NULL AUTO_INCREMENT,
            leveltitle varchar(64) NOT NULL,
            PRIMARY KEY (id)
            ) DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;            
        ",
        "usrlogs" => "
            CREATE TABLE IF NOT EXISTS %dbprefix%_usrlogs (
            id int(11) NOT NULL AUTO_INCREMENT,
            sessionid varchar(255) NOT NULL,
            sysusr int(11) NOT NULL,
            login_time int(11) NOT NULL,
            logout_time int(11) NOT NULL,
            ip varchar(64) NOT NULL,
            PRIMARY KEY (id)
            ) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;            
        ",
        "bannesips" => "
            CREATE TABLE IF NOT EXISTS %dbprefix%_bannedips (
            id int(11) NOT NULL AUTO_INCREMENT,
            ip varchar(64) NOT NULL,
            bann_by int(11) NOT NULL,
            bann_time int(11) NOT NULL,
            PRIMARY KEY (id)
            ) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;            
        "
    );
?>
