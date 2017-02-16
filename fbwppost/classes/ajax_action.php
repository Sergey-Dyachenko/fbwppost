<?php
wp_enqueue_script('jquery');
add_action('wp_ajax_post_facebook', 'post_facebook_callback');

function post_facebook_callback() {

        $at = get_option('fbw_fbap_application_token');

   // $at = trim($_REQUEST['access_tocken']);
    if(empty($at))
    {
        die("Не сформирован Acess token. Для формирования внесите Application ID и Secret Key и нажмите кнопку 'Внести данные' ");
    }
    if(!function_exists('curl_version'))
    {
      die("2");  
    }

    $postid = $_REQUEST['id'];
    $post = get_post($postid);
   // $image_post = wp_get_attachment_url( get_post_thumbnail_id($postid) );
    $data = array();
    $domain_url = "https://graph.facebook.com/me/feed";
    $data['message'] = html_entity_decode(get_the_title($post->ID), ENT_COMPAT, 'utf-8');
    $data['link'] = get_permalink($post->ID);
    $data['caption'] = html_entity_decode(get_the_title($post->ID), ENT_COMPAT, 'utf-8');
    $data['description'] = strip_tags($post->post_content);
    $data['picture'] = 'http://privatfinance.com/wp-content/cache/thumb/168d6f482_850x400.jpg';
    $data['access_token'] = $at;
   
   
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $domain_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $obj = json_decode(strip_tags($output));
      if(isset($obj->{'id'}))
        {
           die("1");
        }
        else if(isset($obj->{'error'})){
            die($obj->{'error'}->message);
        }

    die();
}
?>