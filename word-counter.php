<?php

/*
 * Plugin Name: Word Counter
 * Plugin URI: https://www.wordpress.org/word-counter
 * Description: My plugin's description
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Chouqin Info Co.
 * Author URI: https://www.chouqin.com.tw
 * Text Domain: word-counter
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Word_Counter' ) ) {
    class Word_Counter {
        function __construct() {
            add_action( 'admin_menu', array( $this, 'admin_page' ) );
            add_action( 'admin_init', array( $this, 'settings' ) );
        }

        function settings() {
            add_settings_section( 'word_counter_section', null, null, 'word_counter' );
            add_settings_field( 'word_counter_location', 'Display Location', array( $this, 'location_html' ), 'word_counter', 'word_counter_section' );
            register_setting( 'word_counter_group', 'word-counter-location', array( 'sanitize_callback' => 'sanitize_text_field', 'default' => '0' ) );
        }

        function location_html() {
            ?>
            <select name="word_counter_location">
                <option value="0">Beginning of Post</option>
                <option value="1">End of Post</option>
            </select>
            <?php
        }

        function admin_page() {
            add_options_page( 'Word Counter Settings', 'Word Counter', 'manage_options', 'word_counter_settings_page', array( $this, 'settings_html' ) );
        }

        function settings_html() {
            ?>
            <div class="wrap">
                <h1>Word Counter Settings</h1>
                <form action="options.php" method="POST"></form>
                <?php
                    settings_fields('word_counter_group' );
                    do_settings_sections( 'word_counter_section' );
                    submit_button();
                ?>
            </div>
            <?php
        }
    }

    $word_counter = new Word_Counter();
}