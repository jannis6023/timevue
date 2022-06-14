<template>
  <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" href="https://bulma.io">
        <h2 class="m-0 is-size-5">TimeVue</h2>
      </a>

      <button role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" @click="$store.commit('toggleNav')">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </button>
    </div>

    <div id="navbarBasicExample" class="navbar-menu" :class="$store.state.showNavMobile ? 'is-active':''">
      <div class="navbar-start">
        <router-link to="/admin/dashboard" class="navbar-item" active-class="is-active" tag="a">
          Dashboard
        </router-link>

        <router-link to="/admin/team" class="navbar-item" active-class="is-active" tag="a">
          Team
        </router-link>
      </div>

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">
            <a class="button is-light" href="/logout">
              Abmelden
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: "NavBar"
}
</script>

<style scoped>

</style>