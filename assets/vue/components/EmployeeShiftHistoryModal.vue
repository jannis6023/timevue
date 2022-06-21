<template>
  <div class="modal modal-blur fade" :ref="name" id="modal-simple" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <button type="button" class="btn-close" @click="$emit('close')" aria-label="Close"></button>
        <div class="modal-header">
          <h3 class="modal-title">Schichthistorie</h3>
        </div>
        <table v-if="employee !== null" class="table is-fullwidth">
          <thead>
          <tr>
            <th>Start</th>
            <th>Ende</th>
            <th>Total</th>
          </tr>
          </thead>
          <tbody v-if="employee !== null">
          <tr v-for="shift in employee.shifts.filter(s => new Date(s.startTime).getMonth() === new Date().getMonth())">
            <td>{{new Date(shift.startTime).toLocaleString()}}</td>
            <td>{{new Date(shift.endTime).toLocaleTimeString()}}</td>
            <td>{{toSecondsString(shift.totalSeconds * 1000)}}</td>
          </tr>
          </tbody>
          <tfoot>
          <tr>
            <td>Total</td>
            <td></td>
            <td>{{totalShifts}}</td>
          </tr>
          </tfoot>
        </table>
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
      name: "EmployeeShiftHistoryModal"
    }
  },
  props: ["show", "employee"],
  emits: [ "close" ],
  methods: {
    closeModal(){
      this.$emit('close', false)
    },
    toSecondsString(milliseconds){
      const dateDifference = new Date(milliseconds)
      if(dateDifference.getSeconds() > 0){
        dateDifference.setMinutes(dateDifference.getMinutes()+1)
      }
      return `${dateDifference.getHours()-1}h ${dateDifference.getSeconds() > 0 ? dateDifference.getMinutes() : dateDifference.getMinutes()-1}m`
    }
  },
  watch: {
    show(newVal, oldVal){
      if(newVal !== oldVal){
        let modal = Modal.getOrCreateInstance(this.$refs[this.name])
        modal.toggle()
      }
    }
  },
  computed: {
    totalShifts(){
      let result = 0;
      if(this.employee !== null){
        this.employee.shifts.forEach(s => {
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
    }
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