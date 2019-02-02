<?php

class SWP_Linkedin extends SWP_Social_Network {

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
     * @since  3.0.9 | 04 JUN 2018 | Removed deprecated API request (share counts)
     * @param  none
     * @return none
     * @access public
     *
     */
    public function __construct() {

        // Update the class properties for this network
        $this->name = __('LinkedIn', 'social-warfare');
        $this->cta = __('Share', 'social-warfare');
        $this->key = 'linkedin';
        $this->default = 'true';
        $this->followers_count = 0;
        $this->base_share_url = 'https://www.linkedin.com/cws/share?url=';

        $this->init_social_network();
    }

    /**
     * 
     * @return type
     */
    public function get_followers_count() {
        //Get total
        $url = "https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names=" . SWP_Utility::get_option('twitter_id');
        $obj = substr(substr(file_get_contents($url), 1), 0, -1);
        $followers = json_decode($obj)->followers_count;
        return $followers;
    }

}
