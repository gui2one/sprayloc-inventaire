<template>
  <md-card class="image-card"><img :src="url"/></md-card>
</template>
<script>
export default {
  name: "UploadManagerImageCard",
  props: ["url"],
};
</script>

<style lang="scss" scoped>
.image-card {
  //   width: 150px;
  height: 150px;

  img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}
</style>
