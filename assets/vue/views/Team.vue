<template>

  <div class="page-wrapper">
    <div class="container-xl">
      <!-- Page title -->
      <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h1 class="page-title fs-1">
              Team
            </h1>
          </div>
          <div class="col col-md-auto ms-auto d-print-none">
            <form @submit.prevent="createEmployee">
              <div class="input-group mb-2">
                <input class="form-control" v-model="newEmployeeName" placeholder="Name...">
                <button class="btn btn-success" type="submit">
                  Anlegen
                </button>
              </div>
            </form>
          </div>
        </div>
        <hr class="mt-2 mb-3">
      </div>
    </div>

    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-sm-6 col-lg-3" v-for="employee in employees">
            <div class="card">
              <div class="card-body">
                <h1 class="h1">{{employee.name}}</h1>
                <button @click="$router.push('/admin/team/' + employee.id)" class="btn btn-primary w-100">Stundenzettel anzeigen</button>
              </div>
            </div>
          </div>
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
          .catch((error) => {
            if(error.response.status === 403){
              this.$toast.error("Dieses Team-Mitglied gibt es bereits")
            }
          });
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