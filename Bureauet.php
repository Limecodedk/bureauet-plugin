<?php
/*
* Plugin Name: Bureauet
* Plugin URI: https://bureauet.dk/
* Description: Support til websites.
* Version: 1.0.4
* Author: Bureauet
* Requires PHP: 7.4
* Requires at least: 6.0
*/

if (! defined('ABSPATH')) {
  exit; // Exit hvis filen tilgås direkte.
}

// Enqueue ekstern stylesheet til vores plugin-side
add_action('admin_enqueue_scripts', 'wp_setup_wizard_enqueue');
function wp_setup_wizard_enqueue($hook) {
    //error_log('Current hook: ' . $hook); // Debugging - logger hook-navnet i error log
    //echo "<script>console.log('Current hook: " . $hook . "');</script>"; // Debugging i browser console

    $allowed_hooks = [
      'toplevel_page_bureauet-home', 
      'bureauet_page_wp-setup-wizard', 
      'bureauet_page_wp-setup-wizard-guide',
      'bureauet_page_bureauet-service-history',
      'bureauet_page_bureauet-settings',
      'index.php' 
  ];
  

    if (!in_array($hook, $allowed_hooks)) {
        return;
    }

    wp_enqueue_style('wp_setup_wizard_style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('wp_setup_wizard_script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), null, true);
    wp_localize_script('wp_setup_wizard_script', 'wp_setup_wizard', array(
        'security' => wp_create_nonce('wp_setup_wizard_nonce'),
        'ajaxurl'  => admin_url('admin-ajax.php')
    ));
}

register_activation_hook(__FILE__, 'bureauet_plugin_activate');
function bureauet_plugin_activate() {
  if (get_option('bureauet_allowed_users') === false) {
      $admins = get_users(['role' => 'administrator']);
      $admin_ids = array_map(function($user) {
          return $user->ID;
      }, $admins);
      update_option('bureauet_allowed_users', $admin_ids);
  }
}

register_uninstall_hook(__FILE__, 'bureauet_plugin_uninstall');
function bureauet_plugin_uninstall() {
    delete_option('bureauet_allowed_users');
    delete_option('bureauet_service_history');
}


// Tilføj en admin-menu for wizard'en og guiden
add_action('admin_menu', 'wp_setup_wizard_menu');
function wp_setup_wizard_menu()
{
 // Opret hovedmenuen "Bureauet"
 add_menu_page(
  'Bureauet',          // Sidetitel
  'Bureauet',          // Menu-titel
  'manage_options',    // Rettigheder
  'bureauet-home',     // Menu slug
  'bureauet_home_page', // Callback-funktion til opsætningssiden
  'dashicons-smiley',  // Ikon
  80                   // Placering
);

// Opret submenu "Wp Setup Wizard"
add_submenu_page(
  'bureauet-home',     // Parent menu slug (korrigeret)
  'Wp Setup Wizard',   // Sidetitel
  'Wp Setup Wizard',   // Menu-titel
  'manage_options',    // Rettigheder
  'wp-setup-wizard',   // Menu slug
  'wp_setup_wizard_page' // Callback-funktion til opsætningssiden
);

// Opret submenu "Webservice"
add_submenu_page(
  'bureauet-home',       
  'Webservice',        
  'Webservice',              
  'manage_options', 
  'bureauet-service-history', 
  'bureauet_service_history_page'
);

// Opret submenu "Indstillinger"
add_submenu_page(
  'bureauet-home',
  'Indstillinger',
  'Indstillinger',
  'manage_options',
  'bureauet-settings',
  'bureauet_settings_page'
);

// Opret submenu "Guide"
add_submenu_page(
  'bureauet-home',    
  'Guide',             
  'Guide',             
  'manage_options',    
  'wp-setup-wizard-guide',
  'wp_setup_wizard_guide_page'
);

}

function bureauet_home_page() {
  $allowed_users = get_option('bureauet_allowed_users', [1]);
  if (!in_array(get_current_user_id(), $allowed_users)) {
      wp_die('Du har ikke adgang til denne side.');
  }
  include plugin_dir_path(__FILE__) . 'pages/bureauet-home.php';
}

function wp_setup_wizard_page() {
  $allowed_users = get_option('bureauet_allowed_users', [1]);
  if (!in_array(get_current_user_id(), $allowed_users)) {
      wp_die('Du har ikke adgang til denne side.');
  }
  include plugin_dir_path(__FILE__) . 'pages/wp-setup-wizard-page.php';
}

add_action('wp_dashboard_setup', 'home_widget_dashboard_setup');

function home_widget_dashboard_setup() {
    wp_add_dashboard_widget(
        'home_widget',
        'Bureauet Nyheder',
        'bureauet_widget_display'
    );
}

function bureauet_widget_display() {
  // Inkluder den eksterne PHP-fil med widget-indholdet
  include plugin_dir_path(__FILE__) . 'pages/home-widget-content.php';
}

function bureauet_service_history_page() {
  $allowed_users = get_option('bureauet_allowed_users', [1]);
  if (!in_array(get_current_user_id(), $allowed_users)) {
      wp_die('Du har ikke adgang til denne side.');
  }
  include plugin_dir_path( __FILE__ ) . 'pages/services.php';
}

function bureauet_settings_page() {
  $allowed_users = get_option('bureauet_allowed_users', [1]);
  if (!in_array(get_current_user_id(), $allowed_users)) {
      wp_die('Du har ikke adgang til denne side.');
  }
  include plugin_dir_path( __FILE__ ) . 'pages/settings.php';
}

// Inkluder filen med guiden
function wp_setup_wizard_guide_page() {
  $allowed_users = get_option('bureauet_allowed_users', [1]);
  if (!in_array(get_current_user_id(), $allowed_users)) {
      wp_die('Du har ikke adgang til denne side.');
  }
  include plugin_dir_path( __FILE__ ) . 'pages/guide-page.php';
}


require_once plugin_dir_path(__FILE__) . 'inc/github-updater.php';

bureauet_register_github_updater([
    'plugin_slug' => 'Bureauet/Bureauet.php',
    'slug'        => 'bureauet',
    'github_repo' => 'Limecodedk/bureauet-plugin'
]);

add_action('admin_init', 'bureauet_handle_settings_submissions');
function bureauet_handle_settings_submissions() {
    if (!is_admin() || !current_user_can('administrator')) {
        return;
    }

    // Gem brugertilladelser
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['allowed_users'])) {
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'bureauet_settings_nonce')) {
            wp_die('Sikkerhedstjek fejlede. Prøv igen.');
        }

        $selected_users = array_map('intval', $_POST['allowed_users']);
        update_option('bureauet_allowed_users', $selected_users);
        wp_redirect(admin_url('admin.php?page=bureauet-settings&saved=true'));
        exit;
    }

    // Ryd services
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_services'])) {
        if (!isset($_POST['bureauet_clear_services_nonce_field']) || !wp_verify_nonce($_POST['bureauet_clear_services_nonce_field'], 'bureauet_clear_services_nonce')) {
            wp_die('Sikkerhedstjek fejlede. Prøv igen.');
        }

        update_option('bureauet_service_history', []);
        wp_redirect(admin_url('admin.php?page=bureauet-settings&cleared=true'));
        exit;
    }
}


?>