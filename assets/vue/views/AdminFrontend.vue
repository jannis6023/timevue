<template>
  <NavBar/>
  <router-view></router-view>
</template>

<script>
import NavBar from "../components/NavBar";
import "@tabler/core/dist/js/tabler"
export default {
  name: "AdminFrontend",
  components: {NavBar},
  mounted() {
    this.$store.commit("fetchUser")
  }
}
</script>

<style scoped>

</style>