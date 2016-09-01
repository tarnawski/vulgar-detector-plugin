<?php
/*

Plugin Name: VulgarDetector
Plugin URI: http://vulgardetector.ttarnawski.usermd.net
Description: Tool to recognition and block vulgar language in comments
Author: Tomasz Tarnawski
Version: 1.1
Author URI: http://ttarnawski.usermd.net
*/

class Vulgar_Detector
{
    const VERSION = '1.1';

    /**
     * Function for initializing this class
     * @static
     * @return void
     */
    public static function start()
    {
        add_filter('preprocess_comment', array(__CLASS__, 'filter_comments'));
    }

    public static function filter_comments($comment)
    {
        if (self::filter_comment_check($comment)) {

            self::mark_as_spam();
        }
        return $comment;
    }


    public static function filter_comment_check($comment)
    {
        if ($comment['comment_type']) {
            return false;
        }

        return Vulgar_Detector_Api::check($comment['comment_content']);
    }

    public static function mark_as_spam()
    {
        add_filter('pre_comment_approved', create_function('$a', 'return \'spam\';'));
    }
}

include plugin_dir_path( __FILE__ ) . 'classes/vulgar-detector-api.php';

Vulgar_Detector::start();
