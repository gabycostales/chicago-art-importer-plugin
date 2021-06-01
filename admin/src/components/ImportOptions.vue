<template>
  <div class="configure-import-options">
    <label for="search_query" class="search-label">
      <span>Filter Art by Search Query</span> 
      <br/> <em>Example: "Monet" (Leave blank for all)</em>
    </label>
    <input 
      v-model="input.search" 
      type="text" 
      placeholder="Search Query" 
      class="regular-text" 
    />

    <!-- Currently not Used -->
    <h3>Type of Artworks to Import</h3>
    <div class="type-group">
      <label>
        <input 
          type="checkbox"
          ref="allCheckbox"
          disabled
          @click="toggleAll"
        />
        <span>All</span>
      </label>
      <label
        v-for="type in artworkTypes"
        :key="type.id"
      >
        <input 
          type="checkbox"
          :value="type.id"
          disabled
          @click="deselectAll"
        />
        <span>{{ type.name }}</span>
      </label>
    </div>

    

    <button 
      class="button-primary"
      :class="{disabled: disableSubmit}"
      @click="runImport"
    >
      Import Artwork
    </button>
  </div>
</template>

<script>
  export default {
    name: 'ImportOptions',
    props: {
      artworkTypes: {
        type: Array,
        default: () => [],
      },
      disableSubmit: {
        type: Boolean,
        default: false,
      },
    },
    data() {
      return {
        input: {
          types: {}
        },
      };
    },
    mounted() {
      this.$refs.allCheckbox.checked = true;
    },
    watch: {
      input: {
        deep: true,
        handler(input) {
          this.$emit('input', input);
        },
      },
    },
    methods: {
      runImport() {
        if (this.disableSubmit) return;

        this.$emit('submit');
      },
      toggleAll(e) {
        if (this.$refs.allCheckbox.checked) {
          this.$set(this.input, 'types', {});
        }
      },
      deselectAll() {
        this.$refs.allCheckbox.checked = false;
      },
    }
  };
</script>

<style lang="scss" scoped>

  .configure-import-options {
    display: flex;
    flex-direction: column;
  }

  .search-label {
    margin-bottom: 7px;
    
    span {
      color: #1d2327;
      font-size: 1.3em;
      margin: 1em 0;
      font-weight: 600;
      line-height: 1.5em;
    }
  }

  h3 {
    opacity: 0.5;
  }

  .type-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 25px;

    label {
      margin: 3px 10px; 
    }
  }

</style>
