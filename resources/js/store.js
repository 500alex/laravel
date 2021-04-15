import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    snackbar: {
      text:'',
      color: null,
      show: false
    },
  },
  mutations: {
    setSnackbar (state, payload) {
      state.snackbar.text = payload.text
      state.snackbar.color = payload.color
      state.snackbar.show = payload.show
    },
  }
})

export default store;
