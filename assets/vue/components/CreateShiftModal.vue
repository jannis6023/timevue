<template>
  <div class="modal modal-blur fade" :ref="name" id="modal-simple" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <button type="button" class="btn-close" @click="$emit('close')" aria-label="Close"></button>
        <div class="modal-header">
          <h3 class="modal-title me-3">Schicht nachtragen</h3>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <label class="form-label">Startzeitpunkt</label>
              <div class="input-group">
                <input v-model="newShift.startTime" class="form-control" type="datetime-local" placeholder="Startzeitpunkt">
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Endzeitpunkt</label>
              <div class="input-group">
                <input v-model="newShift.endTime" class="form-control" type="datetime-local" placeholder="Startzeitpunkt">
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary" @click="submit">Erstellen</button>
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
  name: "CreateShiftModal",
  data(){
    return {
      name: "CreateShiftModal",
      newShift: {
        startTime: null,
        endTime: null,
        totalSeconds: null
      }
    }
  },
  props: ["show", "employee"],
  emits: [ "close" ],
  methods: {
    closeModal(){
      this.$emit('close', false)
    },
    submit(){
      if(confirm("Sind Sie sich sicher, dass X Stunden, XX Minuten nachgetragen werden sollen?")){
        axios.put("/api/v1/admin/employees/" + this.employee.id + "/shifts", {
          startTime: new Date(this.newShift.startTime),
          endTime: new Date(this.newShift.endTime)
        })
            .then(r => {
              this.employee.shifts = r.data;
              this.closeModal()
              this.$toast.success("Schicht erstellt!")
            })
      }
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

  },
  mounted() {
    const date = new Date();
    this.newShift.startTime = `${date.getFullYear()}-${date.toLocaleDateString("en-US", {month: '2-digit'})}-${date.getDate()}T${date.getHours()}:${date.getMinutes()}`
    this.newShift.endTime = `${date.getFullYear()}-${date.toLocaleDateString("en-US", {month: '2-digit'})}-${date.getDate()}T${date.getHours()}:${date.getMinutes()}`

    this.$refs[this.name].addEventListener("hide.bs.modal", (event) => {
      this.$emit('close')
    })
  }
}
</script>

<style scoped>

</style>