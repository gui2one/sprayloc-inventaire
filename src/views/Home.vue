<template>
  <div class="home">
    <div v-if="!$auth.loading">
      <EquipmentList v-if="$auth.isAuthenticated" />
      <p v-else>Login To View the Data</p>
      <!-- <div v-for="item in items" :key="item.name">{{ item.data.name }}</div> -->
    </div>
    <div v-else>
      <md-progress-spinner md-mode="indeterminate"></md-progress-spinner>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src

import EquipmentList from "@/components/EquipmentList";
import { mapState } from "vuex";
// import { Analyzer } from "@/modules/ObjectAnalyzer.js";
export default {
  name: "Home",
  components: { EquipmentList },
  mounted() {
    if (this.$auth.isAuthenticated) {
      // console.log("ssdfsdfsdfsdfs<dfedfbgnb");
    }
  },
  methods: {
    // Log the user in
    login() {
      this.$auth.loginWithRedirect();
    },
    // Log the user out
    logout() {
      this.$auth.logout({
        returnTo: window.location.origin,
      });
    },
  },
  computed: {
    ...mapState(["items"]),
    loggedIn() {
      return this.$auth.isAuthenticated;
    },
  },
};
</script>
