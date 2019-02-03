<?php

/**
 * Facebook
 *
 * Class to add a Facebook share button to the available buttons
 *
 * @package   SocialWarfare\Functions\Social-Networks
 * @copyright Copyright (c) 2018, Warfare Plugins, LLC
 * @license   GPL-3.0+
 * @since     1.0.0 | Unknown     | CREATED
 * @since     2.2.4 | 02 MAY 2017 | Refactored functions & updated docblocking
 * @since     3.0.0 | 05 APR 2018 | Rebuilt into a class-based system.
 *
 */
class SWP_Facebook extends SWP_Social_Network {

    /**
     * Counter ID.
     *
     * @var string
     */
    public $id = 'facebook';

    /**
     * API URL.
     *
     * @var string
     */
    protected $api_url = 'https://graph.facebook.com';

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

        // Update the class properties for this network
        $this->name = __('Facebook', 'social-warfare');
        $this->cta = __('Share', 'social-warfare');
        $this->key = 'facebook';
        $this->default = 'true';
        $this->followers_count = 0;
        $this->base_share_url = 'https://www.facebook.com/share.php?u=';

        $this->init_social_network();

        /* if (true === $this->is_active()):
          $this->register_cache_processes();
          endif; */
    }

    /**
     * Get access token.
     *
     * @return string
     */
    protected function get_access_token() {
        $url = sprintf(
                '%s/oauth/access_token?client_id=%s&client_secret=%s&grant_type=client_credentials', $this->api_url, sanitize_text_field('1756956967877647'), sanitize_text_field('21e20c5e6fe3a74917d33beda09ac434')
        );
        $access_token = wp_remote_get($url, array('timeout' => 60));
        if (is_wp_error($access_token) || ( isset($access_token['response']['code']) && 200 != $access_token['response']['code'] )) {
            return '';
        } else {
            $access_token = json_decode($access_token['body'], true);
            return sanitize_text_field($access_token['access_token']);
        }
    }

    /**
     * Get the total.
     *
     * @return int
     */
    public function get_followers_count() {
        $followers = 0;
        $access_token = $this->get_access_token();
        $url = sprintf(
                '%s%s?fields=fan_count&access_token=%s', $this->api_url . '/v2.7/', sanitize_text_field('antsplashrd'), $access_token
        );
        $conn = wp_remote_get($url, array('timeout' => 60));
        if (!is_wp_error($this->connection) || (isset($this->connection['response']['code']) && 200 == $this->connection['response']['code'] )) {
            $_data = json_decode($conn['body'], true);
            print_r($_data);
            
            if (isset($_data['fan_count'])) {
                $followers = intval($_data['fan_count']);
            }
            
            die();
        }
        return $followers;
    }

}
