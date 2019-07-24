<?php
//facebook
function mom_get_facebook_count($url) {
    //$url = 'http://google.com';
    $facebook_url = wp_remote_get('https://graph.facebook.com/v3.1/?fields=engagement&id='.$url.'&access_token='.mom_option('facebook_access_token'));
    if (!is_wp_error($facebook_url)) {
    $facebook = json_decode($facebook_url['body'], true);
    $share_count = isset($facebook['engagement']['share_count']) ? $facebook['engagement']['share_count'] : 0;
        $facebook = intval($share_count);
    } else {
    $facebook = 0;
    }
    return $facebook;
}

function mom_posts_share($id, $url, $style='', $min=false) {
    global $post;

    $url = esc_url($url);
    $desc = esc_js(wp_html_excerpt(strip_shortcodes(get_the_content()), 160));
    $img = esc_url(mom_post_image('large'));
    $title = esc_attr(get_the_title());
    $window_title = __('Share This', 'theme');
    $window_width = 600;
    $window_height = 455;

    $cache_timeout = 60*30;
?>
<?php
$twitter = '';
/*
        Twitter Close share count since November 20, 2015
twitter
//delete_transient('mom_share_twitter_'.$id);
$twitter = get_transient('mom_share_twitter_'.$id);
if ($twitter == '') {
    $twitter_url = wp_remote_get('http://urls.api.twitter.com/1/urls/count.json?url='.$url);
    if (!is_wp_error($twitter_url)) {
    $twitter = json_decode($twitter_url['body'], true);
    $twitter = isset($twitter['count']) ? $twitter['count'] : '';
    } else {
    $twitter = 0;
    }
    set_transient('mom_share_twitter_'.$id, $twitter, $cache_timeout);
}
*/
//delete_transient('mom_share_facebook_'.$id);
$facebook = get_transient('mom_share_facebook_'.$id);
if ($facebook == 0) {
    $facebook = mom_get_facebook_count($url);
    if (strpos($url, 'https') !== false) {
        $old_url = str_replace('https', 'http', $url);
        $facebook += mom_get_facebook_count($old_url);
    }
    set_transient('mom_share_facebook_'.$id, $facebook, $cache_timeout);
}

if ($style != 'vertical') {
//linkedin
//delete_transient('mom_share_linkedin_'.$id);
$linkedin = get_transient('mom_share_linkedin_'.$id);
if ($linkedin == '') {
$linkedin_url = wp_remote_get('http://www.linkedin.com/countserv/count/share?format=json&url='.$url);
    if (!is_wp_error($linkedin_url)) {
    $linkedin = json_decode($linkedin_url['body'], true);
    $linkedin = $linkedin['count'];
    } else {
    $linkedin = 0;
    }
set_transient('mom_share_linkedin_'.$id, $linkedin, $cache_timeout);

}

//pinterest
//delete_transient('mom_share_pinterest_'.$id);
$pinterest = get_transient('mom_share_pinterest_'.$id);
if ($pinterest == '') {
$pinterest_url = wp_remote_get('http://api.pinterest.com/v1/urls/count.json?url='.$url);
    if (!is_wp_error($pinterest_url)) {
    $json = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $pinterest_url['body']);
    $pinterest = json_decode($json, true);
    $pinterest = $pinterest['count'];
    } else {
    $pinterest = 0;
    }
    set_transient('mom_share_pinterest_'.$id, $pinterest, $cache_timeout);
}

}

