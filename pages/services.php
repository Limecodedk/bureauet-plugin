<?php
if (!defined('ABSPATH')) exit; // Sikkerhedstjek
// Gemmer ny serviceevent, hvis formularen er submitted
if ( isset( $_POST['submit_service_event'] ) ) {
    check_admin_referer( 'bureauet_service_history_nonce' );
    $service_text   = sanitize_text_field( $_POST['service_text'] );
    $service_date   = sanitize_text_field( $_POST['service_date'] );
    $service_author = sanitize_text_field( $_POST['service_author'] );
    
    $new_event = array(
        'date'   => $service_date,
        'text'   => $service_text,
        'author' => $service_author,
    );
    
    // Hent eksisterende events, eller et tomt array hvis ingen er gemt
    $service_history = get_option( 'bureauet_service_history', array() );
    $service_history[] = $new_event;
    update_option( 'bureauet_service_history', $service_history );
    
    echo '<div class="updated"><p>Serviceevent gemt.</p></div>';
}
?>
<div class="wrap wp-setup-wizard">
<h1>Webservice</h1>
    <div class="wizard-section">
    <h2>Opret ny webservice</h2>
    <form method="post">
        <?php wp_nonce_field( 'bureauet_service_history_nonce' ); ?>
        <table class="form-table">
            <tr>
                <th><label for="service_text">Beskrivelse</label></th>
                <td><input type="text" name="service_text" id="service_text" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="service_date">Dato</label></th>
                <td><input type="date" name="service_date" id="service_date" /></td>
            </tr>
            <tr>
                <th><label for="service_author">Forfatter</label></th>
                <td><input type="text" name="service_author" id="service_author" class="regular-text" /></td>
            </tr>
        </table>
        <?php submit_button( 'Gem Service Event', 'primary', 'submit_service_event' ); ?>
    </form>
    </div>
   
    <div class="wizard-section">
    <h2>Gemte webservices</h2>
    <div>
     <?php 
    $service_history = get_option( 'bureauet_service_history', array() );
    if ( ! empty( $service_history ) ) {
        echo '<ul class="services-list">';
        foreach ( $service_history as $event ) {
            echo '<li>' . esc_html( $event['date'] ) . ': ' . esc_html( $event['text'] ) . ' - ' . esc_html( $event['author'] ) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Ingen events fundet.</p>';
    }
    ?>
   </div>
    </div>
</div>
