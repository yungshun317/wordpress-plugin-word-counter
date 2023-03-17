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
            register_setting( 'word_counter_group', 'word_counter_location', array( 'sanitize_callback' => array( $this, 'sanitize_location'), 'default' => '0' ) );

            add_settings_field( 'word_counter_headline', 'Headline Text', array( $this, 'headline_html' ), 'word_counter', 'word_counter_section' );
            register_setting( 'word_counter_group', 'word_counter_headline', array( 'sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics' ) );

            add_settings_field( 'word_counter', 'Word Count', array( $this, 'checkbox_html' ), 'word_counter', 'word_counter_section', array( 'name' => 'word_counter_character_count' ) );
            register_setting( 'word_counter_group', 'word_counter', array( 'sanitize_callback' => 'sanitize_text_field', 'default' => '1' ) );

            add_settings_field( 'word_counter_character_count', 'Character Count', array( $this, 'checkbox_html' ), 'word_counter', 'word_counter_section' );
            register_setting( 'word_counter_group', 'word_counter_character_count', array( 'sanitize_callback' => 'sanitize_text_field', 'default' => '1' ) );

            add_settings_field( 'word_counter_read_time', 'Read Time', array( $this, 'checkbox_html' ), 'word_counter', 'word_counter_section', array( 'name' => 'word_counter_read_time' ) );
            register_setting( 'word_counter_group', 'word_counter_read_time', array( 'sanitize_callback' => 'sanitize_text_field', 'default' => '1' ) );
        }

        function sanitize_location( $input ) {
            if ( $input != '0' AND $input != '1' ) {
                add_settings_error( 'word_counter_location', 'word_counter_location_error', 'Display location must be either beginning or end.' );
                return get_option( 'word_counter_location' );
            }
            return $input;
        }

        /*
        function word_count_html( $args ) {
            ?>
            <input type="checkbox" name="word_counter_word_count" value="1" <?php checked( get_option( "word_counter_word_count" ), '1' ) ?>>
            <?php
        }

        function character_count_html( $args ) {
            ?>
            <input type="checkbox" name="word_counter_character_count" value="1" <?php checked( get_option( "word_counter_character_count" ), '1' ) ?>>
            <?php
        }

        function read_time_html( $args ) {
            ?>
            <input type="checkbox" name="word_counter_read_time" value="1" <?php checked( get_option( "word_counter_read_time" ), '1' ) ?>>
            <?php
        }
        */

        // Reusable checkbox function
        function checkbox_html( $args ) {
            ?>
            <input type="checkbox" name="<?php echo $args['name'] ?>" value="1" <?php checked( get_option( $args['name'] ), '1' ) ?>>
            <?php
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