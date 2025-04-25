  <div class="wrap wp-setup-wizard">
  <div class='wsw-header'>
<div>
 <img class="wsw-logo" src="<?php echo plugin_dir_url(__DIR__) . 'assets/Bureauet_primaert_pos.png'; ?>" />


</div>
<div class='wsw-header-text'>
 <h1>WP opsætning</h1>
</div>
  </div> 
    <form method="post" enctype="multipart/form-data">
      <?php wp_nonce_field('wp_setup_wizard_action', 'wp_setup_wizard_nonce'); ?>

<!-- Plugins fan -->
      <div class="wizard-section" id="wsw-plugin">
        <h2>1. Plugins</h2>
        <div class="checkbox-group">
        <label><input type="checkbox" name="plugins[]" value="advanced-custom-fields"> Advanced Custom Fields</label>
        <label><input type="checkbox" name="plugins[]" value="advanced-access-manager"> Advanced Access Manager</label>
        <label><input type="checkbox" name="plugins[]" value="contact-form-7"> Contact Form 7</label>
        <label><input type="checkbox" name="plugins[]" value="duplicate-post"> Duplicate post</label>
        <label><input type="checkbox" name="plugins[]" value="insert-headers-and-footers"> Headers and Footer WP Code</label>
        <label><input type="checkbox" name="plugins[]" value="safe-svg"> Safe SVG</label>
        <label><input type="checkbox" name="plugins[]" value="simple-301-redirects"> Simple 301-redirects</label>
        <label><input type="checkbox" name="plugins[]" value="wordpress-seo"> Yoast SEO</label>
        <label><input type="checkbox" name="plugins[]" value="wp-mail-smtp"> WP Mail SMTP</label>
        <label><input type="checkbox" name="plugins[]" value="worker"> ManageWP Worker</label>
        <label><input type="checkbox" name="plugins[]" value="wordfence"> Wordfence security</label>
        <label><input type="checkbox" name="plugins[]" value="wps-hide-login"> Wps hide login</label>
        <label><input type="checkbox" id="select-all-plugins"> Vælg alle</label>
        </div>
      </div>

<!-- Themes fane -->
<div class="wizard-section">
      <h2>2. Themes</h2>
     <div id="wsw-themes">
        <div>
          <h3>Upload Theme (ZIP-fil)</h3>
          <input type="file" name="premium_theme_zip">

          <a href="https://yootheme.com/wordpress-themes" target='_blank'>Download YOOtheme</a>
        </div>

        <div>
          <h3>Upload Child Theme (ZIP-fil)</h3>
          <input type="file" name="child_theme_zip">
        </div>
      </div>
     <div>
     <label><input type="checkbox" name="removethems" value="removethems"> Slet eksisterende themes (undtagen Twenty Twenty-Five)</label>
     </div>
</div>

<!-- Settings fane -->
<div class="wizard-section" id="wsw-settings">
    <h2>3. Generelle indstillinger</h2>
    <div>
    <label>
        <input type="checkbox" id="static-page" name="staticPage" value="page" <?php checked(get_option('show_on_front'), 'page'); ?>>
        Statisk side (Opretter Forside)
    </label>

    <label>
        <input type="checkbox" id="discourage-search" name="discourageSearch" value="1" <?php checked(get_option('blog_public'), '0'); ?>>
        Bed søgemaskiner om ikke at indeksere dette websted
    </label>

    <h3>Diskussionsindstillinger</h3>
    <label>
        <input type="checkbox" id="allow-comments" name="allowComments" value="open" <?php checked(get_option('default_comment_status'), 'open'); ?>>
        Tillad folk at skrive kommentarer til nye indlæg
    </label>

    <h3>Medieindstillinger</h3>
    <label>
        <input type="checkbox" id="organize-uploads" name="organizeUploads" value="1" <?php checked(get_option('uploads_use_yearmonth_folders'), '1'); ?>>
        Organiser mine uploads i mapper efter måneds- og årsbaserede mapper
    </label>

    <h4>Struktur for permanente links</h4>
    <label>
        <input type="checkbox" id="custom-permalink" name="customPermalink" value="1" <?php checked(get_option('permalink_structure'), '/%postname%/'); ?>>
        Egen struktur
    </label>
    </div>
</div>


<!-- <input type="submit" id="submit_setup_btn" name="submit_setup" class="button button-primary" value="Installer valgte elementer"> -->
<input type="submit" name="submit_setup" class="button button-primary" value="Installer valgte elementer">
    </form>
  </div>

