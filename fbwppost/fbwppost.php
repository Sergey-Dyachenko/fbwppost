<?php
/*
  Plugin Name: Facebook Autopost
  Description: Post your blog posts to your facebook page.Its a simple wordpress plugin. 
  Version: 1.1
  Author: Sidhil Sivadas M
*/


$dir = dirname(__FILE__);

@include_once "$dir/classes/admin.php";
@require_once ("$dir/classes/ajax_action.php");



add_option('fbw_fbap_application_id','');
add_option('fbw_fbap_application_secret', '');
add_option('fbw_fbap_application_token', '');
function fbwppost_init() {
  add_filter('rewrite_rules_array', 'fbwppost_rewrites');
}

function fbwppost_activation() {
    global $wp_rewrite;
    add_filter('rewrite_rules_array', 'fbwppost_rewrites');
    $wp_rewrite->flush_rules();
}

function fbwppost_deactivation() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

function fbwppost_rewrites($wp_rules) {
    $base = get_option('fbwppost_base', 'fbwppost');
    if (empty($base)) {
        return $wp_rules;
    }
    $fbwppost_rules = array(
        "$base\$" => 'index.php?fbwppost=info',
        "$base/(.+)\$" => 'index.php?fbwppost=$matches[1]'
    );
    return array_merge($fbwppost_rules, $wp_rules);
}

function fbwppost_admin_menu() {
    add_menu_page('Fbwppost', 'Fbwppost', 'administrator', basename(__FILE__), 'fbwppost_admin');
}
add_action('init', 'fbwppost_init');
add_action('admin_menu', 'fbwppost_admin_menu');
register_activation_hook("$dir/fbwppost.php", 'fbwppost_activation');
register_deactivation_hook("$dir/fbwppost.php", 'fbwppost_deactivation');
?>
