<?php
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
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
        'rewrite' => ['slug' => 'events'], 'show_in_rest' => true,
    ]);
    foreach (['event_date', 'event_venue', 'event_scale', 'event_type', 'title_ar', 'excerpt_ar', 'event_date_ar', 'event_venue_ar', 'event_scale_ar', 'event_type_ar'] as $key) {
        register_post_meta('event', $key, ['type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_text_field']);
    }
});

/* Meta box so the client can edit fields without a page builder. */
add_action('add_meta_boxes', function () {
    add_meta_box('event_details', 'Event details', function ($post) {
        wp_nonce_field('event_details', 'event_details_nonce');
        $fields = ['event_type' => 'Type (Conference / Workshop / Virtual)', 'event_date' => 'Date', 'event_venue' => 'Venue', 'event_scale' => 'Scale (participants, CME hours...)', 'title_ar' => 'العنوان بالعربي', 'excerpt_ar' => 'الوصف بالعربي', 'event_type_ar' => 'النوع بالعربي', 'event_date_ar' => 'التاريخ بالعربي', 'event_venue_ar' => 'المكان بالعربي', 'event_scale_ar' => 'الحجم بالعربي'];
        foreach ($fields as $key => $label) {
            $val = esc_attr(get_post_meta($post->ID, $key, true));
            echo "<p><label for='$key'><strong>$label</strong></label><br><input type='text' id='$key' name='$key' value='$val' style='width:100%'></p>";
        }
    }, 'event', 'normal', 'high');
});
add_action('save_post_event', function ($post_id) {
    if (!isset($_POST['event_details_nonce']) || !wp_verify_nonce($_POST['event_details_nonce'], 'event_details')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    foreach (['event_type', 'event_date', 'event_venue', 'event_scale', 'title_ar', 'excerpt_ar', 'event_type_ar', 'event_date_ar', 'event_venue_ar', 'event_scale_ar'] as $key) {
        if (isset($_POST[$key])) update_post_meta($post_id, $key, sanitize_text_field(wp_unslash($_POST[$key])));
    }
});
