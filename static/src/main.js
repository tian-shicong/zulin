
import Vue from 'vue';
import app from './App'
import search from '@/components/search'
import loading from '@/components/loading'
import router from './router'
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import Vuex from 'vuex';
import store from './store';
import axios from 'axios';
import $ from './util.js';
Vue.prototype.$axios = axios;
Vue.prototype.$ = $;
import qs from 'qs';
Vue.prototype.qs = qs.stringify;


import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
  faUser, faChartLine, faAmericanSignLanguageInterpreting,faUserCircle,
} from '@fortawesome/free-solid-svg-icons';

library.add(
  faUser,
  faChartLine,
  faAmericanSignLanguageInterpreting,
  faUserCircle,
);

Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.component('search',search)
Vue.component('loading',loading)
Vue.use(ElementUI);
Vue.use(Vuex)

new Vue({
  el:'#app',
  router,
  store,
  render: h =>{
    return h(app)
  }
});
