<template>
  <div class="img-container">
    <img :src="url" alt="" />
  </div>
</template>
<script>
export default {
  name: "ImageListItem",
  props: ["url"],
};
</script>

<style lang="scss" scoped>
.img-container {
  width: 150px;
  height: 150px;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}
</style>
