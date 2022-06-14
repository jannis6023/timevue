<template>
  <div class="container mt-3" v-if="employee !== null">
    <div class="is-flex is-justify-content-space-between">
      <h1 class="title mb-0">{{employee.name}}</h1> <button class="button" @click="copyURL"><span class="icon"><i class="bi bi-link"></i></span></button>
    </div>
    <hr class="mt-0">
    <div class="columns">
      <div class="column ">
        <div class="field">
          <label class="label">Name des Arbeitnehmers</label>
          <div class="control">
            <input class="input" v-model="employee.name" type="text">
          </div>
        </div>
      </div>

      <div class="column">
        <label class="label">Arbeitsstunden pro Monat</label>
        <div class="field has-addons">
          <div class="control is-expanded">
            <input class="input" v-model="employee.maxHoursPerMonth" type="number">
          </div>
          <p class="control">
            <a class="button is-static">
              h/m
            </a>
          </p>
        </div>
      </div>

      <div class="column">
        <label class="label">Aktionen</label>
        <div class="field has-addons">
          <div class="control is-expanded">
            <button class="is-fullwidth button is-danger w-50 is-inline is-light" @click="deleteUser" :class="deleting ? 'is-deleting':''"><span v-if="!confirmDelete">Mitarbeiter löschen</span><i class="bi bi-question-lg" v-if="confirmDelete"></i> </button>
          </div>
          <div class="control is-expanded">
            <button class="is-fullwidth button is-primary w-50 is-inline is-light" @click="updateEmployee">Änderungen sichern</button>
          </div>
        </div>

      </div>
    </div>
    <hr>
    <div class="notification is-warning" v-if="employee.currentShift !== null">
      Es liegt gerade eine aktive Schicht vor!
    </div>
    <nav class="pagination" role="navigation" aria-label="pagination">
      <ul class="pagination-list">
        <li v-for="(shifts, month) in shiftMonths">
          <button @click="showMonth = month" class="button pagination-link" :class="showMonth === month ? 'is-current':''">{{getMonthName(month)}}</button>
        </li>
      </ul>
    </nav>
    <table class="table is-fullwidth">
      <thead>
      <tr>
        <th>Start</th>
        <th>Stop</th>
        <th>Total</th>
      </tr>
      </thead>
      <template v-for="(shifts, month) in shiftMonths">
        <thead>
        <tr>
          <th colspan="2">{{getMonthName(month)}}</th>
          <th>{{sumShifts(shifts)}}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="shift in shifts">
          <td>{{new Date(shift.startTime).toLocaleString()}}</td>
          <td>{{new Date(shift.endTime).toLocaleTimeString()}}</td>
          <td>{{toSecondsString(shift.totalSeconds*1000)}}</td>
        </tr>
        </tbody>
        <tr v-for="shift in shifts"></tr>
      </template>
    </table>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "EmployeeDetail",
  props: ["id"],
  data(){
    return {
      employee: null,
      showMonth: null,
      confirmDelete: false,
      deleting: false
    }
  },
  mounted() {
    axios.get("/api/v1/admin/employees/" + this.id)
        .then(r => {
          this.employee = r.data
          this.showMonth = Object.keys(this.shiftMonths)[0]
        })
  },
  computed: {
    shiftMonths(){
      if(this.employee === null){
        return []
      }else{
        const months = {}
        this.employee.shifts.forEach(shift => {
          if(months[new Date(shift.startTime).getMonth() + ";" + new Date(shift.startTime).getFullYear()] !== undefined){
            months[new Date(shift.startTime).getMonth() + ";" + new Date(shift.startTime).getFullYear()].push(shift)
          }else{
            months[new Date(shift.startTime).getMonth() + ";" + new Date(shift.startTime).getFullYear()] = []
            months[new Date(shift.startTime).getMonth() + ";" + new Date(shift.startTime).getFullYear()].push(shift)
          }
        })
        return months
      }
    },
    totalDateDiffYear(){
      if(this.employee === null){
        return null;
      }else{
        let totalMilliS = 0;
        this.employee.shifts.filter(s => new Date(s.startTime).getFullYear() === new Date().getFullYear()).forEach(s => {
          const dateDifference = new Date(s.totalSeconds*1000)
          if(dateDifference.getSeconds() > 0){
            dateDifference.setMinutes(dateDifference.getMinutes()+1)
          }
          totalMilliS = totalMilliS + dateDifference.getTime()
        })
        return new Date(totalMilliS);
      }
    }
  },
  methods: {
    sumShifts(shifts){
      let result = 0;
      if(this.employee !== null){
        shifts.forEach(s => {
          const dateDifference = new Date(s.totalSeconds*1000)
          if(dateDifference.getSeconds() > 0){
            dateDifference.setMinutes(dateDifference.getMinutes()+1)
          }

          result = result + dateDifference.getTime()
        })
        console.log(result)
        const dateDifference = new Date(result);
        return `${dateDifference.getHours()-1}h ${dateDifference.getSeconds() > 0 ? dateDifference.getMinutes() : dateDifference.getMinutes()-1}m`
      }else{
        return -1;
      }
    },
    toSecondsString(milliseconds){
      const dateDifference = new Date(milliseconds)
      if(dateDifference.getSeconds() > 0){
        dateDifference.setMinutes(dateDifference.getMinutes()+1)
      }
      return `${dateDifference.getHours()-1}h ${dateDifference.getSeconds() > 0 ? dateDifference.getMinutes() : dateDifference.getMinutes()-1}m`
    },
    getMonthName(monthIndex){
      const date = new Date(null);
      date.setMonth(monthIndex.split(';')[0]);
      date.setFullYear(monthIndex.split(';')[1])
      return date.toLocaleString('default', {month: "long", year: 'numeric'})
    },
    copyURL(){
      navigator.clipboard.writeText(window.location.origin + "/employee/" + this.employee.id)
      this.$toast.success("URL erfolgreich kopiert!")
    },
    deleteUser(){
      if(this.confirmDelete){
        this.confirmDelete = false
        this.deleting = true
        axios.delete("/api/v1/admin/employees/" + this.employee.id)
            .then(r => {
              if(r.data.success){
                this.$toast.success("Der Arbeitnehmer wurde gelöscht.")
              }else{
                this.$toast.warning("Da für den Arbeitnehmer Schichten verzeichnet sind, konnte er nur archiviert werden.")
              }
              this.$router.push('/admin/team')
              this.deleting = false;
            })
      }else{
        this.confirmDelete = true
      }
    },
    updateEmployee(){
      axios.post("/api/v1/admin/employees/" + this.employee.id, this.employee)
          .then(r => {
            this.$toast.success("Änderungen übernommen.")
          })
    }
  }
}
</script>

<style scoped>

</style>