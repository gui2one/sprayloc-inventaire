<template>
  <div>
    {{ current.json_data.name }}
  </div>
</template>
<script>
import { mapState } from "vuex";
export default {
  name: "category-item",
  props: ["sql_id"],
  computed: {
    ...mapState(["categories"]),
    current() {
      return this.categories.find((value) => value.id == this.sql_id);
    },
  },
};
</script>

<style lang="scss" scoped></style>
