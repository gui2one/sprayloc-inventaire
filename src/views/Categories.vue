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
    <md-button class="md-icon-button" @click="onAdd"><md-icon>add</md-icon></md-button>

    <div v-for="cat in categories" :key="cat.id">
      <category-item />
      <md-button class="md-icon-button md-raised md-primary"><md-icon>edit</md-icon></md-button>
      <md-button class="md-icon-button md-raised md-accent" @click="onClickDelete(cat)"
        ><md-icon>delete</md-icon></md-button
      >
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
import CategoryItem from "@/components/CategoryItem.vue";
export default {
  name: "Categories",
  components: { CategoryItem },
  data() {
    return {
      delete_modal_active: false,
      edit_modal_active: false,
      id_to_delete: null,
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
    onClickDelete(data) {
      this.delete_modal_active = true;
      console.log(data.id);
      this.id_to_delete = data.id;
    },
    onConfirmDelete() {
      this.$store.dispatch("removeCategory", this.id_to_delete);
    },
  },
  computed: {
    ...mapState(["categories"]),
  },
};
</script>

<style lang="scss" scoped></style>
