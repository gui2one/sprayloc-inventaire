<template>
  <div id="main_container">
    <md-dialog-confirm
      :md-active.sync="modal_active"
      md-title="Remove Item"
      md-content="<strong>Attention</strong>. Cette Action ne peut pas être annulée."
      md-confirm-text="Remove Item"
      md-cancel-text="Cancel"
      @md-cancel="onCancel"
      @md-confirm="onConfirmDelete"
    />
    <h3>Equipment List</h3>
    <div id="buttons">
      <md-button class="md-icon-button md-raised" @click="toggleViewMode">
        <md-icon v-if="view_mode == 'list'">dns</md-icon>
        <md-icon v-else>list</md-icon>
      </md-button>
      <md-button id="add_item_button" class="md-icon-button md-primary md-raised" @click="$store.dispatch('addItem')">
        <md-icon size="2px">add</md-icon>
      </md-button>
      <md-field md-clearable class="search_field">
        <md-icon>search</md-icon><md-input placeholder="Search by name..." v-model="search" @input="searchOnTable" />
      </md-field>
    </div>

    <template v-if="view_mode == 'cards'">
      <div id="equipment_list">
        <EquipmentCard :item="item" v-for="item in searched" :key="item.id" />
      </div>
    </template>
    <div v-else>
      <!-- <md-field md-clearable>
        <md-input placeholder="Search by name..." v-model="search" @input="searchOnTable" />
      </md-field> -->
      <md-table v-model="searched" md-sort="id" md-sort-order="asc" md-card md-fixed-header>
        <md-table-row slot="md-table-row" slot-scope="{ item }">
          <md-table-cell md-label="ID" md-sort-by="id">{{ item.id }}</md-table-cell>
          <md-table-cell md-label="Name" md-sort-by="json_data.name">{{ item.json_data.name }}</md-table-cell>
          <md-table-cell md-label="Description" md-sort-by="json_data.desc">{{ item.json_data.desc }}</md-table-cell>

          <md-table-cell>
            <md-button @click="onEdit(item.id)" class="md-raised md-dense md-icon-button md-primary">
              <md-icon>
                edit
              </md-icon>
            </md-button>
            <md-button @click="onRemove(item.id)" class="md-raised md-dense md-icon-button md-accent">
              <md-icon>
                delete
              </md-icon>
            </md-button>
          </md-table-cell>
        </md-table-row>
      </md-table>
    </div>
  </div>
</template>
<script>
const toLower = (text) => {
  return text.toString().toLowerCase();
};

const searchByName = (items, term) => {
  if (term) {
    let filtered = items.filter((item) => {
      let bool = false;
      if (item.json_data.name) {
        return toLower(item.json_data.name).includes(toLower(term));
      } else {
        return bool;
      }
    });
    // console.log(items.length);
    return filtered;
  }

  return items;
};

let unsubscribe = null;
import { mapActions, mapState } from "vuex";
import EquipmentCard from "./EquipmentCard.vue";
export default {
  name: "EquipmentList",
  data() {
    return {
      view_mode: null,
      search: null,
      searched: [],
      localItems: [],
      modal_active: false,
      remove_id: -1,
    };
  },
  components: { EquipmentCard },
  methods: {
    ...mapActions(["setCurrentID", "setCurrentItem"]),
    setViewMode(value) {
      switch (value) {
        case "list":
          this.view_mode = "list";
          break;
        case "cards":
          this.view_mode = "cards";
          break;
        default:
          console.log("invalid view_mode parameter... ");
          break;
      }
    },
    toggleViewMode() {
      if (this.view_mode == "list") {
        this.view_mode = "cards";
      } else {
        this.view_mode = "list";
      }

      window.localStorage.setItem("view_mode", this.view_mode);
    },
    onEdit(id) {
      // this.setCurrentID(id);
      this.$router.push({ path: `/edit/${id}` });

      // this.setCurrentItem(this.item);
    },
    onRemove(id) {
      console.log(id);
      this.remove_id = id;
      this.modal_active = true;
    },
    searchOnTable() {
      // console.log(this.items);
      this.searched = searchByName(this.items, this.search);
    },
    onCancel() {},
    onConfirmDelete() {
      this.$store.dispatch("removeItem", this.remove_id);
      this.remove_id = -1;
    },
    sortByID() {},
  },
  mounted() {
    this.searched = this.items;
    if (window.localStorage.getItem("view_mode") != undefined) {
      this.view_mode = window.localStorage.getItem("view_mode");
    }

    console.log(this.$store);
    unsubscribe = this.$store.subscribe((mutation) => {
      if (mutation.type == "setItems") {
        console.log(mutation.type);
        console.log(mutation.payload);
        this.searched = this.items.sort(function(A, B) {
          return A.id - B.id;
        });
        // unsubscribe();
      }
      // you may call unsubscribe to stop the subscription
      // unsubscribe();
    });
  },
  destroyed() {
    unsubscribe();
    console.log("destroyed");
  },
  updated() {
    // this.localItems = this.items.sort((A, B) => A.id - B.id);
  },
  watch: {
    searched: function() {
      let items;
      if (this.search) {
        items = searchByName(this.items, this.search);
      } else {
        items = this.items;
      }

      return items;
    },

    // items: function() {
    //   return this.items;
    // },
  },
  computed: {
    ...mapState(["items"]),
    // localItems() {
    //   return this.items;
    // },
  },
};
</script>

<style lang="scss" scoped>
#main_container {
  display: flex;
  flex-direction: column;
  // align-items: flex-end;
  flex-basis: 1;
  // padding: 0 1em;
  max-width: 1200px;
  margin: 0 auto;

  h3 {
    width: 100%;
    text-align: left;
  }

  #buttons {
    display: flex;
    align-items: center;
    padding-bottom: 1em;
    .search_field {
      width: 150px;
      // display: block;
      margin: 0 0 0 auto;
    }
  }
}
#equipment_list {
  width: 100%;
  position: relative;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  //   flex-direction: column;
  //   flex-wrap: wrap;
  //   align-items: flex-end;
  column-gap: 0.5em;
  row-gap: 0.25em;

  .card {
    min-height: 20px;
    // width: 150px;
    // flex-basis: max-content;
  }
}
</style>
