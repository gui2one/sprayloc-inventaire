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
    categories: [],
    currentID: 0,
    currentItem: {},
    images: [],
  },
  mutations: {
    login(state) {
      state.isLoggedIn = true;
    },
    setItems(state, payload) {
      let sorted = payload.sort(function(valueA, valueB) {
        // console.log(valueA.id, valueB.id);
        return parseInt(valueB.id) - parseInt(valueA.id);
      });
      state.items = sorted;
      // console.log("%c setItems MUTATION ", "background: darkgreen; color: white", sorted);
    },
    setCategories(state, payload) {
      console.log("%c Set Categories State ", "background: #0f0; color: white");
      state.categories = payload; //.sort((A, B) => A.json_data.display_id < B.json_data.display_id);
    },
    setCurrentID(state, payload) {
      state.currentID = payload;
      // console.log("%c setCurrentID MUTATION ", "background: darkgreen; color: white", payload);
    },
    setCurrentItem(state, payload) {
      state.currentItem = payload;
      // console.log("%c setCurrentItem MUTATION ", "background: darkgreen; color: white", payload);
    },
    setLoaded(state) {
      state.isDataLoaded = true;
      // console.log(state.isDataLoaded);
    },
    setImages(state, payload) {
      state.images = payload;
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

    async addItem(context) {
      let formData = new FormData();
      formData.append(
        "json_data",
        JSON.stringify({ name: "default name", desc: "default description", category: 0, quantity: 1 })
      );
      const response = await fetch("/php/item_add.php", {
        method: "POST",
        body: formData,
      });
      const data = await response.text();

      console.log(data);
      context.dispatch("loadMysqlData");
      context.dispatch("loadMysqlCategories");
    },

    async removeItem(context, item_sql_id) {
      let formData = new FormData();
      formData.append("id", item_sql_id);
      // const response =
      await fetch("/php/item_remove.php", {
        method: "POST",
        body: formData,
      });
      // const data = await response.text();
      // console.log(data);
      context.dispatch("loadMysqlData");
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
    },

    async uploadImages(context, files) {
      let formData = new FormData();
      let inc = 0;
      for (let file of files) {
        formData.append("file_" + inc, file);
        inc++;
      }
      await fetch("/php/upload_images.php", {
        method: "POST",
        body: formData,
      });

      context.dispatch("loadImagesFromDir");
    },

    async loadMysqlCategories(context) {
      console.log("%c Load SQL Categories ", "background: #f00; color: white");
      const response = await fetch("/php/categories_load.php");
      const data = await response.text();

      let json_data = await JSON.parse(data);

      for (let item of json_data) {
        item.id = parseInt(item.id);
        console.log(item.json_data);
      }

      context.commit(
        "setCategories",
        json_data.sort((A, B) => parseInt(A.json_data.display_id) - parseInt(B.json_data.display_id))
      );

      // if (context.state.currentID != -1) {
      //   context.commit("setCurrentItem", context.state.items.filter((value) => value.id == context.state.currentID)[0]);
      // }
      // context.commit("setLoaded");
    },

    async addCategorie(context, item_data) {
      if (item_data == undefined) {
        item_data = {};
      }
      let formData = new FormData();
      formData.append("json_data", JSON.stringify(item_data));
      // const response =
      await fetch("/php/categories_add.php", {
        data: formData,
      });
      // const data = await response.text();

      // console.log(data);
      context.dispatch("loadMysqlCategories");
    },
    async removeCategory(context, item_sql_id) {
      let formData = new FormData();
      formData.append("id", item_sql_id);
      // const response =
      await fetch("/php/categories_remove.php", {
        method: "POST",
        body: formData,
      });
      // const data = await response.text();
      // console.log(data);
      context.dispatch("loadMysqlCategories");
    },

    async updateCategories(context, payload) {
      console.log("payload : ", payload);
      let formData = new FormData();

      let counter = 0;
      console.log(payload);

      formData.append("num_cats", payload.length);
      for (let item of payload) {
        formData.append("cat_id_" + counter, item.id);
        formData.append("cat_json_data_" + counter, JSON.stringify(item.json_data));
        counter++;
      }

      const response = await fetch("/php/categories_update.php", {
        method: "POST",
        body: formData,
      });
      const data = await response.text();
      console.log(data);
      // context.dispatch("loadMysqlCategories");
    },
    async loadImagesFromDir(context) {
      const response = await fetch("/php/read_upload_dir.php", {
        method: "POST",
      });

      const data = await response.json();
      // console.log(data);
      this.files = data;
      context.commit("setImages", data);
    },

    async deleteFileFromDir(context, payload) {
      let formData = new FormData();
      // console.log(payload);
      formData.append("url", payload);
      const response = await fetch("/php/delete_file_from_dir.php", {
        method: "POST",
        body: formData,
      });

      const _data = await response.text();

      console.log(_data);
      context.dispatch("loadImagesFromDir");
    },
  },
  getters: {
    getCurrentItem(state) {
      return state.currentItem;
    },
  },

  modules: {},
});
