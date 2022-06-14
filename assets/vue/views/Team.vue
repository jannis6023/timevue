<template>
  <div class="container mt-3">
    <div class="is-flex is-justify-content-space-between">
      <h1 class="title mb-0">Team</h1>
      <div class="field has-addons">
        <div class="control" v-if="showCreate">
          <input class="input" v-model="newEmployeeName" placeholder="Name...">
        </div>
        <div class="control" v-if="showCreate">
          <button class="button is-primary" @click="createEmployee">Anlegen</button>
        </div>
        <div class="control">
          <button @click="showCreate = !showCreate" class="button"><i class="bi bi-plus"></i></button>
        </div>
      </div>
    </div>
    <hr class="mt-0">
    <div class="columns is-multiline">
      <div class="column is-one-quarter" v-for="employee in employees">
        <div class="box">
          <h3 class="title is-3">{{employee.name}}</h3>
          <button @click="$router.push('/admin/team/' + employee.id)" class="button is-primary is-fullwidth">Stundenzettel anzeigen</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Team",
  data(){
    return {
      employees: [],
      showCreate: false,
      newEmployeeName: ''
    }
  },
  mounted() {
    this.loadData()
  },
  methods: {
    createEmployee(){
      axios.put("/api/v1/admin/employees", {name: this.newEmployeeName})
          .then(r => {
            this.showCreate = false
            this.newEmployeeName = ''
            this.$toast.success("Team-Mitglied erfolgreich angelegt")
            this.loadData()
          })
    },
    loadData(){
      axios.get("/api/v1/admin/employees")
          .then(r => {
            this.employees = r.data
          })
    }
  }
}
</script>

<style scoped>

</style>