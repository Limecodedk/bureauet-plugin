<!-- bureauet-widget-content.php -->
<div class="bureauet-widget-content">
<img class="wsw-logo" src="<?php echo plugin_dir_url(__DIR__) . 'assets/Bureauet_primaert_pos.png'; ?>" />
    <h2>Webservice:</h2>
    <?php 
    // Hent den seneste serviceevent fra historikken
    $service_history = get_option('bureauet_service_history', array());
    if (!empty($service_history)) {
        $latest_event = end($service_history);
        echo '<p>';
        echo esc_html($latest_event['text']) . ' Af ' . esc_html($latest_event['author']) . ' - ' . esc_html($latest_event['date']);
        echo '</p>';
    } else {
        echo '<p>Ingen webservice registreret pt.</p>';
    }
    ?>
</div>

<?php
/* if (!defined('ABSPATH')) exit;

$rss = fetch_feed('https://support.bureauet.dk/feed/');

if (is_wp_error($rss)) {
    echo '<p>Kunne ikke hente nyheder.</p>';
    return;
}
 */
/* $max_items = $rss->get_item_quantity(5);
$rss_items = $rss->get_items(0, $max_items);

if ($max_items == 0) {
    echo '<p>Ingen nyheder at vise lige nu...</p>';
} else {
    echo '<div class="bureauet-widget-content">';
    echo '<h2>Seneste nyheder:</h2>';
    echo '<ul class="bureauet-news-list">';
    foreach ($rss_items as $item) {
        echo '<li><a href="' . esc_url($item->get_permalink()) . '" target="_blank">' . esc_html($item->get_title()) . '</a></li>';
    }
    echo '</ul>';
    echo '</div>';
} */
?>

<div class="bureauet-widget-footer">
   <div>
   <a href="https://bureauet.dk/" target="_blank" rel="noopener noreferrer">
      <span class="dashicons dashicons-sos"></span> Support
    </a>
   </div>
   <div>
    <a href="mailto:emb@bureauet.dk">
      <span class="dashicons dashicons-email"></span> Send mail
    </a>
   </div>
</div>
