<?php
if (!defined('ABSPATH')) exit;

// Vis beskeder efter redirect
if (isset($_GET['cleared'])): ?>
    <div class="updated"><p>Alle gemte webservices er blevet slettet!</p></div>
<?php endif;

if (isset($_GET['saved'])): ?>
    <div class="updated"><p>Indstillinger gemt!</p></div>
<?php endif;

// Hent gemte brugere
$allowed_users = get_option('bureauet_allowed_users', []);
if (empty($allowed_users)) {
    $allowed_users = [1]; // fallback
}

// Hent alle brugere
$users = get_users(['fields' => ['ID', 'user_login', 'display_name']]);
$user = wp_get_current_user();
$is_admin = in_array('administrator', $user->roles);
?>

<div class="wrap wp-setup-wizard">
    <h1>Indstillinger</h1>

    <div class="wizard-section">
        <?php if ($is_admin): ?>
            <form method="post">
                <?php wp_nonce_field('bureauet_settings_nonce'); ?>
                <h2>Vælg hvem der må have adgang til pluginets sider:</h2>
                <?php foreach ($users as $single_user): ?>
                    <label>
                        <input type="checkbox" name="allowed_users[]" value="<?php echo esc_attr($single_user->ID); ?>"
                            <?php checked(in_array($single_user->ID, $allowed_users)); ?> />
                        <?php echo esc_html($single_user->display_name); ?>
                    </label><br />
                <?php endforeach; ?>

                <br />
                <input type="submit" class="button-primary" value="Gem ændringer" />
            </form>
        <?php else: ?>
            <p>Du har ikke adgang til at ændre indstillingerne for dette plugin.</p>
        <?php endif; ?>
    </div>

    <div class="wizard-section">
        <h2>Ryd gemte webservices:</h2>
        <form method="post" action="">
            <?php wp_nonce_field('bureauet_clear_services_nonce', 'bureauet_clear_services_nonce_field'); ?>
            <input type="submit" name="clear_services" class="button-secondary" value="Slet alle gemte webservices" onclick="return confirm('Er du sikker på, at du vil slette alle gemte webservices?');" />
        </form>
    </div>
</div>
