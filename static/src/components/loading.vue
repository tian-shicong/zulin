<template>
  <div class="loading">
    <i class="el-icon-loading"></i>
  </div>
</template>

<script>
  export default{
      name:'loading'
  }
</script>

<style scoped>
  .loading{
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    /*background-color: rgba(0,0,0,0.2);*/
    z-index: 999;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .loading>i{
    font-size: 36px;
    color:#000;
    font-weight: 600;
  }
</style>
