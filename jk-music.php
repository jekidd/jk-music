<?php
/*
Plugin Name:  JK Music Library
Plugin URI:   https://github.com/jekidd/jk-music.git
Description:  A plugin for creating and managing a WordPress music library.
Version:      1.0
Author:       Jordan Kidd
Author URI:   https://www.jekidd.com/
*/

/**
 * Function that configures the plugin on activation
 *
 * @return void
 */
function activateJKM() {
    installDatabase();
}
register_activation_hook( __FILE__, "activateJKM" );


/**
 * Function that installs the required database(s)
 *
 * @return void
 */
function installDatabase() {
    /**
     * Variable declarations
     */
    global $wpdb;
    $table_name = $wpdb->prefix . "jkm_records";
    $charset_collate = $wpdb->get_charset_collate();
    $jkm_db_version = "1.04";

    /**
     * Create a new table if it doesn't exist
     */
    if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
        $sql = "CREATE TABLE $table_name (
      		id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) DEFAULT '' NOT NULL,
            artist varchar(255) DEFAULT '' NOT NULL,
            fm_id int(255) NOT NULL,
            fm_mbid varchar(255) DEFAULT '' NOT NULL,
            url varchar(100) DEFAULT '' NOT NULL,
            releasedate datetime DEFAULT '00-00-0000 00:00' NOT NULL,
            cover varchar(255) DEFAULT '' NOT NULL,
            rating int(1) DEFAULT 3 NOT NULL,
      		PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . "wp-admin/includes/upgrade.php" );
        dbDelta( $sql );

        add_option( "jkm_db_version", $jkm_db_version );
    }

    /**
     * Upgrade database if necessary
     */
    $installed_ver = get_option( "jkm_db_version" );

    if ($installed_ver != $jkm_db_version) {
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) DEFAULT '' NOT NULL,
            artist varchar(255) DEFAULT '' NOT NULL,
            fm_id int(255) NOT NULL,
            fm_mbid varchar(255) DEFAULT '' NOT NULL,
            url varchar(100) DEFAULT '' NOT NULL,
            releasedate datetime DEFAULT '00-00-0000 00:00' NOT NULL,
            cover varchar(255) DEFAULT '' NOT NULL,
            rating int(1) DEFAULT 3 NOT NULL,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . "wp-admin/includes/upgrade.php" );
        dbDelta( $sql );

        update_option( "jkm_db_version", $jkm_db_version );
    }
}
