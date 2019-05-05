<template>
  <div id="app">
    <!--loading-->
    <transition name="fade">
      <loading v-if="isLoading"></loading>
    </transition>
    <!--topBar-->
    <div class="topBar">
      <div style="float: left;line-height: 50px;font-size: 22px">
        <span style='display: inline-block;height: 45px;vertical-align: middle;font-size: 0'>
          <img src="./assets/logo.png" alt="" style="height: 100%">
        </span>
        <span>{{title}}</span>
      </div>
      <div style="float: right;margin-right: 80px;line-height: 50px;display: flex;justify-items: center" v-if="user">
        <span style="font-size: 24px;">
          <font-awesome-icon :icon="['fas', 'user-circle']"></font-awesome-icon>
        </span>
        <span style="cursor: pointer;margin-left: 6px" class="userText" @click="goEdit(user.id)">
          {{user.name}}
        </span>
        <span style="cursor: pointer;margin-left: 6px;font-size: 13px" class="userText" @click="logout">
          退出
        </span>
      </div>
    </div>
    <!--菜单-->
    <div class="menuBlock" v-if="user && user.role == 1">
      <!--<el-button @click="collapseSwitch">{{isCollapse?'展开':'收起'}}</el-button>-->
      <el-menu :default-active="activeIndex" class="el-menu-vertical-demo" mode="vertical" @select="handleSelect" :router="true" :collapse="isCollapse">
        <el-menu-item index="device">
          <i class="el-icon-menu"></i>
          <span slot="title">设备管理</span>
        </el-menu-item>
        <el-menu-item index="order">
          <i class="el-icon-tickets" ></i>
          <span slot="title">订单管理</span>
        </el-menu-item>
        <el-menu-item index="count">
          <i class="el-icon-user"><font-awesome-icon :icon="['fas', 'chart-line']"></font-awesome-icon></i>
          <span slot="title">数据统计</span>
        </el-menu-item>
        <el-menu-item index="user">
          <i class="el-icon-user"><font-awesome-icon :icon="['fas', 'user']"></font-awesome-icon></i>
          <span slot="title">用户管理</span>
        </el-menu-item>
      </el-menu>
      <!--<div class="line"></div>-->
      <div  class="collapseSwitch" @click="collapseSwitch">
        <el-tooltip effect="dark" content="点击折叠菜单" placement="top"  v-show="!isCollapse">
          <span class="el-icon-d-arrow-left"></span>
        </el-tooltip>
        <el-tooltip effect="dark" content="点击展开菜单" placement="top" v-show="isCollapse">
          <span class="el-icon-d-arrow-right" ></span>
        </el-tooltip>
      </div>
    </div>

    <div class="contentPage" :class="(user && user.role)==0?'fullPage':(!isCollapse ? '' : 'contentPageBig')">
      <router-view v-if="viewShow"></router-view>
    </div>

  </div>
</template>

<script>

export default {
  name: 'App',
  computed:{
    title(){
      return this.$store.state.title;
    },
    user(){
      return this.$store.state.user;
    },
    activeIndex(){
      return this.$store.state.activeIndex;
    },
    isLoading(){
        return this.$store.state.isLoading;
    },
    viewShow(){
        return this.$store.state.viewShow;
    }
  },
  data() {
    return {
      isCollapse:false
    };
  },
  methods: {
    handleSelect(key, keyPath) {
//      console.log(key, keyPath);
    },
    collapseSwitch(){
        this.isCollapse = !this.isCollapse
    },
    logout(){
        this.$store.commit('removeUserSession');
        this.$router.push('/login')
    },
    goEdit(id){
      this.$router.push('./user')
      this.$store.commit('setEditUser', id)
    }
  },
  created:function () {
//      this.$store.commit('setLoading', 0);
      this.$store.commit('setActiveIndex', this.$route.name);
  },
  mounted:function () {
    var userSession = sessionStorage.getItem('user');
    if(userSession){
      this.$store.commit('setUserSession', JSON.parse(userSession))
    }
  },
  watch:{
    viewShow(){
        if(this.viewShow == false){
            var $this = this
            setTimeout(function () {
              $this.$store.commit('reload', true)
            },200)
        }
    }
  }
}
</script>

<style>
  body,html{
    margin: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: #efefef;
  }
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 0;
  height: 100%;
}
.menuBlock{
  position: fixed;
  left: 0;
  top:51px;
  height: 100%;
}
  .el-menu-vertical-demo{
    height: 100%;
  }
.el-menu-vertical-demo:not(.el-menu--collapse) {
  width: 200px;
  min-height: 400px;
}
  .topBar{
    background: #fff;
    height: 50px;
    position: fixed;
    top:0;
    width: 100%;
    padding:0 18px;
    border-bottom: 1px solid rgba(0,0,0, 0.4);
  }
  .collapseSwitch{
    position: absolute;
    right: -0px;
    bottom:68px;
    cursor: pointer;
    font-size: 20px;
    width: 20px;
    height: 20px;
    opacity: 0;
  }
  .menuBlock:hover .collapseSwitch{
    opacity: 1;
    right: -20px;
  }
  .contentPage{
    background: #efefef;
    width: calc(100% - 200px);
    height: calc(100% - 50px);
    margin-top:50px;
    margin-left:200px;
    transition: all 0.3s;
  }
  .contentPageBig{
    width: calc(100% - 65px);
    margin-left:65px;
  }
  .fullPage{
    width: 100%;
    margin-left:0;
  }
  .userText:hover{
    color: #409eff;
  }
  .breadcrumbWrap{
    margin-bottom: 12px;
    height: 40px;
  }
  .fade-enter,
  .fade-leave-active {
    opacity: 0;
  }
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.2s;
  }
  .breadcrumb-box{
    height: 40px;
    float: left;
    display: flex;
    align-items: center;
  }
  .el-breadcrumb{
    font-size: 16px;
  }
  .el-table .success-row{
    background: #e6effb;
  }
  .el-table .warning-row {
    background: #fdf5e6;
  }
</style>
