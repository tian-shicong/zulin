<template>
  <div style="padding:8px 24px;height: 100%">
    <div class="breadcrumbWrap">
      <div class="breadcrumb-box">
        <el-breadcrumb separator-class="el-icon-arrow-right">
          <el-breadcrumb-item >客户列表</el-breadcrumb-item>
        </el-breadcrumb>
      </div>

      <search :allData="allData" :dataList="dataList" v-model="dataList" style="float: right"></search>
      <el-button type="primary" plain style="float: right;margin-right: 8px" @click="handleAdd">添加客户</el-button>
    </div>

    <el-table
      height="87%"
      :data="dataList"
      style="width: 100%;"
      :default-sort = "{prop: 'id', order: 'ascending'}"
    >
      <el-table-column
        prop="id"
        label="客户编号"
        sortable
      >
      </el-table-column>
      <el-table-column
        prop="name"
        label="单位名称"
        sortable
      >
      </el-table-column>
      <el-table-column
        prop="person_name"
        label="负责人姓名"
        sortable
      >
      </el-table-column>
      <el-table-column
        prop="phone"
        label="联系电话"
        sortable
      >
      </el-table-column>
      <el-table-column
        prop="status"
        label="状态"
        :formatter="formatter"
        sortable
      >
      </el-table-column>
      <el-table-column
        prop="finished1"
        label="是否完结"
        sortable
      >
      </el-table-column>
      <el-table-column label="操作" width="300">
        <template slot-scope="scope">
          <el-button
            size="mini"
            @click="goFlow(scope.$index, scope.row)">流水</el-button>
          <el-button
            size="mini"
            @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          <el-button
            size="mini"
            :type="scope.row.status==1?'danger':'success'"
            @click="editStatus(scope.$index, scope.row)">{{scope.row.status==1?"下架":"上架"}}</el-button>
        </template>
      </el-table-column>
    </el-table>

    <!--编辑弹窗-->
    <el-dialog title="编辑客户信息" :visible.sync="dialogFormVisible">
      <el-form :model="form" label-width="100px" :rules="rules" ref="form">
        <el-form-item label="单位名称" prop="name">
          <el-input v-model="form.name"  placeholder="请输入单位名称"></el-input>
        </el-form-item>
        <el-form-item label="负责人名称" prop="person_name">
          <el-input v-model="form.person_name" autocomplete="off" placeholder="请输入负责人"></el-input>
        </el-form-item>
        <el-form-item label="联系电话" prop="phone">
          <el-input v-model="form.phone" autocomplete="off" placeholder="请输入电话"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="editCommit(form,'form')">确 定</el-button>
      </div>
    </el-dialog>

    <!--添加弹窗-->
    <el-dialog title="添加客户" :visible.sync="add_dialogFormVisible">
      <el-form :model="addForm" label-width="100px" :rules="rules" ref="addForm">
        <el-form-item label="单位名称" prop="name">
          <el-input v-model="addForm.name"  placeholder="请输入名称"></el-input>
        </el-form-item>
        <el-form-item label="负责人姓名" prop="person_name">
          <el-input v-model="addForm.person_name" autocomplete="off" placeholder="请输入负责人姓名" ></el-input>
        </el-form-item>
        <el-form-item label="联系电话" prop="phone">
          <el-input v-model="addForm.phone" autocomplete="off" placeholder="请输入手机号码或者固话号码，固话格式'0XXX-XXXXXXXX'" type="tel"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="add_dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="addCommit(addForm,'addForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
  export default{
    name: 'order',
    data () {
      return {
        msg: '订单管理',
        dataList:[],
        allData:[],
        form:{},
        addForm:{name:'',person_name:'',phone:''},
        dialogFormVisible:false,
        add_dialogFormVisible:false,
        rules:{
          name:[{
            required: true, message: '请输入名称', trigger: 'blur'
          }],
          person_name:[{
            required: true, message: '请输入负责人姓名', trigger: 'blur'
          }],
          phone:[{
            required: true, message: '请输入联系电话', trigger: 'blur'
          },{
            validator: this.checkPhone, message: "格式不正确!请输入11位手机号码或者固话，固话格式'0XXX-XXXXXXXX'", trigger: 'blur'
          }]
        },

      }
    },
    methods:{
      checkPhone(rule, value, callback){
        var patt = /^[1][3,4,5,7,8][0-9]{9}$/;
        var patt1 = /0\d{2,3}-\d{7,8}/;

        if(patt.test(value) || patt1.test(value)){
          callback()
        }else{
          callback(new Error('请输入正确的手机号码或固话号码'))
        }
      },
      getSite(){
        this.$.ajax({
          method:'GET',
          url:'get_site.php'
        }).then((res)=>{
          if(res.code == 0){
            this.$store.commit('setLoading', 0);
            for(var a = 0; a < res.data.length; a++){
              if(res.data[a].status == 1){
                res.data[a].status1 = "上架"
              }else {
                res.data[a].status1 = "下架"
              }
              res.data[a].finished1 = res.data[a].isfinished==0?'未完结':'已完结'
            }
            this.allData = res.data.concat()
          }
        }).catch((err)=>{
            console.log(err)
        })
      },
      formatter(row, column) {
        if(row.status == 1){
          return "上架"
        }else {
          return "下架"
        }
      },
      handleEdit(index, item){
        this.dialogFormVisible = true;
//        清空表单验证
        if(this.$refs.form){
          this.$refs.form.clearValidate();
        }
        this.form = Object.assign({}, this.form, {
          name: item.name,
          person_name: item.person_name,
          phone: item.phone,
          id: item.id
        })

      },
      editStatus(index,item){
        console.log(item.status);
        this.$.ajax({
          method:"POST",
          url:'remove.php',
          data:this.qs({
            name:'site',
            id:item.id,
            type:item.status==1?0:1
          })
        }).then((res)=>{
          if(res.code == 0){
            this.getSite();
            this.$message({
              message:'操作成功！',
              type:'success'
            })
          }else {
            this.$message({
              message:'操作失败，请重试！',
              type:'error'
            })
          }
        })
      },
      editCommit(form, formName){
        this.$refs[formName].validate((valid) => {
          if (valid) {
            console.log(form);
            this.$store.commit('setLoading', 1);
            this.$.ajax({
              method:"post",
              url:'edit_site.php',
              data:this.qs(this.form)
            }).then((res=>{
              console.log(res);
              if(res.code != 0){
                this.$store.commit('setLoading', 0);
              }
              if(res.code == 0){
                this.dialogFormVisible = false;
                this.getSite()
                this.$message({
                  message:'编辑客户信息成功',
                  type:"success"
                })
              }else if(res.code == -1){
                this.$message({
                  message:'编辑失败，缺少参数！',
                  type:"error"
                })
              }else if(res.code == -3){
                this.$message({
                  message:'编辑失败，找不到客户！',
                  type:"error"
                })
              }else if(res.code == 1){
                this.$message({
                  message:'编辑失败，请重试！',
                  type:"error"
                })
              }else if(res.code == -2){
                this.$message({
                  message:'编辑失败，客户名称已经存在，请修改后重试！',
                  type:"error"
                })
              }
            }))

          } else {
            this.$message({
              message:'数据不合法！',
              type:'warning'
            })
            return false;
          }
        });
      },
      handleAdd(){
        this.add_dialogFormVisible = true;
        if(this.$refs.addForm){
          this.$refs.addForm.clearValidate();
        }
      },
      addCommit(addForm, formName){
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.$store.commit('setLoading', 1);
            console.log(addForm);
            this.$.ajax({
              method:"POST",
              url:'add_site.php',
              data:this.qs(this.addForm)
            }).then((res)=>{
              if(res.code != 0){
                this.$store.commit('setLoading', 0);
              }
              if(res.code == 0){
                this.add_dialogFormVisible = false;
                this.addForm = {name:'',person_name:'',phone:''};
                this.getSite();
                this.$message({
                  message:'添加客户成功',
                  type:'success'
                })
              }else if(res.code == -1){
                this.$message({
                  message:'添加客户失败，缺少参数！',
                  type:'error'
                })
              }else if(res.code == -2){
                this.$message({
                  message:'添加客户失败，单位名称已存在，请更改后重新提交！',
                  type:'error'
                })
              }else if(res.code == 1){
                this.$message({
                  message:'添加设备失败，请稍后重试！',
                  type:'error'
                })
              }
            })
          } else {
            this.$message({
              message: '数据不合法！',
              type: 'warning'
            })
            return false;
          }
        })
      },
      goFlow(index, row){
          console.log(row);
          //跳转流水详情
          this.$router.push({
            name:'flow',
            query: {
              id: row.id
            }
          })
      }
    },
    mounted(){
      this.getSite();
      this.$store.commit('setActiveIndex', "order");
    },
    created(){
      this.$store.commit('setLoading', 1)
    }
  }
</script>
