<template>
  <div class="modal modal-blur fade" :ref="name" id="modal-simple" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <button type="button" class="btn-close" @click="$emit('close')" aria-label="Close"></button>
        <div class="modal-header">
          <h3 class="modal-title me-3">Schichthistorie</h3>
          <ul class="pagination mb-0">
            <li class="page-item" :class="showMonth === month ? 'active':''" v-for="(shifts, month) in shiftMonths">
              <button v-if="month !== undefined" @click="showMonth = month" class="btn page-link">{{getMonthName(month)}}</button>
            </li>
          </ul>
        </div>
        <div class="modal-body">
          <table v-if="employee !== null && showMonth !== null && showMonth !== undefined" class="table is-fullwidth">
            <thead>
            <tr>
              <th>Start</th>
              <th>Ende</th>
              <th>Total</th>
            </tr>
            </thead>
            <tbody v-if="employee !== null">
            <tr v-for="shift in currentMonthShifts">
              <td>{{new Date(shift.startTime).toLocaleString()}}</td>
              <td>{{new Date(shift.endTime).toLocaleTimeString()}}</td>
              <td>{{formatMinutes(Math.floor(shift.totalSeconds/60))}}</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
              <td>Total</td>
              <td></td>
              <td>{{formatMinutes(sumShiftsMinutes(currentMonthShifts))}}</td>
            </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Modal } from "bootstrap"
import axios from "axios";
import "@tabler/core/dist/libs/litepicker/dist/litepicker"
export default {
  name: "EmployeeShiftHistoryModal",
  data(){
    return {
      name: "EmployeeShiftHistoryModal",
      showMonth: null
    }
  },
  props: ["show", "employee"],
  emits: [ "close" ],
  methods: {
    closeModal(){
      this.$emit('close', false)
    },
    sumShiftsMinutes(shifts){
      let minutes = 0;
      shifts.forEach(s => {
        minutes = minutes + Math.round(s.totalSeconds / 60)
      })
      return minutes;
    },
    formatMinutes(minutes){
      let hoursString = Math.floor(minutes / 60)
      let minutesString = minutes % 60;
      return hoursString + "h " + minutesString + "m"
    },
    toSecondsString(milliseconds){
      const dateDifference = new Date(milliseconds)
      if(dateDifference.getSeconds() > 0){
        dateDifference.setMinutes(dateDifference.getMinutes()+1)
      }
      return `${dateDifference.getHours()-1}h ${dateDifference.getSeconds() > 0 ? dateDifference.getMinutes() : dateDifference.getMinutes()-1}m`
    },
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
    getMonthName(monthIndex){
      console.log(monthIndex)
      const date = new Date(null);
      date.setMonth(monthIndex.split(';')[0]);
      date.setFullYear(monthIndex.split(';')[1])
      return date.toLocaleString('default', {month: "long", year: 'numeric'})
    },
  },
  watch: {
    show(newVal, oldVal){
      if(newVal !== oldVal){
        let modal = Modal.getOrCreateInstance(this.$refs[this.name])
        modal.toggle()
      }
    },
    employee(){
      this.showMonth = Object.keys(this.shiftMonths)[Object.keys(this.shiftMonths).length-1]
    }
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
  },
  mounted() {
    this.$refs[this.name].addEventListener("hide.bs.modal", (event) => {
      this.$emit('close')
    })
  }
}
</script>

<style scoped>

</style>