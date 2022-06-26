<template>
  <NavBar/>
  <router-view></router-view>
</template>

<script>
import "bootstrap";
import NavBar from "../components/NavBar";
//import "@tabler/core/dist/js/tabler";

export default {
  name: "AdminFrontend",
  components: {NavBar},
  data(){
    return {
      darkTheme: false
    }
  },
  mounted() {
    this.$store.commit("fetchUser")

    this.darkTheme = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
      const newColorScheme = e.matches ? "dark" : "light";
      this.darkTheme = (newColorScheme === "dark");
    });

  },
  watch: {
    darkTheme(newVal) {
      if (newVal) {
        document.body.classList.add('theme-dark')
      } else {
        document.body.classList.remove('theme-dark')
      }
    }
  }
}
</script>

<style scoped>

</style>