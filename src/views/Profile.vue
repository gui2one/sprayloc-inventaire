<template>
  <div>
    <div>
      <img :src="$auth.user.picture" />
      <h2>{{ $auth.user.name }}</h2>
      <p>{{ $auth.user.email }}</p>
    </div>

    <div>
      <!-- <pre>{{ JSON.stringify($auth.user, null, 2) }}</pre> -->
    </div>
  </div>
</template>
<script>
export default {
  name: "Profile",
  methods: {},
};
</script>

<style lang="scss" scoped>
pre {
  text-align: left;
  font-size: 1.2em;
}
</style>
