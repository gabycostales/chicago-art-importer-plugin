<?php

class ChicagoArtApiHandler {
  function __construct() {
    add_action('rest_api_init', array($this, 'register_rest_endpoints'));
  }

  /**
   * Register REST Route to Import Artwork
   */
  public function register_rest_endpoints() {
    register_rest_route('chicago-art-importer/v1', '/import-art', array(
      'methods' => 'POST',
      'callback' => array($this, 'handle_import_artworks_request'),
    ));
  }

  /**
   * Handle POST request to import artworks
   */
  public function handle_import_artworks_request(WP_REST_Request $request) {
    $jsonParams = $request->get_json_params();

    $search = $jsonParams['params']['search'] ?? '';

    // check_ajax_referer('chicago-art-importer', $jsonParams['headers']['X-WP-Nonce']);

    // Delete all previously saved artworks
    // TODO: this is very slow and we should paginate in the future
    $oldArtworks= get_posts(array('post_type' => 'artwork', 'numberposts'=> -1));
    foreach ($oldArtworks as $artwork) {
      wp_delete_post($artwork->ID, true);
    }

    // Get new art
    $fields = [
      'id',
      'title',
      'image_id',
      'date_display',
      'artist_display',
      'place_of_origin',
      'medium_display',
      'is_public_domain',
      'artwork_type_id',
      'artwork_type_title',
      'inscriptions'
    ];
    $fieldsParam = implode($fields, ',');
    $artworksBaseUrl = 'https://api.artic.edu/api/v1/artworks';

    // Just pull all artwork
    if (empty($search)) {
      $artworks = $this->get_data("{$artworksBaseUrl}?limit=100&fields={$fieldsParam}");
      foreach ($artworks as $artwork) {
        $this->store_artwork($artwork);
      }

      // Return total
      return count($artworks);
    }
    
    // Add artwork that matches search
    $encodeSearch = urlencode($search);
    $searchResults = $this->get_data("{$artworksBaseUrl}/search?q={$encodeSearch}&size=100&fields=id");
    foreach ($searchResults as $artworkResult) {
      // Get full artwork data
      if ($artworkResult['id']) {
        $artwork = $this->get_data("{$artworksBaseUrl}/{$artworkResult['id']}?fields={$fieldsParam}");
        if (!empty($artwork)) {
          $this->store_artwork($artwork);
        }
      }      
    }

    // Return total
    return count($searchResults);
  }

  /**
   * Helper to make basic get requests
   */
  public function get_data($url) {
    $response = wp_remote_get($url); 
    $body = json_decode(wp_remote_retrieve_body($response), true);

    return $body['data'] ?? array();
  }

  /**
   * Get all potential artwork types
   */
  public function get_artwork_types() {
    return $this->get_data('https://api.artic.edu/api/v1/artwork-types?fields=id,title');
  }

  /**
   * Store new artwork
   */
  public function store_artwork($artwork) {
    $baseImageUrl = 'https://www.artic.edu/iiif/2';
    $imageSizeParams = 'full/843,/0/default.jpg';
    $taxonomy_name = 'artwork_type';

    $newPostID = wp_insert_post(array(
      'post_type' => 'artwork',
      'post_title' => $artwork['title'],
      'post_content' => '',
      'post_status' => 'publish'
    ));

    add_post_meta($newPostID, 'artwork_id', $artwork['id']);
    add_post_meta($newPostID, 'date_display', $artwork['date_display']);
    add_post_meta($newPostID, 'artist_display', $artwork['artist_display']);
    add_post_meta($newPostID, 'place_of_origin', $artwork['place_of_origin']);
    add_post_meta($newPostID, 'is_public_domain', $artwork['is_public_domain']);
    add_post_meta($newPostID, 'medium_display', $artwork['medium_display']);
    add_post_meta($newPostID, 'inscriptions', $artwork['inscriptions']);

    if ($artwork['image_id']) {
      add_post_meta($newPostID, 'artwork_thumbnail_url', "{$baseImageUrl}/{$artwork['image_id']}/{$imageSizeParams}");
    }

    if ($artwork['artwork_type_id']) {
      $term = get_term_by('slug', (string)$artwork['artwork_type_id'], $taxonomy_name);
      // If no existing term for this type, create new one
      if (!$term && $artwork['artwork_type_title']) {
        $term = wp_insert_term($artwork['artwork_type_title'], $taxonomy_name, array(
          'slug' => (string)$artwork['artwork_type_id'],
        ));
      }
      // Associate term/type to new post
      if ($term && $term->term_id) {
        wp_set_post_terms($newPostID, $term->term_id, $taxonomy_name);
      } 
    }
  }
}