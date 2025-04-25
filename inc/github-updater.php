<?php
/**
 * GitHub Plugin Updater Helper
 *
 * Bruges til at aktivere auto-opdatering af plugin direkte fra GitHub releases.
 */

function bureauet_register_github_updater($args) {
    $plugin_slug = $args['plugin_slug'];       // fx 'bureauet/bureauet.php'
    $plugin_slug_short = $args['slug'];        // fx 'bureauet'
    $repo = $args['github_repo'];              // fx 'din-bruger/bureauet-plugin'

    // Hook ind i WP's plugin update-check
    add_filter('pre_set_site_transient_update_plugins', function($transient) use ($plugin_slug, $repo) {
        if (empty($transient->checked)) return $transient;

        $api_url = "https://api.github.com/repos/$repo/releases/latest";
        $response = wp_remote_get($api_url, [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'WordPress'
            ]
        ]);

        if (!is_wp_error($response) && isset($transient->checked[$plugin_slug])) {
            $release = json_decode(wp_remote_retrieve_body($response));
            $latest_version = ltrim($release->tag_name, 'v');
            $current_version = $transient->checked[$plugin_slug];

            if (version_compare($latest_version, $current_version, '>')) {
                $transient->response[$plugin_slug] = (object)[
                    'slug'        => $plugin_slug,
                    'plugin'      => $plugin_slug,
                    'new_version' => $latest_version,
                    'url'         => $release->html_url,
                    'package'     => $release->zipball_url
                ];
            }
        }

        return $transient;
    });

    // Tilføj info når WP bruger plugins_api
    add_filter('plugins_api', function($res, $action, $args) use ($plugin_slug_short, $repo) {
        if ($action !== 'plugin_information' || $args->slug !== $plugin_slug_short) return false;

        $api_url = "https://api.github.com/repos/$repo/releases/latest";
        $response = wp_remote_get($api_url, [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'WordPress'
            ]
        ]);

        if (is_wp_error($response)) return false;

        $release = json_decode(wp_remote_retrieve_body($response));
        return (object)[
            'name'          => $plugin_slug_short,
            'slug'          => $plugin_slug_short,
            'version'       => ltrim($release->tag_name, 'v'),
            'author'        => 'Bureauet',
            'homepage'      => $release->html_url,
            'download_link' => $release->zipball_url,
            'sections'      => [
                'description' => $release->body ?? 'Plugin opdatering via GitHub.'
            ]
        ];
    }, 10, 3);
}
