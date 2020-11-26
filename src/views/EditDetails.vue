<template>
  <div v-if="!$auth.loading">
    <div v-if="isManagerOn" class="layer">
      <uploadManager ref="manager" mode="popup" @select="onSelectImages" />
      <md-button class="md-raised md-primary md-dense" @click="onSaveSelected">Add Selected</md-button>
      <md-button class="md-raised md-dense" @click="isManagerOn = false">Close</md-button>
    </div>
    <form class="form" @submit.prevent="onSaveData">
      <div>Edit ..... {{ $route.params.id }}</div>

      <md-field>
        <label>Name</label>
        <md-input v-model="currentItem.json_data.name"></md-input>
      </md-field>
      <md-field>
        <label>Description</label>
        <md-input v-model="currentItem.json_data.desc"></md-input>
      </md-field>
      <md-button class="md-raised" @click="isManagerOn = true">Add Image</md-button>
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
import UploadManager from "../components/UploadManager.vue";
export default {
  name: "EditDetails",
  components: {
    ImageList,
    UploadManager,
  },
  data: () => {
    UploadManager;
    return {
      sending: false,
      images: [],
      images_temp: [],
      image_path: null,
      isManagerOn: false,
    };
  },

  created() {
    this.images = this.currentItem.json_data.images;
  },

  mounted() {
    // this.$store.dispatch("setCurrentID", this.$route.params.id);
    this.setCurrentID(this.$route.params.id);
    this.currentItem;

    // this.name = this.currentItem.json_data.name;
  },
  updated() {
    // console.log(this.$refs["manager"]);
    this.images = this.currentItem.json_data.images;
  },
  methods: {
    ...mapActions(["setCurrentItem", "setCurrentID"]),
    onSaveData() {
      console.log("save data ?");

      this.currentItem.json_data.name = this.name;
      this.currentItem.json_data.desc = this.desc;

      // console.log("currentItem :", JSON.stringify(this.currentItem.json_data));
      this.$store.dispatch("updateItem", {
        id: this.$route.params.id,
        json_data: JSON.stringify(this.currentItem.json_data),
      });
    },
    onSelectImages(data) {
      console.log("select");
      console.log(data);
      this.images_temp = data;
    },
    onSaveSelected() {
      if (this.images == undefined) this.images = [];
      for (let img of this.images_temp) {
        this.images.push(img);
      }
      // console.log(data.target);
      this.currentItem.json_data.images = this.images;
      this.$store.dispatch("updateItem", {
        id: this.$route.params.id,
        json_data: JSON.stringify(this.currentItem.json_data),
      });

      this.isManagerOn = false;
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

.layer {
  position: absolute;
  z-index: 200;
  background-color: rgba(white, 0.5);
  width: 100%;
  left: 0;
  padding: 1em;
  // height: 100%;
}
</style>
