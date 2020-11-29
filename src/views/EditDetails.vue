<template>
  <div>
    <div class="back-btn">
      <router-link to="/"
        ><md-button><md-icon class="md-size-2x">arrow_back</md-icon></md-button></router-link
      >
    </div>
    <h3>
      Edit Details for <strong>{{ currentItem.json_data.name }}</strong>
    </h3>
    <div v-if="isManagerOn" class="layer-manager">
      <uploadManager ref="manager" mode="popup" @select="onSelectImages" />
      <md-button class="md-raised md-primary md-dense" @click="onSaveSelected">Add Selected</md-button>
      <md-button class="md-raised md-dense" @click="isManagerOn = false">Close</md-button>
    </div>
    <div class="main-container" v-if="!$auth.loading">
      <form class="form" @submit.prevent="onSaveData">
        <!-- <div>
          <md-button type="submit" class="md-primary md-raised" :disabled="sending">Save Data</md-button>
        </div> -->
        <md-field>
          <label>Category</label>
          <md-select v-model="currentItem.json_data.category">
            <md-option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.json_data.name }}</md-option>
          </md-select>
        </md-field>
        <md-field>
          <label>Name</label>
          <md-input v-model="currentItem.json_data.name"></md-input>
        </md-field>
        <md-field>
          <label>Description</label>
          <md-textarea v-model="currentItem.json_data.desc"></md-textarea>
        </md-field>
        <md-field>
          <label>Quantity</label>
          <md-input v-model="currentItem.json_data.quantity" type="number"></md-input>
        </md-field>
        <div>
          <md-button type="submit" class="md-primary md-raised" :disabled="sending">Save Data</md-button>
        </div>
      </form>
      <div id="images">
        <md-button class="md-raised" @click="isManagerOn = true">Add Image</md-button>
        <ImageList :images="images" />
      </div>
    </div>
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
    // UploadManager;
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

      // this.currentItem.json_data.name = this.name;
      // this.currentItem.json_data.desc = this.desc;

      this.currentItem.json_data.quantity = parseInt(this.currentItem.json_data.quantity);
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

    onChooseCategory(cat_id) {
      console.log("Choose", cat_id);
    },
  },
  computed: {
    // ...mapGetters(["getCurrentItem"]),
    ...mapState(["items", "currentItem", "categories"]),
    name() {
      return this.$store.state.currentItem.json_data.name;
    },
    desc() {
      return this.$store.state.currentItem.json_data.desc;
    },
    category() {
      return this.$store.state.currentItem.json_data.category;
    },
  },
};
</script>

<style lang="scss" scoped>
.back-btn,
h3 {
  text-align: left;
}
.main-container {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
}

#images {
  outline: 1px solid rgba(black, 0.1);
  // min-width: 50vw;
  flex-basis: 100%;
  background-color: rgba(black, 0.05);
}
.form {
  text-align: left;
  flex-basis: 100%;
}

.layer-manager {
  position: absolute;
  z-index: 200;
  background-color: rgba(white, 0.5);
  width: 100%;
  left: 0;
  padding: 1em;
  height: 100%;
}

@media screen and (min-width: 800px) {
  .main-container {
    // display: flex;
    flex-direction: row;
    align-items: flex-start;
    text-align: left;
  }

  .form {
    width: 50vw;
    padding-right: 1em;
  }
}
</style>
