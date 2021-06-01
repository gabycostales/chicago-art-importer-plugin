# Art Insitute of Chicago Importer

Client Plugin to import public data from the Art Institute of Chicago.

It will import up to a maximum of 100 artworks. The user can search artworks to save by a certain query.
Currently limited to only storing 100 artworks at a time.

## Installation

This section describes how to install the plugin and get it working.

1. Install [ACF Plugin](https://wordpress.org/plugins/advanced-custom-fields/)
2. Upload plugin folder to the `/wp-content/plugins/` directory as `chicago-art-importer-plugin`
3. Run `npm i` and then `npm run build` in root plugin directory to build Vue admin files
4. Activate the plugin through the 'Plugins' menu in WordPress

## Notes

### ACF Dependency

This plugin uses ACF to define the custom fields for the artwork post type.

### Artwork Types
Upon activating this plugin, it will automatically query for the various types of artworks that the museum saves and automatically creates them as a new taxonomy `artwork_type` for the `artwork` custom post type.

I have code to automatically associate the appropriate type term to the custom post type upon importing the artwork, but the API is returning `null` for the `artwork_type_id` and `artwork_type_title` keys for every artwork which was unexpected. Not sure if its a bug on their end.

### Vue Admin View

The page the import more pieces of artwork is actually built with Vue and sends an POST request to a custom `wp-json` endpoint to trigger importing artworks.

### Room for Improvement

- [ ] Allow results pagination or for users to just keep adding X amount of artworks to save/import. If artwork ID already saved, just query for more artworks. This way we do not need to delete previous artwork.
- [ ] Improve validation
- [ ] Allow for importing by artwork type if can build proper search query
 

## Screenshots

![Artwork Custom Post Type](/assets/Artwork_CPT.png?raw=true "Artwork Custom Post Type")


![Import Artwork](/assets/Import_Artworks.png?raw=true "Import Artwork")