?>
<?php if ($style == 'vertical') { ?>
           <div class="mom-social-share ss-vertical border-box php-share" data-id="<?php echo $id; ?>">
        <?php if (mom_option('post_share_facebook') != false) { ?>
            <div class="ss-icon facebook">
                <a href="#" onclick="window.open('http://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>', '<?php echo $window_title; ?>', 'menubar=no,toolbar=no,resizable=no,scrollbars=no, width=<?php echo $window_width; ?>,height=<?php echo $window_height; ?>');"><span class="icon"><i class="fa-icon-facebook"></i><?php _e('Share', 'theme'); ?></span></a>
                <span class="count"><?php echo $facebook; ?></span>
            </div> <!--icon-->
        <?php } ?>

        <?php if (mom_option('post_share_twitter') != false) { ?>
            <div class="ss-icon twitter">
                <a href="http://twitter.com/share?text=<?php echo $title; ?>&url=<?php echo $url; ?>" onclick="window.open(this.href, 'Post this on twitter', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,width=<?php echo $window_width; ?>,height=<?php echo $window_height; ?>');"><span class="icon"><i class="fa-icon-twitter"></i><?php _e('Tweet', 'theme'); ?></span></a>
            </div> <!--icon-->
        <?php } ?>

    <?php if ($min == false) { ?>
        <?php if (mom_option('post_share_linkedin') != false) { ?>
                <div class="ss-icon linkedin">
                <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url;?>&title=<?php echo $title; ?>&source=<?php echo urlencode(home_url());?>"
onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=<?php echo $window_height; ?>,width=<?php echo $window_width; ?>');return false;"><span class="icon"><i class="fa-icon-linkedin"></i><?php _e('Share', 'theme'); ?></span></a>
                <span class="count"><?php echo $linkedin; ?></span>
            </div> <!--icon-->
        <?php } ?>
        <?php if (mom_option('post_share_pin') != false) { ?>
            <div class="ss-icon pinterest">
                <a href="http://pinterest.com/pin/create/bookmarklet/?media=<?php echo $img;?>&amp;
url=<?php echo $url;?>&amp;
is_video=false&amp;description=<?php echo $title;?>"
onclick="javascript:window.open(this.href, '_blank', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=<?php echo $window_height; ?>,width=<?php echo $window_width; ?>');return false;"><span class="icon"><i class="fa-icon-pinterest"></i><?php _e('Share', 'theme'); ?></span></a>
                <span class="count"><?php echo $pinterest; ?></span>
            </div> <!--icon-->
        <?php } ?>
        <?php if (mom_option('post_share_vk') != false) { ?>
            <div class="ss-icon vk vk-<?php echo $id; ?>">
                <a href="http://vkontakte.ru/share.php?url=<?php echo $url; ?>&title=<?php print(urlencode($title)); ?>&image=<?php echo $img; ?>&description=<?php echo $desc; ?>"
onclick="javascript:window.open(this.href, '_blank', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=<?php echo $window_height; ?>,width=<?php echo $window_width; ?>');return false;"><span class="icon"><i class="fa-icon-vk"></i><?php _e('Share', 'theme'); ?></span></a>
            </div> <!--icon-->
        <?php } ?>

    <?php } ?>
        </div> <!--social share-->
    <div class="clear"></div>

<?php } else { // horizontal here ?>
       <div class="mom-social-share ss-horizontal border-box php-share" data-id="<?php echo $id; ?>">
        <?php if (mom_option('post_share_facebook') != false) { ?>
            <div class="ss-icon facebook">
                <a href="#" onclick="window.open('http://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>', '<?php echo $window_title; ?>', 'menubar=no,toolbar=no,resizable=no,scrollbars=no, width=<?php echo $window_width; ?>,height=<?php echo $window_height; ?>');"><span class="icon"><i class="fa-icon-facebook"></i><?php _e('Share', 'theme'); ?></span></a>
                <span class="count"><?php echo $facebook; ?></span>
            </div> <!--icon-->
        <?php } ?>

        <?php if (mom_option('post_share_twitter') != false) { ?>
            <div class="ss-icon twitter">
                <a href="http://twitter.com/share?text=<?php echo $title; ?>&url=<?php echo $url; ?>" onclick="window.open(this.href, 'Post this on twitter', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,width=<?php echo $window_width; ?>,height=<?php echo $window_height; ?>');"><span class="icon"><i class="fa-icon-twitter"></i><?php _e('Tweet', 'theme'); ?></span></a>
            </div> <!--icon-->
        <?php } ?>
    <?php if ($min == false) { ?>
        <?php if (mom_option('post_share_linkedin') != false) { ?>
                <div class="ss-icon linkedin">
                <a href="#"
onclick="javascript:window.open('http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url;?>&title=<?php echo $title; ?>&source=<?php echo urlencode(home_url());?>', '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=<?php echo $window_height; ?>,width=<?php echo $window_width; ?>');return false;"><span class="icon"><i class="fa-icon-linkedin"></i><?php _e('Share', 'theme'); ?></span></a>
                <span class="count"><?php echo $linkedin; ?></span>
            </div> <!--icon-->
        <?php } ?>
        <?php if (mom_option('post_share_pin') != false) { ?>
            <div class="ss-icon pinterest">
                <a href="http://pinterest.com/pin/create/bookmarklet/?media=<?php echo $img;?>&amp;
url=<?php echo $url;?>&amp;
is_video=false&amp;description=<?php echo $title;?>"
onclick="javascript:window.open(this.href, '_blank', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=<?php echo $window_height; ?>,width=<?php echo $window_width; ?>');return false;"><span class="icon"><i class="fa-icon-pinterest"></i><?php _e('Share', 'theme'); ?></span></a>
                <span class="count"><?php echo $pinterest; ?></span>
            </div> <!--icon-->
        <?php } ?>
        <?php if (mom_option('post_share_vk') != false) { ?>
            <div class="ss-icon vk vk-<?php echo $id; ?>">
                <a href="http://vkontakte.ru/share.php?url=<?php echo $url; ?>&title=<?php print(urlencode($title)); ?>&image=<?php echo $img; ?>&description=<?php echo $desc; ?>"
onclick="javascript:window.open(this.href, '_blank', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=<?php echo $window_height; ?>,width=<?php echo $window_width; ?>');return false;"><span class="icon"><i class="fa-icon-vk"></i><?php _e('Share', 'theme'); ?></span></a>
            </div> <!--icon-->
        <?php } ?>
    <?php } ?>
        <div class="clear"></div>
        </div> <!--social share-->

<?php

}
}


function mom_clear_share_cache_on_click() {
        $nonce = $_POST['nonce'];
    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Nope!' );
        $id = $_POST['id'];

delete_transient('mom_share_twitter_'.$id);
delete_transient('mom_share_facebook_'.$id);
delete_transient('mom_share_linkedin_'.$id);
delete_transient('mom_share_pinterest_'.$id);
delete_transient('mom_share_plusone_'.$id);

        exit();
}

add_action( 'wp_ajax_mcscoc', 'mom_clear_share_cache_on_click' );
add_action( 'wp_ajax_nopriv_mcscoc', 'mom_clear_share_cache_on_click');
