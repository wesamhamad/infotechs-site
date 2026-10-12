<?php
/**
 * Infotechs theme bootstrap.
 */
define('INFOTECHS_VER', '0.5.3');

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'script', 'style']);
    add_theme_support('custom-logo');
    register_nav_menus(['primary' => __('Primary menu', 'infotechs')]);
});

add_action('wp_enqueue_scripts', function () {
    $uri = get_template_directory_uri();
    wp_enqueue_style('infotechs-fonts', 'https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Manrope:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap', [], null);
    wp_enqueue_style('infotechs-main', $uri . '/assets/css/main.css', [], INFOTECHS_VER);
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', [], '3.12.5', true);
    wp_enqueue_script('gsap-st', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', ['gsap'], '3.12.5', true);
    wp_enqueue_script('lenis', 'https://cdn.jsdelivr.net/npm/lenis@1.1.18/dist/lenis.min.js', [], '1.1.18', true);
    wp_enqueue_script('infotechs-main', $uri . '/assets/js/main.js', ['gsap-st', 'lenis'], INFOTECHS_VER, true);
    wp_localize_script('infotechs-main', 'INFOTECHS', [
        'logo'  => $uri . '/img/logo.webp',
        'theme' => $uri,
        'lang'  => infotechs_lang(),
        'plan'  => ['hall' => __t('plan.hall'), 'drawing' => __t('plan.drawing'), 'assembling' => __t('plan.assembling'), 'ready' => __t('plan.ready'), 'booths' => __t('plan.booths')],
    ]);
});

require get_template_directory() . '/inc/events.php';
require get_template_directory() . '/inc/i18n.php';

/** Small helper: theme asset URL. */
function infotechs_asset(string $path): string {
    return get_template_directory_uri() . '/' . ltrim($path, '/');
}

/* ---------- Forms (contact, job application, newsletter): mail to the admin address, then redirect. ---------- */
function infotechs_form_redirect(string $state): void {
    $to = isset($_POST['redirect']) ? esc_url_raw(wp_unslash($_POST['redirect'])) : home_url('/');
    wp_safe_redirect(add_query_arg('sent', $state, remove_query_arg('sent', $to))); exit;
}
function infotechs_form_guard(): void {
    if (!isset($_POST['_inf']) || !wp_verify_nonce($_POST['_inf'], 'infotechs_form')) infotechs_form_redirect('err');
}
function infotechs_post(string $k): string { return sanitize_text_field(wp_unslash($_POST[$k] ?? '')); }
$infotechs_contact = function () {
    infotechs_form_guard();
    $body = sprintf("Name: %s\nEmail: %s\nPhone: %s\nSubject: %s\nEvent type: %s\nAttendees: %s\nWhen: %s\nCity: %s\n\n%s", infotechs_post('name'), infotechs_post('email'), infotechs_post('phone'), infotechs_post('subject'), infotechs_post('type'), infotechs_post('attendees'), infotechs_post('when'), infotechs_post('city'), sanitize_textarea_field(wp_unslash($_POST['message'] ?? '')));
    $ok = wp_mail(get_option('admin_email'), '[infotechs.co] Contact: ' . infotechs_post('subject'), $body, ['Reply-To: ' . infotechs_post('email')]);
    infotechs_form_redirect($ok ? 'ok' : 'err');
};
add_action('admin_post_nopriv_infotechs_contact', $infotechs_contact); add_action('admin_post_infotechs_contact', $infotechs_contact);
$infotechs_apply = function () {
    infotechs_form_guard();
    $att = [];
    if (!empty($_FILES['cv']['tmp_name']) && $_FILES['cv']['size'] <= 10 * MB_IN_BYTES && mime_content_type($_FILES['cv']['tmp_name']) === 'application/pdf') {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        $up = wp_handle_upload($_FILES['cv'], ['test_form' => false, 'mimes' => ['pdf' => 'application/pdf']]);
        if (empty($up['error'])) $att[] = $up['file'];
    }
    $body = sprintf("Name: %s %s\nEmail: %s\nPhone: %s\nPosition: %s\n\n%s", infotechs_post('first'), infotechs_post('last'), infotechs_post('email'), infotechs_post('phone'), infotechs_post('position'), sanitize_textarea_field(wp_unslash($_POST['cover'] ?? '')));
    $ok = wp_mail(get_option('admin_email'), '[infotechs.co] Application: ' . infotechs_post('position'), $body, ['Reply-To: ' . infotechs_post('email')], $att);
    infotechs_form_redirect($ok ? 'ok' : 'err');
};
add_action('admin_post_nopriv_infotechs_apply', $infotechs_apply); add_action('admin_post_infotechs_apply', $infotechs_apply);
$infotechs_news = function () {
    infotechs_form_guard();
    $list = get_option('infotechs_newsletter', []); $e = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    if ($e && !in_array($e, $list, true)) { $list[] = $e; update_option('infotechs_newsletter', $list, false); }
    infotechs_form_redirect('nl');
};
add_action('admin_post_nopriv_infotechs_newsletter', $infotechs_news); add_action('admin_post_infotechs_newsletter', $infotechs_news);
