<template>
  <div>
    <input ref="input-file" type="file" name="" id="" :style="{ display: 'none' }" @change="onAddImage" multiple />

    <md-button class="md-raised md-primary md-icon-button" @click="$refs['input-file'].click()">
      <md-icon>add</md-icon>
    </md-button>

    <div class="cards-container">
      <UploadManagerImageCard v-for="(file, index) in images" :key="index" :url="file" />
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import UploadManagerImageCard from "@/components/UploadManagerImageCard.vue";
export default {
  name: "UploadManager",
  components: { UploadManagerImageCard },
  data() {
    return {
      files: [],
    };
  },
  mounted() {
    this.loadImagesFromDir();
  },
  methods: {
    ...mapActions(["loadImagesFromDir"]),

    onAddImage(event) {
      //   console.log(event.target.files);
      this.$store.dispatch("uploadImages", event.target.files);
      this.loadImagesFromDir();
    },
  },
  watch: {},
  computed: {
    ...mapState(["images"]),
    list() {
      return this.images;
    },
  },
};
</script>

<style lang="scss" scoped>
.cards-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 0.5em;

  padding-top: 1em;
}
</style>