<?php
if (!defined('ABSPATH')) exit; // Sikkerhedstjek
  // Processer formularen ved submission
  if (isset($_POST['submit_setup']) && check_admin_referer('wp_setup_wizard_action', 'wp_setup_wizard_nonce')) {

    // 1. Installer gratis plugins fra WordPress.org
    if (isset($_POST['plugins']) && is_array($_POST['plugins'])) {
      require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
      require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

      $plugin_upgrader = new Plugin_Upgrader();

      // Mapping af plugin slug til den korrekte hovedfil
      $plugin_files = array(
        'advanced-access-manager'    => 'aam.php',
        'contact-form-7'             => 'wp-contact-form-7.php',
        'safe-svg'                   => 'safe-svg.php',
        'wordpress-seo'              => 'wp-seo.php',
        'insert-headers-and-footers' => 'ihaf.php',
        'wp-mail-smtp'               => 'wp_mail_smtp.php',
        'advanced-custom-fields'     => 'acf.php', 
        'worker'                     => 'init.php', 
        'simple-301-redirects'       => 'wp-simple-301-redirects.php', 
        'wordfence'                  => 'wordfence.php', 
        'duplicate-post'             => 'duplicate-post.php', 
        'wps-hide-login'             => 'wps-hide-login.php',   
      );

      foreach ($_POST['plugins'] as $plugin_slug) {
        // Hent plugin-info fra WordPress.org
        $api = plugins_api('plugin_information', array(
          'slug'   => $plugin_slug,
          'fields' => array('sections' => false),
        ));

        if (! is_wp_error($api)) {
          // Installer plugin'et
          $result = $plugin_upgrader->install($api->download_link);
          if ($result && ! is_wp_error($result)) {
            // Bestem den rigtige sti til pluginets hovedfil
            $plugin_file = isset($plugin_files[$plugin_slug]) ? $plugin_files[$plugin_slug] : $plugin_slug . '.php';
            $plugin_path = WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file;
            $activate = activate_plugin($plugin_path);
            if (is_wp_error($activate)) {
              echo '<p>Fejl ved aktivering af ' . esc_html($plugin_slug) . ': ' . $activate->get_error_message() . '</p>';
            } else {
              echo '<p>Installeret og aktiveret plugin: ' . esc_html($plugin_slug) . '</p>';
            }
          } else {
            echo '<p>Fejl ved installation af plugin: ' . esc_html($plugin_slug) . '</p>';
          }
        } else {
          echo '<p>Plugin ikke fundet: ' . esc_html($plugin_slug) . '</p>';
        }
      }
    }

    // 2. Slet eksisterende themes hvis valgt, men opdater "twentytwentyfive" i stedet for at slette det
    if (isset($_POST['removethems']) && $_POST['removethems'] === 'removethems') {
      // Hent alle installerede themes
      $themes = wp_get_themes();

      // Inkluder nødvendige filer til tema-opgradering og themes API
      require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
      require_once ABSPATH . 'wp-admin/includes/theme.php';
      $theme_upgrader = new Theme_Upgrader();

      // Definer funktionen til rekursiv sletning, hvis den ikke allerede findes
      if (! function_exists('delete_directory')) {
        function delete_directory($dir)
        {
          if (! file_exists($dir)) {
            return true;
          }
          if (! is_dir($dir)) {
            return unlink($dir);
          }
          foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
              continue;
            }
            if (! delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
              return false;
            }
          }
          return rmdir($dir);
        }
      }

      // Gennemløb alle themes
      foreach ($themes as $theme_slug => $theme_obj) {
        // Undgå at slette det aktive theme
        if (get_stylesheet() !== $theme_slug) {

          // Hvis theme er "twentytwentyfive", opdater i stedet for at slette
          if ($theme_slug === 'twentytwentyfive') {
            // Hent theme-information fra WordPress.org
            $api = themes_api('theme_information', array(
              'slug'   => $theme_slug,
              'fields' => array('sections' => false),
            ));
            if (! is_wp_error($api)) {
              $result = $theme_upgrader->upgrade($theme_slug);
              if (! is_wp_error($result)) {
                echo '<p>Opdateret fallback theme: ' . esc_html($theme_slug) . '</p>';
              } else {
                echo '<p>Fejl ved opdatering af fallback theme: ' . esc_html($theme_slug) . '</p>';
              }
            } else {
              echo '<p>Kunne ikke hente theme information for fallback theme: ' . esc_html($theme_slug) . '</p>';
            }
            // Spring videre til næste theme
            continue;
          }

          // Slet temaet for øvrige themes
          $theme_dir = get_theme_root() . '/' . $theme_slug;
          if (is_dir($theme_dir)) {
            if (delete_directory($theme_dir)) {
              echo '<p>Slettet theme: ' . esc_html($theme_slug) . '</p>';
            } else {
              echo '<p>Fejl ved sletning af theme: ' . esc_html($theme_slug) . '</p>';
            }
          }
        }
      }
    }

    //3. Installer premium theme fra ZIP-upload og aktiver det
    if (! empty($_FILES['premium_theme_zip']['name'])) {
      require_once ABSPATH . 'wp-admin/includes/file.php';
      require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

      $uploaded_file = $_FILES['premium_theme_zip'];
      $upload_overrides = array('test_form' => false);
      $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

      if ($movefile && ! isset($movefile['error'])) {
        $theme_upgrader = new Theme_Upgrader();
        $result = $theme_upgrader->install($movefile['file']);
        if ($result && ! is_wp_error($result)) {
          // Antag, at temaets mappe svarer til ZIP-filens navn uden ".zip"
          $theme_slug = preg_replace('/\.zip$/i', '', basename($uploaded_file['name']));
          switch_theme($theme_slug);
          echo '<p>Installeret og aktiveret theme: ' . esc_html($theme_slug) . '</p>';
        } else {
          echo '<p>Fejl ved installation af theme: ' . (is_wp_error($result) ? $result->get_error_message() : '') . '</p>';
        }
      } else {
        echo '<p>Upload fejl for theme: ' . esc_html($movefile['error']) . '</p>';
      }
    }

    //4. Installer child theme fra ZIP-upload og aktiver det
    if (! empty($_FILES['child_theme_zip']['name'])) {
      require_once ABSPATH . 'wp-admin/includes/file.php';
      require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

      $uploaded_file = $_FILES['child_theme_zip'];
      $upload_overrides = array('test_form' => false);
      $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

      if ($movefile && ! isset($movefile['error'])) {
        $theme_upgrader = new Theme_Upgrader();
        $result = $theme_upgrader->install($movefile['file']);
        if ($result && ! is_wp_error($result)) {
          // Antag, at child theme mappen svarer til ZIP-filens navn uden ".zip"
          $child_theme_slug = preg_replace('/\.zip$/i', '', basename($uploaded_file['name']));
          // Bemærk: Et child theme kræver, at parent theme er aktivt.
          switch_theme($child_theme_slug);
          echo '<p>Installeret og aktiveret child theme: ' . esc_html($child_theme_slug) . '</p>';
        } else {
          echo '<p>Fejl ved installation af child theme: ' . (is_wp_error($result) ? $result->get_error_message() : '') . '</p>';
        }
      } else {
        echo '<p>Upload fejl for child theme: ' . esc_html($movefile['error']) . '</p>';
      }
    }

    //5. Håndtere indstillinger 
  update_option('show_on_front', isset($_POST['staticPage']) ? 'page' : 'posts');
  update_option('blog_public', isset($_POST['discourageSearch']) ? '0' : '1');
  update_option('default_comment_status', isset($_POST['allowComments']) ? 'open' : 'closed');
  update_option('uploads_use_yearmonth_folders', isset($_POST['organizeUploads']) ? '1' : '0');
  update_option('permalink_structure', isset($_POST['customPermalink']) ? '/%category%/%postname%/' : '/%postname%/');

  global $wp_rewrite;
  $wp_rewrite->set_permalink_structure(get_option('permalink_structure'));
  $wp_rewrite->flush_rules(true); // Force refresh rewrite rules
  
  // Håndter statisk forside
  if (isset($_POST['staticPage'])) {
      $existing_page = get_page_by_title('Forside');

      if (!$existing_page) {
          // Opret en ny side
          $front_page_id = wp_insert_post([
              'post_title'   => 'Forside',
              'post_content' => 'Dette er en fin forside.',
              'post_status'  => 'publish',
              'post_type'    => 'page',
              'post_author'  => get_current_user_id()
          ]);
      } else {
          // Brug eksisterende side
          $front_page_id = $existing_page->ID;
      }

      // Sæt den nye side som forside
      update_option('page_on_front', $front_page_id);
      update_option('show_on_front', 'page');
  }
}