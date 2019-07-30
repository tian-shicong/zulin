
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
import Print from './print.js';

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
Vue.use(Vuex);
Vue.use(Print);

new Vue({
  el:'#app',
  router,
  store,
  render: h =>{
    return h(app)
  },
  methods:{
    backup(){
      this.$.ajax({
        url:'beifen.php',
        method:'GET'
      }).then((res)=>{
        console.log(res);
        if(res == 0){
          this.$.ajax({
            url:'http://myrontian.000webhostapp.com/zulin/api/download_backup.php',
            method:'GET'
          }).then((res)=>{
            console.log(res);
          })
        }
      })
    }
  },
  created(){
    this.$store.commit('setLoading', 1);
    //备份数据库
    this.$.ajax({
      url:'isUpdate.php',
      method:'GET'
    }).then((res)=>{
      var currTime = (new Date()).valueOf();
      console.log(res);
      console.log(currTime - res * 1000);
      if(currTime - res * 1000 > 3600 * 12 * 1000){
        this.backup()
      }
    })
  }
});
