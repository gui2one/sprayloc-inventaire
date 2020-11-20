<template>
  <div>
    <p v-for="(image, index) in images" :key="index">image</p>
  </div>
</template>
<script>
export default {
  name: "ImageList",
  props: ["images"],
};
</script>

<style lang="scss" scoped></style>
