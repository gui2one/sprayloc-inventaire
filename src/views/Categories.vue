<template>
  <div>
    <md-dialog-confirm
      :md-active.sync="delete_modal_active"
      md-title="Remove Category"
      md-content="<strong>Attention</strong>. Cette Action ne peut pas être annulée."
      md-confirm-text="Remove Category"
      md-cancel-text="Cancel"
      @md-cancel="onCancel"
      @md-confirm="onConfirmDelete"
    />
    <md-dialog v-if="current" :md-active.sync="edit_modal_active" :md-click-outside-to-close="false">
      <md-dialog-title>Edit Category</md-dialog-title>
      <form class="form" @submit.prevent="onSaveData">
        <md-field>
          <label>Name {{ current.id }}</label>
          <md-input v-model="current.json_data.name"></md-input>
        </md-field>
        <div>
          <md-button type="submit" class="md-primary md-raised" :disabled="sending">Save Data</md-button>
        </div>
      </form>
    </md-dialog>
    <md-button class="md-icon-button" @click="onAdd"><md-icon>add</md-icon></md-button>

    <draggable id="draggable" v-model="sorted" tag="ul" :move="onMove" @start="onDragStart" @end="onDragEnd">
      <div v-for="cat in sorted" :key="cat.id">
        <md-card class="cat_item">
          <category-item class="title" :sql_id="cat.id" />
          <md-button class="md-icon-button md-raised md-primary md-dense" @click="onClickEdit(cat)">
            <md-icon>edit</md-icon>
          </md-button>
          <md-button class="md-icon-button md-raised md-accent md-dense" @click="onClickDelete(cat)">
            <md-icon>delete</md-icon>
          </md-button>
        </md-card>
      </div>
    </draggable>
  </div>
</template>
<script>
import { mapState } from "vuex";
import draggable from "vuedraggable";
import CategoryItem from "@/components/CategoryItem.vue";
export default {
  name: "Categories",
  components: { CategoryItem, draggable },
  data() {
    return {
      delete_modal_active: false,
      edit_modal_active: false,
      id_to_delete: null,
      id_to_edit: null,
    };
  },
  mounted() {
    this.$store.dispatch("loadMysqlCategories");
  },
  methods: {
    onAdd() {
      this.$store.dispatch("addCategorie", { name: "cat 1" });
    },
    onCancel() {},
    onClickEdit(data) {
      console.log(data);
      this.id_to_edit = data.id;
      this.edit_modal_active = true;
    },
    onClickDelete(data) {
      this.delete_modal_active = true;
      console.log(data.id);
      this.id_to_delete = data.id;
    },
    onConfirmDelete() {
      this.$store.dispatch("removeCategory", this.id_to_delete);
    },
    onMove(data) {
      console.log("move : ", data.draggedContext);
    },
    onDragStart() {
      console.log("Drag Start");
    },
    onDragEnd(data) {
      console.log("Drag End : ", data);

      for (let i = 0; i < this.sorted.length; i++) {
        // console.log(this.sorted[i].json_data);
        try {
          let test = this.sorted[i].json_data.display_id === undefined;
          console.log(test);
          if (test) {
            console.log("reset display id");
            this.sorted[i].json_data.display_id = i;
          }
          //   console.log(this.sorted[i].json_data.display_id);
        } catch (error) {
          //   console.log("error : ", error);
        }
      }

      for (let i = 0; i < this.sorted.length; i++) {
        this.sorted[i].json_data.display_id = i;
        // console.log(this.sorted[i].json_data.display_id);
      }

      let temp = this.sorted; //.sort((valueA, valueB) => valueA.json_data.display_id < valueB.json_data.display_id);
      this.$store.commit("setCategories", temp);

      let stringified = this.sorted.map((value) => {
        return {
          id: value.id,
          json_data: value.json_data,
        };
      });
      // console.log(stringified);
      this.$store.dispatch("updateCategories", stringified);
    },
    onSaveData() {
      // console.log("save ::::");
      this.$store.dispatch("updateCategories", this.sorted);
      this.edit_modal_active = false;
    },
  },
  computed: {
    ...mapState(["categories"]),
    current() {
      return this.categories.filter((value) => value.id == this.id_to_edit)[0];
    },

    sorted: {
      get() {
        return this.categories;
      },
      set(data) {
        console.log(data);
        this.$store.commit("setCategories", data);
      },
    },
  },
};
</script>

<style lang="scss" scoped>
#draggable {
  display: flex;
  flex-direction: column;
  flex-basis: 100%;
  align-items: flex-start;
}

ul {
  padding: 0px;
}
.cat_item {
  display: flex;
  // flex-basis: 10;
  // flex-grow: 10;
  align-items: center;
  padding: 0.33em;
  min-width: 300px;
  width: 100%;
  cursor: grab;
  // max-width: 800px;
  margin: 0.3em auto;

  .title {
    font-size: 1.3em;
    width: 100%;
    text-align: left;
    // border-bottom: 1px solid $primary-color;
    padding-bottom: 0.3em;
  }
}
</style>
