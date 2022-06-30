<template>

  <div class="page-wrapper" v-if="employee !== null">
    <div class="container-xl">
      <!-- Page title -->
      <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
          <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">
              Arbeitnehmer
            </div>
            <h2 class="page-title">
              {{employee.name}}
            </h2>
          </div>
          <!-- Page title actions -->
          <div class="col-auto ms-auto d-print-none">
            <button class="btn btn-primary" @click="copyURL">Link kopieren</button>
          </div>
        </div>
      </div>
    </div>

    <div class="page-body">
      <div class="container-xl">
        <div class="card mb-3">
          <div class="card-header">
            <h3 class="card-title">Stammdaten</h3>
          </div>
          <div class="card-body">
            <div class="row align-items-start">
              <div class="col">
                <label class="form-label">Name des Arbeitnehmers</label>
                <input class="form-control" v-model="employee.name" type="text">
              </div>
              <div class="col">
                <label class="form-label">Arbeitsstunden pro Monat</label>
                <div class="input-group">
                  <input class="form-control" v-model="employee.maxHoursPerMonth" type="number">
                  <span class="input-group-text bg-secondary">
                    h/m
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer d-flex justify-content-between">
            <button class="btn btn-danger me-auto" @click="deleteUser"><span v-if="!confirmDelete">Mitarbeiter löschen</span><span v-if="confirmDelete">?</span></button>
            <button class="btn btn-success" @click="updateEmployee">Änderungen sichern</button>
          </div>
        </div>

        <div class="alert alert-warning mb-3" v-if="employee.currentShift !== null">
          Es liegt gerade eine aktive Schicht vor!
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title me-3">Stundenübersicht</h3>
            <ul class="pagination mb-0">
              <li class="page-item" :class="showMonth === month ? 'active':''" v-for="(shifts, month) in shiftMonths">
                <button @click="showMonth = month" class="btn page-link">{{getMonthName(month)}}</button>
              </li>
            </ul>
          </div>
          <table class="table is-fullwidth card-table" v-if="showMonth !== null">
            <thead>
            <tr>
              <th>Start</th>
              <th>Stop</th>
              <th>Total</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="shift in currentMonthShifts">
              <td>{{new Date(shift.startTime).toLocaleString()}}</td>
              <td>{{new Date(shift.endTime).toLocaleTimeString()}}</td>
              <td>{{toSecondsString(shift.totalSeconds*1000)}}</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
              <th colspan="2">{{getMonthName(showMonth)}}</th>
              <th>{{toSecondsString(sumShifts(currentMonthShifts)*1000)}}</th>
            </tr>
            </tfoot>
          </table>
        </div>



      </div>
    </div>
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
    currentMonthShifts(){
      if(this.showMonth === null){
        return [];
      }else{
        return this.shiftMonths[this.showMonth]
      }
    },
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
        return months;
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
          result = result + s.totalSeconds;
        })
        console.log(result)
        return result;
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
      console.log(monthIndex)
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