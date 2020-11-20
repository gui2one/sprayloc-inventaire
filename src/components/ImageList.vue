<template>
  <div>
    <draggable v-model="list" tag="ul" :move="onMove" @start="onDragStart">
      <!-- <div class="draggable-item" v-for="image in images" :key="image">{{ image.name }}</div> -->
      <div v-for="(image, index) in list" :key="index">
        <p>{{ image }}</p>
        <ImageListItem />
        <md-button class="md-icon-button md-accent md-raised" @click="onDelete(index)">
          <md-icon>delete</md-icon>
        </md-button>
      </div>
    </draggable>
  </div>
</template>
<script>
import draggable from "vuedraggable";
import ImageListItem from "@/components/ImageListItem.vue";
export default {
  name: "ImageList",
  components: { draggable, ImageListItem },
  props: ["images"],
  data() {
    return {
      isDragging: false,
      //   list: this.images,
    };
  },
  methods: {
    onMove() {
      console.log("moved");
    },
    onDragStart() {
      console.log("Dargged start");
    },
    onDelete(index) {
      this.images.splice(index, 1);
    },
  },
  computed: {
    list: {
      get() {
        return this.images;
      },
      set(value) {
        // this.images.push(value.name);
        console.log(value);
      },
    },
  },
};
</script>

<style lang="scss" scoped>
.draggable-item {
  user-select: none;
}
</style>
