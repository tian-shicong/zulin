<template>
  <div style="padding:8px 24px;height: 100%">
    <div class="breadcrumbWrap">
      <div class="breadcrumb-box">
        <el-breadcrumb separator-class="el-icon-arrow-right">
          <el-breadcrumb-item :to="{ path: '/order' }" >{{site.name}}</el-breadcrumb-item>
          <el-breadcrumb-item >流水列表</el-breadcrumb-item>
        </el-breadcrumb>
      </div>

      <!--<search :allData="allData" :dataList="dataList" v-model="dataList" style="float: right"></search>-->
      <!--<el-button type="primary" plain style="float: right;margin-right: 8px" @click="handleAdd">新增流水</el-button>-->
    </div>
  </div>
</template>
<script>
  export default{
      data(){
          return{
            site:{}
          }
      },
    methods:{
      //查询site
      getSite(id){
          this.$.ajax({
            url:'get_site.php?id=' + id
          }).then((res)=>{
              console.log(res)
              if(res.code == 0 && res.data.length > 0){
                  this.site = res.data[0]
              }else {
                  this.$message({
                    message:'获取客户信息失败！',
                    type:'error'
                  })
              }
          })
      }
    },
    created(){
      this.$store.commit('setLoading', 0);
    },
    mounted(){
      this.$store.commit('setActiveIndex', "order");
      if(this.$route.query.id){
        this.getSite(this.$route.query.id)
      }else {
        this.$message({
          message:'缺少客户编号！',
          type:'error'
        })
      }
    }
  }
</script>
