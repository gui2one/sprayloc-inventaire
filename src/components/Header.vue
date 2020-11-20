<template>
  <div id="header" ref="header">
    <div id="nav">
      <router-link to="/">Home</router-link> | <router-link to="/about">About</router-link> |
      <a v-if="!$auth.isAuthenticated" @click="login">Login</a>
    </div>
    <fa-icon class="btn-icon" v-if="$auth.isAuthenticated" icon="user" size="2x" @click="toggleMenu" />
    <!-- <fa-icon class="btn-icon" v-if="!$auth.isAuthenticated" icon="plug" size="2x" @click="login" /> -->

    <div
      v-if="$auth.isAuthenticated"
      id="user-menu"
      ref="user-menu"
      :style="style"
      :class="showUserMenu ? '' : 'hidden'"
      @click="clickUserMenu()"
    >
      <ul>
        <li>
          <div><fa-icon icon="sign-out-alt" /><a @click="logout">log out</a></div>
        </li>

        <li>
          <div>
            <fa-icon icon="user" />
            <router-link to="/profile">Profile</router-link>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: "Header",
  data: () => {
    return {
      showUserMenu: false,
    };
  },
  mounted() {
    // console.log(this.$refs["user-menu"]);
    // console.log(this.headerHeight().toString());
    // let menu = this.$refs["user-menu"];
    // menu.style.top = this.headerHeight().toString() + "px";
    // console.log(menu.style.top);
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
    toggleMenu() {
      this.$refs["user-menu"].classList.toggle("hidden");
    },
    clickUserMenu() {
      this.toggleMenu();
    },
    headerHeight() {
      // let height = t;
      return this.$refs["header"].clientHeight;
    },
  },
  computed: {
    style() {
      return {
        top: this.headerHeight().toString() + "px",
      };
    },
  },
};
</script>

<style lang="scss" scoped>
router-link {
  color: lighten($primary-color, 15%);
  text-decoration: none;
}
#user-menu {
  min-width: 100px;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: left;
  // padding: 1em;
  background: white;
  position: absolute;
  right: 0%;
  // top: 150px;
  // background-color: tomato;
  transition: transform 0.15s ease;
  border-style: solid;
  border-width: 0 0 1px 1px;
  border-color: rgba($primary-color, 0.5);

  * {
    text-align: left;
  }
  ul {
    list-style: none;
    padding: 0;

    li {
      display: block;
      padding: 0.3em;
    }
  }
  a {
    display: inline-block;
    padding: 0 0.3em;
    font-weight: bold;
    color: $primary-color;
    cursor: pointer;
    text-decoration: none;

    &:hover {
      text-decoration: underline;
    }
  }
  &.hidden {
    transform: translateX(calc(100% + 1px));
  }
}
.btn-icon {
  cursor: pointer;

  &:hover {
    opacity: 0.6;
  }
}
#header {
  border-bottom: 1px solid rgba(black, 0.1);
  display: flex;
  justify-content: flex-end;
  align-items: center;

  padding: 0 1em;
  #nav {
    padding: 15px;
    display: flex;
    justify-content: flex-end;
    align-items: center;

    a {
      display: inline-block;
      padding: 0 0.3em;
      font-weight: bold;
      color: $primary-color;
      cursor: pointer;
    }

    .router-link-exact-active {
      color: lighten($primary-color, 20%);
    }
  }
}
</style>
