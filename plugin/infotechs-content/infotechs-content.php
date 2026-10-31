<?php
/**
 * Plugin Name: Infotechs Content
 * Description: Registers the "event" post type and its fields so event data stays independent of the active theme. Also provides an admin-only theme installer from the project repository.
 * Version: 1.2
 */
if (!defined('ABSPATH')) exit;
define('INFOTECHS_CONTENT_PLUGIN', true);

/**
 * "Event" custom post type with the few fields the front page needs.
 */
add_action('init', function () {
    register_post_type('event', [
        'labels' => [
            'name' => 'Events', 'singular_name' => 'Event', 'add_new_item' => 'Add event',
            'edit_item' => 'Edit event', 'menu_name' => 'Events',
        ],
        'public' => true, 'has_archive' => true, 'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
        'rewrite' => ['slug' => 'events'], 'show_in_rest' => true,
    ]);
    foreach (['event_date', 'event_venue', 'event_scale', 'event_type', 'title_ar', 'excerpt_ar', 'event_date_ar', 'event_venue_ar', 'event_scale_ar', 'event_type_ar', 'featured_order'] as $key) {
        register_post_meta('event', $key, ['type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_text_field']);
    }
});

/* Meta box so the client can edit fields without a page builder. */
add_action('add_meta_boxes', function () {
    add_meta_box('event_details', 'Event details', function ($post) {
        wp_nonce_field('event_details', 'event_details_nonce');
        $fields = ['event_type' => 'Type (Conference / Workshop / Virtual)', 'event_date' => 'Date', 'event_venue' => 'Venue', 'event_scale' => 'Scale (participants, CME hours...)', 'title_ar' => 'العنوان بالعربي', 'excerpt_ar' => 'الوصف بالعربي', 'event_type_ar' => 'النوع بالعربي', 'featured_order' => 'Home page order (1-8, empty = not featured)', 'event_date_ar' => 'التاريخ بالعربي', 'event_venue_ar' => 'المكان بالعربي', 'event_scale_ar' => 'الحجم بالعربي'];
        foreach ($fields as $key => $label) {
            $val = esc_attr(get_post_meta($post->ID, $key, true));
            echo "<p><label for='$key'><strong>$label</strong></label><br><input type='text' id='$key' name='$key' value='$val' style='width:100%'></p>";
        }
    }, 'event', 'normal', 'high');
});
add_action('save_post_event', function ($post_id) {
    if (!isset($_POST['event_details_nonce']) || !wp_verify_nonce($_POST['event_details_nonce'], 'event_details')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    foreach (['event_type', 'event_date', 'event_venue', 'event_scale', 'title_ar', 'excerpt_ar', 'event_type_ar', 'event_date_ar', 'event_venue_ar', 'event_scale_ar', 'featured_order'] as $key) {
        if (!isset($_POST[$key])) continue;
        $v = sanitize_text_field(wp_unslash($_POST[$key]));
        if ($key === 'featured_order' && $v === '') delete_post_meta($post_id, $key); else update_post_meta($post_id, $key, $v);
    }
});


/* Admin-only: download the theme folder from the project repository into wp-content/themes/infotechs (no activation). */
add_action('admin_post_infotechs_install_theme', function () {
    if (!current_user_can('install_themes')) wp_die('forbidden', 403);
    @set_time_limit(300); header('Content-Type: application/json');
    require_once ABSPATH . 'wp-admin/includes/file.php'; WP_Filesystem();
    $zip = download_url('https://codeload.github.com/wesamhamad/infotechs-site/zip/refs/heads/main', 280);
    if (is_wp_error($zip)) { echo json_encode(['ok' => false, 'err' => $zip->get_error_message()]); exit; }
    $tmp = WP_CONTENT_DIR . '/upgrade/infotechs-src'; if (is_dir($tmp)) { global $wp_filesystem; $wp_filesystem->delete($tmp, true); }
    $r = unzip_file($zip, $tmp); @unlink($zip);
    if (is_wp_error($r)) { echo json_encode(['ok' => false, 'err' => $r->get_error_message()]); exit; }
    $src = $tmp . '/infotechs-site-main/theme'; $dst = get_theme_root() . '/infotechs';
    global $wp_filesystem; if (is_dir($dst)) $wp_filesystem->delete($dst, true);
    $ok = rename($src, $dst); $wp_filesystem->delete($tmp, true);
    $t = wp_get_theme('infotechs');
    echo json_encode(['ok' => $ok && $t->exists(), 'name' => $t->get('Name'), 'version' => $t->get('Version'), 'active' => get_stylesheet()]); exit;
});

/* Admin-only live preview of the new theme: ?infotechs_preview=1 sets a cookie, ?infotechs_preview=0 clears it. Visitors never see it. */
add_action('init', function () {
    if (!isset($_GET['infotechs_preview']) || !current_user_can('switch_themes')) return;
    $on = $_GET['infotechs_preview'] === '1';
    setcookie('infotechs_preview', $on ? '1' : '', $on ? time() + DAY_IN_SECONDS : time() - 3600, '/'); $_COOKIE['infotechs_preview'] = $on ? '1' : '';
}, 1);
add_action('plugins_loaded', function () {
    if (empty($_COOKIE['infotechs_preview']) && (($_GET['infotechs_preview'] ?? '') !== '1')) return;
    if (!function_exists('wp_get_current_user') || !current_user_can('switch_themes') || is_admin()) return;
    if (!wp_get_theme('infotechs')->exists()) return;
    add_filter('template', fn() => 'infotechs'); add_filter('stylesheet', fn() => 'infotechs');
    add_filter('pre_option_template', fn() => 'infotechs'); add_filter('pre_option_stylesheet', fn() => 'infotechs');
});
