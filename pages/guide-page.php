<?php
//guide-page.php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Sikkerhedstjek
}
    ?>
    <div class="wrap wp-setup-wizard">
        <h1>Guide til Opsætning af WordPress</h1>

        <!-- Faner -->
        <div class="wp-tabs">
            <button class="tab-button active" data-tab="plugins">Guide</button>
            <button class="tab-button" data-tab="settings">Indstillinger</button>
        </div>

        <!-- Fanernes indhold -->
        <div class="tab-content active" id="tab-plugins">
            Se "Procedure for oprettelse af nyt website" i Google drev
        </div>

        <div class="tab-content" id="tab-settings">
            <h2>Indstillinger</h2>
            <h3>Begræns plugin Adgang</h3>
            <p>Du har mulighed for at vælge hvem der må have adgang til pluginet. Makrkere de bruger som skal have adgang.</p>
            <h3>Hvis du ønsker at slette gemte webservices klik da på knappen "Slet alle gemte webservices"</h3>
        </div>
    </div>

    <?php
