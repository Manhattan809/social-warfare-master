<?php

class SWP_Linkedin extends SWP_Social_Network {

    /**
     *
     * @var type 
     */
    private $access_token = "AQXWnj-Q5XQSEZtIu8MHpHrSxHhtuDVWfzi8NtahLvB98ECFHcEscUdURauHeNr7-XqNuJCd9Hv7m_VciM1Qd2il6_4Nd4TxlKYFsHcZTrLRhY61eLbBboaYOREBKEGXGnDFRXxqFWLg-C8hDyY8k4qj4J3r9paaeyj9WT6hCIy0AWzZ5Qs5YXpKX3wz2RCEUtx-BI-J8a4zkvxj7LFjGddYCAmZjKFvnrwykiymlzm1sVvSIvc_HChRXvQIo8kIv68YsavAXFRNHVekWqDQ0H-Qk1AUZyVeWfbq_rChOp31n0ipIfmdQoolnW-ijImoYsQK0v2A1-0OP701t_iLdjv4jMyPlw";

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
        $this->followers_count = $this->get_followers_count();
        $this->base_share_url = 'https://www.linkedin.com/cws/share?url=';
        $this->init_social_network();
    }

    /**
     * 
     * @return type
     */
    public function get_followers_count() {
        $api_url = 'https://api.linkedin.com/v1/companies/%s/num-followers?format=json';
        $params = array(
            'timeout' => 60,
            'headers' => array(
                'Authorization' => 'Bearer ' . sanitize_text_field($this->access_token)
            )
        );
        $obj = wp_remote_get(sprintf($api_url, sanitize_text_field(SWP_Utility::get_option('linkedin_id'))), $params);
        $followers = $obj['body'];
        return $followers;
    }

}
