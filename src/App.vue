<template>
  <div id="app">
    <md-toolbar class="md-primary">
      <md-button class="md-icon-button" @click="showNavigation = true">
        <md-icon>menu</md-icon>
      </md-button>
      <!-- <span class="md-title">Inventaire</span> -->

      <div class="md-toolbar-section-end">
        <md-button class="md-icon-button" @click="showSidepanel = true"><md-icon>account_circle</md-icon></md-button>
      </div>
    </md-toolbar>
    <md-drawer class="md-left" :md-active.sync="showNavigation">
      <md-toolbar class="md-transparent" md-elevation="0">
        <span class="md-title">Inventaire</span>
      </md-toolbar>

      <md-list>
        <router-link to="/" class="first-link">
          <md-list-item @click="showNavigation = false">
            <md-icon>home</md-icon>
            <span class="md-list-item-text">Equipements</span>
          </md-list-item>
        </router-link>

        <router-link to="/categories">
          <md-list-item @click="showNavigation = false">
            <md-icon>category</md-icon>
            <span class="md-list-item-text">Categories</span>
          </md-list-item>
        </router-link>

        <router-link to="/files">
          <md-list-item @click="showNavigation = false">
            <md-icon>cloud_download</md-icon>
            <span class="md-list-item-text">Files</span>
          </md-list-item>
        </router-link>

        <router-link to="/about">
          <md-list-item @click="showNavigation = false">
            <md-icon>help</md-icon>
            <span class="md-list-item-text">About</span>
          </md-list-item>
        </router-link>
      </md-list>
    </md-drawer>

    <md-drawer class="md-right" :md-active.sync="showSidepanel">
      <md-toolbar class="md-transparent" md-elevation="0">
        <span class="md-title">User</span>
      </md-toolbar>
      <md-list>
        <router-link to="/profile">
          <md-list-item @click="showSidepanel = false">
            <md-icon>account_circle</md-icon>
            <span class="md-list-item-text">Infos</span>
          </md-list-item>
        </router-link>

        <md-list-item v-if="$auth.isAuthenticated" @click="logout">
          <md-icon>exit_to_app</md-icon>
          <span class="md-list-item-text">Logout</span>
        </md-list-item>

        <a v-else>
          <md-list-item @click="login">
            <md-icon>login</md-icon>
            <span class="md-list-item-text">Login</span>
          </md-list-item>
        </a>
      </md-list>
    </md-drawer>
    <!-- <Header /> -->
    <div v-if="$auth.isAuthenticated" id="main-container">
      <router-view />
    </div>
    <div v-else class="spinner">
      <md-progress-spinner md-mode="indeterminate"></md-progress-spinner>
    </div>
  </div>
</template>

<script>
// import Header from "@/components/Header.vue";

export default {
  name: "App",
  components: {},
  data() {
    return {
      showNavigation: false,
      showSidepanel: false,
    };
  },
  created() {
    this.$store.dispatch("loadMysqlData");
    this.$store.dispatch("loadMysqlCategories");
  },
  methods: {
    // showNavigation() {
    //   console.log("Navigate");
    // },
    login() {
      this.$auth.loginWithRedirect();
      this.showSidepanel = false;
    },
    logout() {
      this.$auth.logout({
        returnTo: window.location.origin,
      });
    },
  },
};
</script>
<style lang="scss">
body {
  overflow-x: hidden;
}

.spinner {
  position: absolute;
  top: 0;
  left: 0;
  display: flex;
  height: 100%;
  width: 100%;
  justify-content: center;
  align-items: center;
}
#main-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  // text-align: center;
  max-width: 1200px;
  padding: 0 1em;
  margin: 1em auto;
  // overflow: hidden;
}
</style>
