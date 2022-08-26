<template>
  <EmployeeShiftHistoryModal :show="showHistory" :employee="employee" @close="showHistory = false;"/>

  <div class="page page-center">
    <div class="container-tight py-4" v-if="employee !== null">
      <div class="card">
        <div class="card-body">
          <h1 class="card-title h1 fs-1">Hi, {{employee.name}}! 👋</h1>
          <hr class="mt-2 mb-3">
          <div v-if="employee.currentShift === null">
            <div class="alert alert-danger mb-4">
              <h3 class="alert-title">Gerade ist keine Schicht aktiv!</h3>
            </div>
            <button class="btn btn-success w-100" :disabled="loading" :class="loading ? 'btn-loading' : ''" @click="startShift">Schicht starten</button>
          </div>
          <div v-if="employee.currentShift !== null">
            <div class="alert alert-success mb-4">
              <h3 class="alert-title">Eine Schicht ist seit {{new Date(employee.currentShift.startTime).toLocaleTimeString()}} aktiv.</h3>
            </div>
            <button class="btn btn-danger w-100" :disabled="loading" :class="loading ? 'btn-loading' : ''" @click="stopShift">Schicht beenden</button>
          </div>
          <hr>
          <button class="btn w-100" @click="showHistory = true">Schichthistorie anzeigen</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import ShiftHistoryModal from "../components/ShiftHistoryModal";
import checkPoint from "point-in-cirlce"
import EmployeeShiftHistoryModal from "../components/EmployeeShiftHistoryModal";
import "bootstrap"

export default {
  name: "MeEmployee",
  components: {EmployeeShiftHistoryModal, ShiftHistoryModal},
  props: ["id"],
  data(){
    return {
      employee: null,
      showHistory: false,
      loading: false
    }
  },
  mounted() {
    this.loadData()
  },
  methods: {
    startShift(){
      this.loading = true

      navigator.geolocation.getCurrentPosition(pos => {
        axios.get("/var/requiredLocation")
            .then(r => {
              const circle = {
                circleLat : parseFloat(r.data.lat),
                circleLng : parseFloat(r.data.lon),
                circleRadius : parseFloat(r.data.rad)
              }
              console.log(pos.coords)
              if(checkPoint(pos.coords.latitude, pos.coords.longitude, circle).result){
                axios.put("/api/v1/employee/" + this.id + "/shifts")
                    .then(r => {
                      this.$toast.success("Schicht gestartet!")
                      this.loadData()
                    })
              }else{
                this.$toast.error("Sie befinden sich nicht am entsprechenden Standort!")
              }

              this.loading = false
            })
      }, () => {
        this.$toast.error("Sie können keine Schicht ohne Standortfreigabe starten!")
        this.loading = false
      })

    },
    loadData(){
      axios.get("/api/v1/employee/" + this.id)
          .then(r => {
            this.employee = r.data
            if(!this.employee.active){
              this.$toast.error("Ihr Arbeitnehmerkonto ist in TimeVue nicht mehr aktiv!")
            }
          })
    },
    stopShift(){
      this.loading = true
      axios.delete("/api/v1/employee/" + this.id + "/shifts/" + this.employee.currentShift.id, {
        headers: {
          "Content-Type": "application/json"
        }
      })
          .then(r => {
            this.$toast.success("Schicht beendet!")
            this.loadData()
            this.loading = false
          })
    }
  },
  computed: {
    elapsedShiftTimeDate(){
      return new Date(new Date().getTime() - new Date(this.employee.currentShift.startTime).getTime())
    }
  }
}
</script>

<style scoped>
.bg-1{
  background-color: #0B3954;
}
.bg-2{
  background-color: #E26D5A;
}
a:hover{
  transition: 2s;
  color: #005aec;
}
</style>