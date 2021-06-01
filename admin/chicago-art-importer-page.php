<?php
  /**
   * Provide a admin area view for the plugin
   */

  const PLUGIN_SLUG_NAME = 'chicago-art-importer';
  const VERSION = '1.0';

  $terms = get_terms(array(
    'taxonomy' => 'artwork_type',
    'hide_empty' => false
  ));
  $nonce = wp_create_nonce(PLUGIN_SLUG_NAME);
 ?>

  <script type="text/javascript">
    window.artSettings = {
      apiURL: "<?php echo get_site_url().'/wp-json/'.PLUGIN_SLUG_NAME.'/v1/import-art' ?>",
      nonce: "<?php echo $nonce ?>",
      artworkTypes: <?php echo json_encode($terms) ?>,
    };
  </script>
  <div id="app-import-artwork-admin-page"></div>

<?php

wp_enqueue_script(PLUGIN_SLUG_NAME, plugin_dir_url(__FILE__). '/dist/build.js', array(), VERSION, true);
wp_enqueue_style(PLUGIN_SLUG_NAME, plugin_dir_url(__FILE__). '/dist/main.css', array(), VERSION);


