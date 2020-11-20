<template>
  <div v-if="!$auth.loading">
    <form class="md-layout" @submit.prevent="validateUser">
      <div>Edit ..... {{ $route.params.id }}</div>

      <md-field>
        <label>Name</label>
        <md-input v-model="currentItem.json_data.name"></md-input>
      </md-field>
      <md-field>
        <label>Description</label>
        <md-input v-model="currentItem.json_data.desc"></md-input>
      </md-field>
      <md-button type="submit" class="md-primary md-raised" :disabled="sending">Save Data</md-button>
    </form>
    <!-- {{ currentItem }} -->
  </div>
</template>
<script>
import { mapActions, mapState } from "vuex";

export default {
  name: "EditDetails",

  data: () => {
    return {
      // name: null,
      // desc: null,
      sending: false,
    };
  },

  mounted() {
    // this.$store.dispatch("setCurrentID", this.$route.params.id);
    this.setCurrentID(this.$route.params.id);
    // this.name = this.currentItem.json_data.name;
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

<style lang="scss" scoped></style>
