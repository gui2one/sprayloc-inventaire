<template>
  <div>
    <md-dialog-confirm
      :md-active.sync="active"
      md-title="Remove Item"
      md-content="<strong>Attention</strong>. Cette Action ne peut pas être annulée."
      md-confirm-text="Remove Item"
      md-cancel-text="Cancel"
      @md-cancel="onCancel"
      @md-confirm="onConfirm"
    />

    <md-card>
      <md-card-header>
        {{ displayData.name }}
      </md-card-header>

      <md-card-content>
        {{ displayData.desc }}
      </md-card-content>
      <md-card-actions>
        <md-button class="md-dense md-primary md-raised md-icon-button" @click="onEdit()"
          ><md-icon>edit</md-icon></md-button
        >

        <md-button class="md-dense md-accent md-raised md-icon-button" @click="active = true"
          ><md-icon>delete</md-icon></md-button
        >
      </md-card-actions>
    </md-card>
  </div>
</template>
<script>
import { mapGetters, mapMutations, mapState } from "vuex";
export default {
  name: "EquipmentCard",
  props: ["item"],
  data: () => {
    return {
      active: false,
      value: null,
    };
  },
  methods: {
    ...mapMutations(["setCurrentID", "setCurrentItem"]),
    ...mapGetters(["getCurrentItem"]),
    onCancel() {
      console.log("cancel");
    },
    onConfirm() {
      console.log("confirm");

      this.$store.dispatch("removeItem", this.item.id);
    },
    onEdit() {
      // console.log("edit");
      // this.$store.dispatch("editItem", this.item.id);
      this.setCurrentID(this.item.id);
      this.$router.push({ path: `/edit/${this.item.id}` });
      // this.setCurrentItem(this.item);
    },
  },
  computed: {
    ...mapState(["items"]),
    displayData() {
      return this.item.json_data;
    },
  },
};
</script>

<style lang="scss" scoped></style>
