<?php

/**
 * Tumblr
 *
 * Class to add a Tumblr share button to the available buttons
 *
 * @package   SocialWarfare\Functions\Social-Networks
 * @copyright Copyright (c) 2018, Warfare Plugins, LLC
 * @license   GPL-3.0+
 * @since     1.0.0 | Unknown     | CREATED
 * @since     2.2.4 | 02 MAY 2017 | Refactored functions & upinterest_descriptionated docblocking
 * @since     3.0.0 | 05 APR 2018 | Rebuilt into a class-based system.
 *
 */
class SWP_Tumblr extends SWP_Social_Network {

    /**
     * Counter ID.
     *
     * @var string
     */
    public $id = 'tumblr';

    /**
     * API URL.
     *
     * @var string
     */
    protected $api_url = 'https://api.tumblr.com/v2/blog/%s/followers';


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
        $this->name = __('Tumblr', 'social-warfare');
        $this->cta = __('Tumblr', 'social-warfare');
        $this->key = 'tumblr';
        $this->default = 'true';
        $this->followers_count = $this->get_followers_count();
        $this->base_share_url = '//tumblr.com/widgets/share/tool?canonicalUrl=';
        $this->init_social_network();
    }
    
    
    
    /**
     * Authorization.
     *
     * @param  string $hostname                  hostname.
     * @param  string $consumer_key              Ccustomer key.
     * @param  string $consumer_secret           Customer secret.
     * @param  string $oaut_haccess_token        OAuth access token.
     * @param  string $oauth_access_token_secret OAuth access token secret.
     *
     * @return string
     */
    protected function authorization($hostname, $consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret) {
        $signature = $this->signature($hostname, $consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret);
        return $this->header($signature);
    }
    
    
	/**
     * Build the OAuth Signature.
     *
     * @param  string $hostname                  hostname.
     * @param  string $consumer_key              Twitter customer key.
     * @param  string $consumer_secret           Twitter customer secret.
     * @param  string $oauth_access_token        OAuth access token.
     * @param  string $oauth_access_token_secret OAuth access token secret.
     *
     * @return array                             OAuth signature params.
     */
    private function signature($hostname, $consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret) {
        $oauth = array(
            'oauth_consumer_key' => $consumer_key,
            'oauth_nonce' => hash_hmac('sha1', time(), '1', false),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $oauth_access_token,
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0'
        );
        $base_info = $this->signature_base_string(sprintf($this->api_url, $hostname), 'GET', $oauth);
        $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;
        return $oauth;
    }
    
    /**
     * Build the Signature base string.
     *
     * @param  string $url     Request URL.
     * @param  string $query   Request query.
     * @param  string $method  Request method.
     * @param  string $params  OAuth params.
     *
     * @return string          OAuth Signature base.
     */
    private function signature_base_string($url, $method, $params) {
        $return = array();
        ksort($params);
        foreach ($params as $key => $value) {
            $return[] = $key . '=' . $value;
        }
        return $method . '&' . rawurlencode($url) . '&' . rawurlencode(implode('&', $return));
    }
    
    /**
     * Build the header.
     *
     * @param  array $signature OAuth signature.
     *
     * @return string           OAuth Authorization.
     */
    public function header($signature) {
        $return = 'OAuth ';
        $values = array();
        foreach ($signature as $key => $value) {
            $values[] = $key . '="' . rawurlencode($value) . '"';
        }
        $return .= implode(', ', $values);
        return $return;
    }

    /**
     * Get the total.
     *
     * @param  array $settings Plugin settings.
     * @param  array $cache    Counter cache.
     *
     * @return int
     */
    public function get_followers_count() {
        $followers = 0;
        $hostname = str_replace(array('http:', 'https:', '/'), '', sanitize_text_field("http://luiscruz1992.tumblr.com/"));
        $params = array(
            'method' => 'GET',
            'timeout' => 60,
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => $this->authorization(
                        $hostname, 
                        "YZhLsFBxooVjxbYqSH16ZctR8JHK50ZmNItftZLXBH7iDfPtEp", 
                        "rgLqcUhmOCQ1QlvUHX8HtvfMjY9g2TuVGsUwLXEbxuPl0qV5Fj", 
                        "ZwxepZSgf7qf7dDfJS2Rc1sZOseJVeb6eypqgz3o5djdG7wCWy", 
                        "yRskaMDbEasYsqcHPJndjeNuprna4goz3ylFsGHeUu4fj7MQDA"
                )
            )
        );
        $conn = wp_remote_get(sprintf($this->api_url, $hostname), $params);
        if (!is_wp_error($conn) || ( isset($conn['response']['code']) && 200 == $conn['response']['code'] )) {
            $_data = json_decode($conn['body'], true);
            if (isset($_data['response']['total_users'])) {
                $count = intval($_data['response']['total_users']);
                $followers = $count;
            }
        }
        return $followers;
    }

    /**
     * 
     * @return type
     *
    public function get_followers_count() {
        
       /*
        
            $request_token = "YZhLsFBxooVjxbYqSH16ZctR8JHK50ZmNItftZLXBH7iDfPtEp";
            $request_token_secret = "rgLqcUhmOCQ1QlvUHX8HtvfMjY9g2TuVGsUwLXEbxuPl0qV5Fj";
            $oauth = new OAuth(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);
           
            $oauth->setToken($request_token, $request_token_secret);
            $access_token_info = $oauth->getAccessToken("https://www.tumblr.com/oauth/access_token");
            print_r($access_token_info);
            if (!empty($access_token_info)) {
                print_r($access_token_info);
            } else {
                print "Falló obteniendo el token de acceso, la respuesta fue: " . $oauth->getLastResponse();
            }
      
        die();
        /*
        $api_key = 'YZhLsFBxooVjxbYqSH16ZctR8JHK50ZmNItftZLXBH7iDfPtEp';
        $blog_name = 'your-belle';
        echo "https://api.tumblr.com/v2/blog/$blog_name/followers?api_key=$api_key";
        die();
        $data = @file_get_contents("https://api.tumblr.com/v2/blog/$blog_name/followers?api_key=$api_key");
        $data = json_decode($data, true);
        echo '<pre/>';
        print_r($data);
        echo $data['response']['blog']['likes'];
        
        
        die();

/*
        $followers = 0;
        $conn = wp_remote_get($this->api_url . sanitize_text_field(SWP_Utility::get_option('pinterest_id')), array('timeout' => 60));
        if (!is_wp_error($conn)) {
            if (200 == $conn['response']['code']) {
                $tags = array();
                $regex = '/property\=\"pinterestapp:followers\" name\=\"pinterestapp:followers\" content\=\"(.*?)" data-app/';
                preg_match($regex, $conn['body'], $tags);
                $followers = isset($tags[1]) ? intval($tags[1]) : 0;
            }
        }
        return $followers;*
    }*/

}
