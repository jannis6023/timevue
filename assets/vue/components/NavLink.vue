<template>
  <AppLink
    v-bind="$attrs"
    active-class="active"
    inactive-class=""
  >
    <slot/>
  </AppLink>

</template>

<script>
import AppLink from "./AppLink";
export default {
  name: "NavLink",
  components: {AppLink}
}
</script>

<style scoped>

</style>