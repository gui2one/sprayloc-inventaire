<template>
  <div>
    <md-card class="image-card" :class="selected ? 'selected' : ''" @click.native.prevent="onClick">
      <img :src="image.url" />
      <md-button
        v-if="mode !== 'popup'"
        class="delete-btn md-raised md-icon-button md-dense md-accent"
        @click.prevent="onClickDelete(image.url)"
      >
        <md-icon>delete</md-icon>
      </md-button>
    </md-card>
  </div>
</template>
<script>
export default {
  name: "UploadManagerImageCard",
  data() {
    return {
      selected: false,
    };
  },
  props: ["image", "mode"],
  methods: {
    onClickDelete(url) {
      this.$emit("delete-image", url);
      console.log(url);
    },
    onClick() {
      console.log(this.mode);
      if (this.mode !== "stand_alone") {
        this.selected = !this.selected;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.image-card {
  //   width: 150px;
  height: 150px;
  position: relative;
  img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .delete-btn {
    opacity: 0;
    // z-index: 10;
    position: absolute;
    right: 0px;
    bottom: 0px;

    transition: none;
    margin-bottom: 0.5em;

    &:hover {
      opacity: 1 !important;
    }
  }

  &.selected {
    outline: 4px solid lightgreen;
  }
  &:hover {
    .delete-btn {
      opacity: 0.6;
    }
  }
}
</style>
