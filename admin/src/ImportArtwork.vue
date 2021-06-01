<template>
  <div class="import-artwork-admin-container wrap">
    <div v-if="showSuccess" class="notice notice-success inline">
      <p>Saved artwork successfully! Imported a total of {{totalImported}} artworks.</p>
    </div>
    <div v-if="showError" class="notice notice-error inline">
      <p>Oh no! An error occurreed when trying to import artwork.</p>
    </div>

    <div class="card">
      <h1>Import Artwork from the Art Institute of Chicago</h1>
      <p>Please choose from some of the settings below to import more artwork. Please note that you will only be allowed to send 1 request per second, so that we do not spam the art institute's API. This will import up to a maximum of 100 artworks. All previously stored artwork will be deleted (will enhance this in the future so that we don't delete previous art).</p>

      <import-options
        v-model="input"
        :artwork-types="artworkTypes"
        :disable-submit="disableSubmit"
        @submit="importArtwork"
      />
    </div>
  </div>
</template>

<script>
  import axios from 'axios';
  import ImportOptions from './components/ImportOptions.vue';

  export default {
    name: 'ImportArtwork',
    components: {
      ImportOptions,
    },
    data() {
      return {
        loading: false,
        showSuccess: false,
        disableSubmit: false,
        totalImported: 0,
        importUrl: '',
        input: {
          search: '',
          types: {},
        },
        artworkTypes: [],
      };
    },
    mounted() {
      const {artworkTypes = [], apiURL} = window.artSettings || {};

      this.importUrl = apiURL;
      this.artworkTypes = artworkTypes.map(({slug, name}) => ({id: slug, name}));
    },
    methods: {
      importArtwork() {
        this.showSuccess = false;
        this.showError = false;

        if (!this.importUrl) return;

        this.loading = true;
        this.disableSubmit = true;
        console.log(this.input);

        const {search = ''} = this.input;

        console.log(search);

        axios.post(this.importUrl, {
          headers: {
            'X-WP-Nonce': window.artSettings?.nonce,
          },
          params: {
            search,
          },
        }).then((response) => {
            console.log(response);
            this.totalImported = response.data;
            this.showSuccess = true;
          })
          .catch((e) => {
            console.error(e);
            this.showError = true;
          })
          .finally(() => {
            this.loading = false;
            setTimeout(() => {
              this.disableSubmit = false;
            }, 1000);
          })
      },
    },
  };
</script>

<style lang="scss" scoped>
</style>