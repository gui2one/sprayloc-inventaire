<template>
  <div v-if="!$auth.loading">
    <form class="form" @submit.prevent="validateUser">
      <div>Edit ..... {{ $route.params.id }}</div>

      <md-field>
        <label>Name</label>
        <md-input v-model="currentItem.json_data.name"></md-input>
      </md-field>
      <md-field>
        <label>Description</label>
        <md-input v-model="currentItem.json_data.desc"></md-input>
      </md-field>

      <md-field>
        <md-file v-model="image_path" multiple accept="image/*" @md-change="onFileInput"></md-file>
      </md-field>
      <div id="images-list">
        <div v-for="(img, index) in images" :key="index">
          <label>{{ img }}</label>
        </div>
      </div>

      <ImageList :images="images" />
      <div>
        <md-button type="submit" class="md-primary md-raised" :disabled="sending">Save Data</md-button>
      </div>
    </form>
  </div>
</template>
<script>
import { mapActions, mapState } from "vuex";
import ImageList from "@/components/ImageList.vue";
export default {
  name: "EditDetails",
  components: {
    ImageList,
  },
  data: () => {
    return {
      // name: null,
      // desc: null,
      sending: false,
      images: [],
      image_path: null,
    };
  },

  mounted() {
    // this.$store.dispatch("setCurrentID", this.$route.params.id);
    this.setCurrentID(this.$route.params.id);
    // this.name = this.currentItem.json_data.name;
  },
  updated() {
    this.images = this.currentItem.json_data.images;
  },
  methods: {
    ...mapActions(["setCurrentItem", "setCurrentID"]),
    validateUser() {
      console.log("validate ?");

      this.currentItem.json_data.name = this.name;
      this.currentItem.json_data.desc = this.desc;

      // console.log("currentItem :", JSON.stringify(this.currentItem.json_data));
      this.$store.dispatch("updateItem", {
        id: this.$route.params.id,
        json_data: JSON.stringify(this.currentItem.json_data),
      });
    },
    onFileInput(files) {
      console.log(files);
      if (this.currentItem.json_data.images == undefined) {
        this.currentItem.json_data.images = [];
      }
      for (let file of files) {
        console.log(file);
        this.currentItem.json_data.images.push(file.name);
      }

      this.$store.dispatch("uploadImages", files);
    },
  },
  computed: {
    // ...mapGetters(["getCurrentItem"]),
    ...mapState(["items", "currentItem"]),
    name() {
      return this.$store.state.currentItem.json_data.name;
    },
    desc() {
      return this.$store.state.currentItem.json_data.desc;
    },
  },
};
</script>

<style lang="scss" scoped>
.form {
  text-align: left;
}
</style>
