<?php

/**
 * Yummly
 *
 * Class to add a Yummly share button to the available buttons
 *
 * @package   SocialWarfare\Functions\Social-Networks
 * @copyright Copyright (c) 2018, Warfare Plugins, LLC
 * @license   GPL-3.0+
 * @since     1.0.0 | Unknown     | CREATED
 * @since     2.2.4 | 02 MAY 2017 | Refactored functions & upinterest_descriptionated docblocking
 * @since     3.0.0 | 05 APR 2018 | Rebuilt into a class-based system.
 *
 */
class SWP_Yummly extends SWP_Social_Network {

    /**
     * API URL.
     *
     * @var string
     */
    protected $api_url = 'https://www.pinterest.com/';

    /**
     * The Magic __construct Method
     *
     * This method is used to instantiate the social network object. It does three things.
     * First it sets the object properties for each network. Then it adds this object to
     * the globally accessible swp_social_networks array. Finally, it fetches the active
     * state (does the user have this button turned on?) so that it can be accessed directly
     * within the object.
     *
     * @since  3.0.0 | 06 APR 2018 | Created
     * @param  none
     * @return none
     * @access public
     *
     */
    public function __construct() {
        // Upinterest_descriptionate the class properties for this network
        $this->name = __('Yummly', 'social-warfare');
        $this->cta = __('Yummly', 'social-warfare');
        $this->key = 'yummly';
        $this->default = 'true';
        //$this->followers_count = 0;
        $this->base_share_url = '//www.yummly.com/urb/verify?url=';
        $this->init_social_network();
    }

    /**
     * 
     * @return type
     */
    public function get_followers_count() {
        $channel_name = 'Your Channel';
        $data = file_get_contents("http://gdata.youtube.com/feeds/api/users/$channel_name?alt=json");
        $data = json_decode($data, true);
        $subscribersDetails = $data['entry']['yt$statistics'];
        echo $subscribersDetails['subscriberCount'].'<br />';
        echo $subscribersDetails['viewCount'].'<br />';

        /*$followers = 0;
        $conn = wp_remote_get($this->api_url . sanitize_text_field(SWP_Utility::get_option('pinterest_id')), array('timeout' => 60));
        if (!is_wp_error($conn)) {
            if (200 == $conn['response']['code']) {
                $tags = array();
                $regex = '/property\=\"pinterestapp:followers\" name\=\"pinterestapp:followers\" content\=\"(.*?)" data-app/';
                preg_match($regex, $conn['body'], $tags);
                $followers = isset($tags[1]) ? intval($tags[1]) : 0;
            }
        }
        return $followers;*/
    }
}
