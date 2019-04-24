<template>
  <div class="loginPage">
    <div class="formBox">
      <div style="margin:0 0 16px 0;font-size: 24px">
        {{title}}
      </div>
      <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="80px" class="demo-ruleForm" >
        <el-form-item label="用户名" prop="name">
          <el-input v-model="ruleForm.name"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="ruleForm.password" type="password"></el-input>
        </el-form-item>
        <div>
          <el-button type="primary" @click="submitForm('ruleForm')">登录</el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
//  import axios from 'axios';
//  import $ from './util.js';
export default {
  name: 'HelloWorld',
  computed:{
    title(){
      return this.$store.state.title;
    }
  },
  data () {
    return {
      ruleForm:{
         name:'',
         password:''
      },
      rules:{
          name:{
              required:true,message:"请输入用户名", trigger:'change'
          },
          password:{
            required:true,message:"请输入密码", trigger:'change'
          }
      }
    }
  },
  methods:{
      login(){
        this.$.ajax({
          method:'POST',
          url:'login.php',
          data:this.qs(this.ruleForm)
        }).then((res)=>{
          console.log(res);
          if(res.code==0){
            console.log(res.data)
            this.$store.commit('setUserSession', {...this.ruleForm,role:res.data.type});
            if(sessionStorage.getItem('path')){
              this.$router.push({path: sessionStorage.getItem('path')});
//              this.$router.push({path: '/device'});
            }else {
              this.$router.push({path: '/device'});
            }
//            this.$router.push({path: '/device'});
            this.$message({
              message:'登录成功!',
              type:'success'
            })
          }else if(res.code == -1){
            this.$message({
              message: '缺少数据！',
              type: 'error'
            });
          }else if(res.code == -3){
            this.$message({
              message:'用户名不存在!',
              type:'error'
            })
          }else if(res.code == -2){
            this.$message({
              message:'密码错误,请重新输入!',
              type:'error'
            })
          }
        })
      },
    submitForm:function (formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
//          alert('submit!');
          this.login();
        } else {
          console.log('error submit!!');
          this.$message({
            message:'请输入用户名密码！',
            type:'warning'
          })
          return false;
        }
      });
      console.log(this.ruleForm)
    }
  },
  created(){
      if(sessionStorage.getItem('user')){
        this.ruleForm = this.$store.state.user;
        console.log(this.ruleForm)
        if(this.ruleForm && !!this.ruleForm.name){
          this.login()
        }
      }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .loginPage{
    position: fixed;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    background: #FFF;
  }
  .formBox{
    width: 400px;
    margin:200px auto;
    border: 1px solid #666;
    padding:20px;
    border-radius: 4px;
    box-shadow: 0 0 12px 2px rgba(0,0,0,0.4);
  }
  .el-form-item:last-child>.el-form-item__content{
    margin-left: 0!important;
  }
</style>
