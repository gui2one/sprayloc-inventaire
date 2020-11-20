import Vue from "vue";
import Vuex from "vuex";

import { allowed_emails } from "../../auth_config.json";
Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    isLoggedIn: false,
    isDataLoaded: false,
    allowed_emails: allowed_emails,
    items: [],
    currentID: 0,
    currentItem: {},
  },
  mutations: {
    login(state) {
      state.isLoggedIn = true;
    },
    setItems(state, payload) {
      // let sorted = payload.sort(function(valueA, valueB) {
      //   // console.log(valueA.id, valueB.id);
      //   return parseInt(valueA.id) - parseInt(valueB.id);
      // });
      state.items = payload;

      console.log(typeof payload[0].id);
      console.log("%c setItems MUTATION ", "background: darkgreen; color: white", payload);
    },
    setCurrentID(state, payload) {
      state.currentID = payload;
      console.log("%c setCurrentID MUTATION ", "background: darkgreen; color: white", payload);
    },
    setCurrentItem(state, payload) {
      state.currentItem = payload;
      console.log("%c setCurrentItem MUTATION ", "background: darkgreen; color: white", payload);
    },
    setLoaded(state) {
      state.isDataLoaded = true;
      console.log(state.isDataLoaded);
    },
  },
  actions: {
    setCurrentID(context, payload) {
      console.log("%c setCurrentID action ", "background: #f00; color: white", payload);
      context.commit("setCurrentID", payload);
      context.dispatch(
        "setCurrentItem",
        context.state.items.filter((value) => parseInt(value.id) == parseInt(payload))[0]
      );
    },
    setCurrentItem(context, payload) {
      context.commit("setCurrentItem", payload);
      console.log("%c setCurrentItem action ", "background: #f00; color: white", payload);
    },
    async loadMysqlData(context) {
      console.log("%c Load SQL Data action ", "background: #f00; color: white");
      const response = await fetch("/php/items_load.php");
      const data = await response.text();

      let json_data = await JSON.parse(data);

      for (let item of json_data) {
        item.id = parseInt(item.id);
      }
      context.commit("setItems", json_data);

      if (context.state.currentID != -1) {
        context.commit("setCurrentItem", context.state.items.filter((value) => value.id == context.state.currentID)[0]);
      }
      context.commit("setLoaded");
    },

    async addItem(context, item_data) {
      let formData = new FormData();
      formData.append("json_data", JSON.stringify(item_data));
      const response = await fetch("/php/item_add.php", {
        data: formData,
      });
      const data = await response.text();

      console.log(data);
      context.dispatch("loadMysqlData");
      // window.location.reload();
    },

    async removeItem(context, item_sql_id) {
      let formData = new FormData();
      formData.append("id", item_sql_id);
      const response = await fetch("/php/item_remove.php", {
        method: "POST",
        body: formData,
      });
      const data = await response.text();

      console.log(data);
      context.dispatch("loadMysqlData");
      // window.location.reload();
    },

    async updateItem(context, payload) {
      console.log(payload);
      let formData = new FormData();
      formData.append("id", payload.id);
      formData.append("json_data", payload.json_data);
      const response = await fetch("/php/item_update.php", {
        method: "POST",
        body: formData,
      });
      const data = await response.text();

      console.log(data);
      // context.dispatch("loadMysqlData");
    },
  },
  getters: {
    getCurrentItem(state) {
      return state.currentItem;
    },
  },

  modules: {},
});