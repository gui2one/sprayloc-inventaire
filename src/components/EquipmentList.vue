<template>
  <div id="main_container" ref="main_container">
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
      <md-button
        id="add_item_button"
        class="md-icon-button md-primary md-raised"
        @click="$store.dispatch('addItem', { id: null })"
      >
        <md-icon size="2px">add</md-icon>
      </md-button>

      <md-button class="md-raised md-accent" :disabled="delete_multiple_disabled" @click="onDeleteMultipleClick"
        >Delete Selected</md-button
      >
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
      <md-table
        class="table-list"
        ref="table-list"
        v-model="searched"
        md-sort="id"
        md-fixed-header
        md-sort-order="asc"
        @md-selected="onSelect"
      >
        <md-table-row slot="md-table-row" slot-scope="{ item }" md-selectable="multiple">
          <md-table-cell md-label="ID" md-sort-by="id">{{ item.id }}</md-table-cell>
          <md-table-cell md-label="Name" md-sort-by="json_data.name">{{ item.json_data.name }}</md-table-cell>
          <md-table-cell md-label="Category" md-sort-by="json_data.category">{{ getItemCategory(item) }}</md-table-cell>
          <md-table-cell md-label="Quantity" md-sort-by="json_data.quantity">{{
            item.json_data.quantity
          }}</md-table-cell>

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
// import { nextTick } from "vue";
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
      content_height: 500,
      resize_event: null,
      handle_resize: null,
      delete_multiple_disabled: true,
      selected_items: [],
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
    onCancel() {
      this.remove_id = -1;
    },
    onConfirmDelete() {
      this.$store.dispatch("removeItem", this.remove_id);
      this.remove_id = -1;
    },
    sortByID() {},
    onSelect(selected_items) {
      this.selected_items = selected_items;

      if (this.selected_items.length !== 0) {
        this.delete_multiple_disabled = false;
      } else {
        this.delete_multiple_disabled = true;
      }
    },
    onDeleteMultipleClick() {
      console.log(this.selected_items);
      let to_delete = this.items.filter((value) => {
        return this.selected_items.some((el) => {
          el.id === value.id;
        });
      });
      console.log(to_delete);
    },

    handleResize() {
      if (this.view_mode == "list") this.$refs["table-list"].fixedHeaderTableWidth = 0;
    },
    getItemCategory(item) {
      if (item.json_data.category == 0) {
        return "no category";
      } else {
        let found = this.categories.find((value) => value.id === parseInt(item.json_data.category));
        if (found) {
          return found.json_data.name;
        }

        return null;
      }
    },
  },
  mounted() {
    this.searched = this.items;

    if (window.localStorage.getItem("view_mode") != undefined) {
      this.view_mode = window.localStorage.getItem("view_mode");
    }

    // console.log(this.$store);
    unsubscribe = this.$store.subscribe((mutation) => {
      if (mutation.type == "setItems") {
        // console.log(mutation.type);
        // console.log(mutation.payload);
        this.searched = this.items.sort(function(A, B) {
          return A.id - B.id;
        });
        // unsubscribe();
      }
    });

    let vm = this;
    this.handleResize = function() {
      if (vm.view_mode == "list") vm.$refs["table-list"].fixedHeaderTableWidth = 0;
    };
    this.$nextTick(function() {
      this.resize_event = window.addEventListener("resize", this.handleResize);
    });
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleResize);
    unsubscribe();
    // console.log("destroyed");
  },

  updated() {
    // this.localItems = this.items.sort((A, B) => A.id - B.id);
    this.content_height = window.innerHeight;
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
  },
  computed: {
    ...mapState(["items", "categories", "currentItem"]),
  },
};
</script>

<style lang="scss">
#main_container {
  display: flex;
  flex-direction: column;
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
      margin: 0 2em 0 auto;
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
.md-table-head-container {
  // background: red !important;
  // text-align: left;

  .md-table-head-label {
    padding-left: 20px;
    padding-right: 0px;
  }
}
.md-table-cell {
  // display: inline-table;
  position: relative;
  overflow: hidden;
  white-space: pre;
  text-overflow: ellipsis;
  max-width: 80px;
  width: 80px;
  // background-color: pink !important;

  .md-table-cell-container {
    // display: inline !important;
    position: relative;
    overflow: hidden;
    white-space: pre;
    text-overflow: ellipsis;
    padding-left: 0px;
    padding-right: 10px;
  }
}

.table-list {
  // text-align: left;
}
</style>
