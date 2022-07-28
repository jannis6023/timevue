<template>
  <div class="page-wrapper">
    <div class="container-xl">
      <!-- Page title -->
      <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
          <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">
              Übersicht
            </div>
            <h2 class="page-title">
              Dashboard
            </h2>
          </div>
        </div>
      </div>
    </div>

    <div class="page-body" v-if="secondsTotal !== 0">
      <div class="container-xl">
        <div class="col-12">
          <div class="row row-cards">
            <div class="col-12">
              <div class="card card-sm">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                        <polyline points="12 7 12 12 15 15"></polyline>
                      </svg>
                    </div>
                    <div class="col">
                      <div class="font-weight-medium">
                        {{toSecondsString(secondsTotal*1000)}}
                      </div>
                    </div>
                  </div>
                </div>
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
  name: "DashBoard",
  data(){
    return {
      secondsTotal: 0
    }
  },
  mounted() {
    axios.get("/api/v1/admin/stats")
        .then(r => {
          this.secondsTotal = r.data
        })
  },
  methods: {
    toSecondsString(milliseconds){
      const dateDifference = new Date(milliseconds)
      if(dateDifference.getSeconds() > 0){
        dateDifference.setMinutes(dateDifference.getMinutes()+1)
      }
      return `${dateDifference.getHours()-1}h ${dateDifference.getSeconds() > 0 ? dateDifference.getMinutes() : dateDifference.getMinutes()-1}m`
    }
  }
}
</script>

<style scoped>

</style>