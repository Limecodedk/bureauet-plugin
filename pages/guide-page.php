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
            <button class="tab-button active" data-tab="plugins">Huskeliste</button>
            <button class="tab-button" data-tab="settings">Indstillinger</button>
        </div>

        <!-- Fanernes indhold -->
        <div class="tab-content active" id="tab-plugins">
            <h2>Huskeliste</h2>
            <p>Hvad skal der ske efter installation af Plugins + Tema via WP Setup Wizard?</p>
            <h3>AAM indstillinger</h3>
            <p>Upload seneste nye Jason fil til AAM brugerroller og indstillinger.
            Den uploades via AAM Settings Export/import Import Upload fil</p>


            <h3>Wordfence license + indstillinger</h3>
            <p>Indsæt vores support mail: online@johnsen.dk + vores free Wordfence license
Free license: 5856f25271e123a9687c1b706d4fcba25e111ba5aca8178ae6de930dda03048b95af761fd27b38a7061d3c7b433b7300233c11ec39c073d51a6af52eeeb9e85f17ab1d47478dd7ab24e767390b59a16f

Indsæt derefter license til indstillinger under All options import/export options
Export license:
7d9d1dfdb71c67586c6acabe12df4449c402232872c50691894ef79e9b1aa01223e41f96ae92b1d625a994db3ba1fb31347acd00019aea5410e349c8a1779a70</p>


<h3>YOOTheme API:</h3>
            <p>Opret websitets domæne samt API via vores egen YOOTheme PRO konto på https://yootheme.com/shop/my-account/websites/
Kopier API nøglen og gå til WP-admin YOOTheme indstillinger API Nøgle
Indsæt nøglen og afslut med ”Gem”
</p>


<h3>Google Maps API:</h3>
            <p>
            Tilføj vores Google API under WP-admin YOOTheme indstillinger Ekstern Service
API nøgle: AIzaSyAB57-y3iFgIbizn89xGrRiIu0vH6YROHs

</p>

<h3>WordPress skærmindstillinger på forsiden:</h3>
            <p>
            Fjern alle skærmelementer undtagen Status på webstedshelbred + Bureauet Nyheder
</p>

<h3>Medier:</h3>
            <p>
            Upload Bureauet logo svg-fil + Login BG png til mediebiblioteket

</p>

<h3>Plugins:</h3>
            <p>
            Deaktiver dette plugin: ”WPS Hide Login” (aktiveres og installeres ved go-live)

</p>

<h3>Duplicate post visning:</h3>
            <p>
            Fjern ”Ny kladde” + ”Omskriv og genudgiv” i duplicate post plugin.
Gå under WP-admin Indstillinger Duplicate Post Vis og fjern flueben ved disse to. Afslut med gem.
</p>

<h3>Widgets:</h3>
            <p>
            Slet alle standard WP-widgets i Toolbar Left + Sidebar
</p>

<h3>LESS/CSS:</h3>
            <p>Indsæt LESS variabler i starten af custom code dokument i YOOTheme. 
Indsættes her: YOOTheme → Indstillinger → Brugerdefineret kode 

</p>
<p>
"
<br>
@desktop:   ~"only screen and (min-width: 960px) and (max-width: 1920px)";
@tablet:    ~"only screen and (min-width: 720px) and (max-width: 959px)";
@mobile:    ~"only screen and (min-width: 1px) and (max-width: 719px)";

@default-rgba:red(@global-background), green(@global-background), blue(@global-background);
@muted-rgba:red(@global-muted-background), green(@global-muted-background), blue(@global-muted-background);
@primary-rgba:red(@global-primary-background), green(@global-primary-background), blue(@global-primary-background);
@secondary-rgba:red(@global-secondary-background), green(@global-secondary-background), blue(@global-secondary-background);
@danger-rgba:red(@global-danger-background), green(@global-danger-background), blue(@global-danger-background);
@success-rgba:red(@global-success-background), green(@global-success-background), blue(@global-success-background);
@warning-rgba:red(@global-warning-background), green(@global-warning-background), blue(@global-warning-background);

:root {
    /*Background*/
    --default: @global-background;
    --muted: @global-muted-background;
    --primary: @global-primary-background;
    --secondary: @global-secondary-background;
    --danger: @global-danger-background;
}
<br>
"</p>
        </div>

        <div class="tab-content" id="tab-settings">
            <h2>Indstillinger</h2>
            <h3>Begræns plugin Adgang</h3>
            <p>Du har mulighed for at vælge hvem der må have adgang til pluginet. Makrkere de bruger som skal have adgang.</p>
            <h3>Hvis du ønsker at slette gemte webservices klik da på knappen "Slet alle gemte webservices"</h3>
        </div>
    </div>

    <?php
