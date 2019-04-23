import Vue from 'vue'
import Vuex from 'vuex'
import state from './state'
import mutations from './mutations'

Vue.use(Vuex);

export default new Vuex.Store({
    // actions, //  set多个值
    // getters, //  get
    mutations, //  set
    state //  data
})
