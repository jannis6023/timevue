<template>
  <header class="navbar navbar-expand-md navbar-light d-print-none">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" :aria-expanded="$store.state.showNavMobile ? 'true':'false'" @click="$store.commit('toggleNav')">
        <span class="navbar-toggler-icon"></span>
      </button>
      <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
        <a href=".">
          TimeVue
        </a>
      </h1>
      <div class="navbar-nav flex-row order-md-last" v-if="$store.state.user != null">
        <div class="nav-item dropdown">
          <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
            <span class="avatar avatar-sm">{{ $store.state.user.displayName.split(" ")[0].charAt(0) + $store.state.user.displayName.split(" ")[1].charAt(0) }}</span>
            <div class="d-none d-xl-block ps-2">
              <div>{{ $store.state.user.displayName }}</div>
              <div class="mt-1 small text-muted">{{ $store.state.user.email }}</div>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <a href="/logout" class="dropdown-item">Logout</a>
          </div>
        </div>
      </div>
      <div class="collapse navbar-collapse" ref="collapse" id="navbar-menu">
        <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
          <ul class="navbar-nav">
            <NavLink to="/admin/dashboard">Dashboard</NavLink>
            <NavLink to="/admin/team">Team</NavLink>
          </ul>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import {Collapse} from "bootstrap"
import {mapState} from "vuex"
import NavLink from "./NavLink";
export default {
  name: "NavBar",
  components: {NavLink},
  mounted() {
    this.collapse = Collapse.getOrCreateInstance(this.$refs.collapse, {
      toggle: false
    })
  },
  data(){
    return {
      collapse: null
    }
  },
  watch: {
    showNavMobile(newValue, oldValue){
      this.collapse.toggle()
    }
  },
  computed: mapState(["showNavMobile"])
}
</script>

<style scoped>

</style>