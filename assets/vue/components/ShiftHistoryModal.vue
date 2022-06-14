<template>
  <div class="modal" :class="show ? 'is-active':''">
    <div class="modal-background">
      <div class="modal-card mt-5">
        <header class="modal-card-head">
          <p class="modal-card-title">Schichthistorie {{new Date().toLocaleString('default', {month: 'long'})}}</p>
          <button class="delete" @click="$emit('close')"></button>
        </header>
        <section class="modal-card-body">
          <table class="table is-fullwidth">
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
        </section>
        <footer class="modal-card-foot">
          <button class="button is-success is-fullwidth" @click="$emit('close')">Schließen</button>
        </footer>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ShiftHistoryModal",
  props: ["show", "employee"],
  emits: ["close"],
  methods: {
    toSecondsString(milliseconds){
      const dateDifference = new Date(milliseconds)
      if(dateDifference.getSeconds() > 0){
        dateDifference.setMinutes(dateDifference.getMinutes()+1)
      }
      return `${dateDifference.getHours()-1}h ${dateDifference.getSeconds() > 0 ? dateDifference.getMinutes() : dateDifference.getMinutes()-1}m`
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
  }
}
</script>

<style scoped>

</style>