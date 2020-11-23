<template>
  <div>
    <md-dialog-confirm
      :md-active.sync="modal_active"
      md-title="Delete File"
      md-content="<strong>Attention</strong>. Cette Action ne peut pas être annulée."
      md-confirm-text="Delete File"
      md-cancel-text="Cancel"
      @md-cancel="onCancel"
      @md-confirm="onConfirmDelete"
    />
    <input ref="input-file" type="file" name="" id="" :style="{ display: 'none' }" @change="onAddImage" multiple />

    <md-button class="md-raised md-primary md-icon-button" @click="$refs['input-file'].click()">
      <md-icon>add</md-icon>
    </md-button>

    <div class="cards-container">
      <UploadManagerImageCard v-for="(file, index) in images" :key="index" :file="file" @delete-image="onDelete" />
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
      modal_active: false,
      file_to_delete: null,
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
    onDelete(data) {
      console.log(data);
      this.modal_active = true;
      this.file_to_delete = data;
      // this.onConfirmDelete(data);
    },
    onCancel() {
      console.log("cancel");
    },
    onConfirmDelete() {
      // console.log(this.file_to_delete);
      this.$store.dispatch("deleteFileFromDir", this.file_to_delete);
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
