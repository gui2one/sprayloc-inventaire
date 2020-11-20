<template>
  <div id="app">
    <Header />
    <div v-if="$auth.isAuthenticated" id="main-wrapper">
      <router-view />
    </div>
    <div v-else>
      <md-progress-spinner md-mode="indeterminate"></md-progress-spinner>
    </div>
  </div>
</template>

<script>
import Header from "@/components/Header.vue";
import "./icons.js";
import "./material.js";
export default {
  name: "App",
  components: { Header },
  created() {
    this.$store.dispatch("loadMysqlData");
  },
};
</script>
<style lang="scss">
html,
body {
  padding: 0px;
  margin: 0;
  box-sizing: border-box;
  overflow-x: hidden;
}
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: $primary-color;
}

#main-wrapper {
  // padding-top: 15px;
  padding: 1em;
}
</style>
