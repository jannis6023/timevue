<template>
  <ShiftHistoryModal :show="showHistory" :employee="employee" @close="showHistory = false;"/>
  <section class="hero bg-1 is-fullheight">
    <div class="hero-body">
      <div class="container" v-if="employee !== null">
        <div class="columns is-centered" v-if="employee.active">
          <div class="column is-5-tablet is-5-desktop is-5-widescreen">
            <div class="box" v-if="employee !== null">
              <h1 class="is-size-3 mb-0">Hi, {{employee.name}}! 👋</h1>
              <hr class="mt-0">
              <template v-if="employee.currentShift === null">
                <div class="message"><div class="message-body">Gerade ist keine Schicht aktiv!</div> </div>
                <button class="button is-success is-fullwidth" :disabled="loading" :class="loading ? 'is-loading':''" @click="startShift">Schicht starten</button>
              </template>
              <template v-if="employee.currentShift !== null">
                <div class="message"><div class="message-body">Eine Schicht ist seit {{new Date(employee.currentShift.startTime).toLocaleTimeString()}} aktiv.</div></div>
                <button class="button is-danger is-fullwidth" :disabled="loading" :class="loading ? 'is-loading':''" @click="stopShift">Schicht beenden</button>
              </template>
              <hr>
              <button class="button is-fullwidth" @click="showHistory = true">Schichthistorie anzeigen</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from "axios";
import ShiftHistoryModal from "../components/ShiftHistoryModal";
import checkPoint from "point-in-cirlce"

export default {
  name: "MeEmployee",
  components: {ShiftHistoryModal},
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
      axios.delete("/api/v1/employee/" + this.id + "/shifts/" + this.employee.currentShift.id)
          .then(r => {
            this.$toast.success("Schicht beendet!")
            this.loadData()
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