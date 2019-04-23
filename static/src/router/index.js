import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Device from '@/components/device'
import Order from '@/components/order'
import User from '@/components/user'
import Count from '@/components/count'

Vue.use(Router);

const router = new Router({
  routes: [
    {
      path: '/device',
      name: 'device',
      component: Device
    },
    {
      path: '/order',
      name: 'order',
      component: Order
    },
    {
      path: '/user',
      name: 'user',
      component: User
    },
    {
      path: '/count',
      name: 'count',
      component: Count
    },
    {
      path: '/login',
      name: 'login',
      component: HelloWorld
    },
    {
      path: '*',
      redirect: '/device'
    }
  ]
})

router.beforeEach((to, from, next)=>{
  switch (to.name){
    case "device":
      var siteTitle = '设备管理';
      break;
    case "order":
      var siteTitle = '订单管理';
      break;
    case "user":
      var siteTitle = '用户管理';
      break;
    case "count":
      var siteTitle = "数据统计";
      break;
    case "login":
      var siteTitle = "登录";
      break;
    default:
      var siteTitle = "设备管理";
  }
  document.getElementById('siteTitle').innerText = siteTitle;
  if(to.name == 'login'){
    next()
  }
  var loginSession = sessionStorage.getItem('user');
  if(loginSession){
    next()
  }else {
    next({
      path: '/login'
    })
  }
})

export default router;
