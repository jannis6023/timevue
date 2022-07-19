import { createStore } from 'vuex'
import axios from "axios";

export default createStore({
  state: {
      user: null,
      showNavMobile: false
  },
  mutations: {
    fetchUser(){
      axios.get(window.location.protocol + '//' + window.location.host + "/api/v1/me/")
          .then(r => {
            this.state.user = r.data
          })
          .catch(() => {
              this.state.user = null
          })
    },
      toggleNav(state){
        state.showNavMobile = !state.showNavMobile
      }
  }
})
