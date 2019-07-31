<template>
  <div style="padding:8px 24px;height: 100%">
    <div class="breadcrumbWrap">
      <div class="breadcrumb-box">
        <el-breadcrumb separator-class="el-icon-arrow-right">
          <el-breadcrumb-item>用户列表</el-breadcrumb-item>
        </el-breadcrumb>
      </div>

      <search :allData="allData" :dataList="dataList" v-model="dataList" style="float: right"></search>
      <el-button type="primary" plain style="float: right;margin-right: 8px" @click="handleAdd">添加用户</el-button>
    </div>

    <el-table
      height="87%"
      :data="dataList"
      style="width: 100%;"
      :default-sort = "{prop: 'id', order: 'ascending'}"
    >
      <el-table-column
        prop="id"
        label="用户编号"
        sortable
      >
      </el-table-column>
      <el-table-column
        prop="name"
        label="用户名"
        sortable
      >
      </el-table-column>
      <el-table-column
        prop="role"
        label="角色"
        sortable
      >
      </el-table-column>
      <el-table-column
        prop="site"
        label="所属单位"
        sortable
        :formatter="formatter"
      >
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button
            size="mini"
            @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          <el-button
            size="mini"
            type="danger"
            @click="handleRemove(scope.$index, scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>

    <!--编辑弹窗-->
    <el-dialog title="编辑用户" :visible.sync="dialogFormVisible" style="text-align: left" width="30%">
      <el-form :model="form" label-width="100px" :rules="rules" ref="form" label-position="left">
        <el-form-item label="用户名" prop="name">
          <el-input v-model="form.name"  placeholder="请输入用户名"></el-input>
        </el-form-item>
        <el-form-item label="角色" prop="type">
          <el-select v-model="form.type" placeholder="请选择角色" style="width: 100%" @change="changeType">
            <el-option label="管理员" value="1"></el-option>
            <el-option label="普通用户" value="0"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="所属单位" prop="site.id" v-if="form.type == 0" :rules="{required: true, message: '请选择单位', trigger: 'blur'}">
          <el-select v-model="form.site.id" placeholder="请选择单位" style="width: 100%">
            <el-option :label="item.name" :value="item.id" :key="item.id" v-for="(item, index) in siteList"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="设置新密码" prop="setPassword">
          <el-switch v-model="form.setPassword"></el-switch>
        </el-form-item>
        <el-form-item label="新密码" prop="password" v-if="form.setPassword" :rules="{required: true, message: '请输入密码', trigger: 'blur'}">
          <el-input v-model="form.password"  placeholder="请输入密码"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="editCommit(form,'form')">确 定</el-button>
      </div>
    </el-dialog>

    <!--添加弹窗-->
    <el-dialog title="添加用户" :visible.sync="add_dialogFormVisible">
      <el-form :model="addForm" label-width="100px" :rules="rules" ref="addForm">
        <el-form-item label="用户名" prop="name">
          <el-input v-model="addForm.name"  placeholder="请输入用户名"></el-input>
        </el-form-item>
        <el-form-item label="角色" prop="type">
          <el-select v-model="addForm.type" placeholder="请选择角色" style="width: 100%" @change="changeType">
            <el-option label="管理员" value="1"></el-option>
            <el-option label="普通用户" value="0"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="所属单位" prop="site.id" v-if="addForm.type == 0" :rules="{required: true, message: '请选择单位', trigger: 'blur'}">
          <el-select v-model="addForm.site.id" placeholder="请选择单位" style="width: 100%">
            <el-option :label="item.name" :value="item.id" :key="item.id" v-for="(item, index) in siteList"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="密码" prop="password" :rules="{required: true, message: '请输入密码', trigger: 'blur'}">
          <el-input v-model="addForm.password"  placeholder="请输入密码" type="password"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="add_dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="addCommit(addForm,'addForm')" :disable="add_disable">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
  export default{
    name: 'user',
    computed:{
      isEditUser(){
          return this.$store.state.isEditUser;
      }
    },
    data () {
      return {
        msg: '用户管理',
        dataList: [],
        allData: [],
        form: {},
        addForm: {name: '', type: "1", site: {}, password: ''},
        dialogFormVisible: false,
        add_dialogFormVisible: false,
        rules: {
          name: {required: true, message: '请输入名称', trigger: 'blur'},
          type: {required: true, message: '请选择角色', trigger: 'blur'},
        },
        siteList: [],
        tempUser: '',
        add_disable:false
      }
    },
    methods: {
      getUser(){
        this.$.ajax({
          method: 'GET',
          url: 'get_user.php'
        }).then((res) => {
          if (res.code == 0) {
            this.$store.commit('setLoading', 0);
            for (var a = 0; a < res.data.length; a++) {
              if (res.data[a].type == 1) {
                res.data[a].role = "管理员"
              } else {
                res.data[a].role = "普通用户"
              }
            }
            this.allData = res.data.concat();
            if(this.isEditUser){
                this.openEdit(this.isEditUser);
            }
          }
        })
      },
      handleAdd(){
        this.add_dialogFormVisible = true;
        if(this.$refs.addForm){
          this.$refs.addForm.clearValidate();
        }
      },
      handleEdit(index, item){
        this.dialogFormVisible = true;
        if(this.$refs.form){
          this.$refs.form.clearValidate();
        }
        this.form = Object.assign({}, this.form, {
          name: item.name,
          type: item.type,
          password: '',
          setPassword: false,
          site: item.site,
          id: item.id
        });
        if (!this.form.site) {
          this.form.site = {}
        }
        this.tempUser = {...item};
      },
      editCommit(form, formName){
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.$store.commit('setLoading', 1);
//              console.log(form);
            var newData = {
              id: form.id,
              type: form.type,
              site: form.site
            }
            if (form.type == 0) {
              newData.site_id = form.site.id
            }
            if (form.setPassword) {
              newData.password = form.password;
            }
            if (this.tempUser.name != form.name) {
              newData.name = form.name;
            }
            this.$.ajax({
              method: "POST",
              url: 'edit_user.php',
              data: this.qs(newData)
            }).then((res) => {
              console.log(res);
              if(res.code != 0){
                this.$store.commit('setLoading', 0);
              }
              if (res.code == 0) {
                this.dialogFormVisible = false;
                this.getUser();
                this.$message({
                  message: '编辑成功！',
                  type: 'success'
                })
              } else if (res.code == 1) {
                this.$message({
                  message: '编辑失败，请稍后重试！',
                  type: 'error'
                })
              } else if (res.code == -1) {
                this.$message({
                  message: '编辑失败，缺少参数！',
                  type: 'error'
                })
              } else if (res.code == -2) {
                this.$message({
                  message: '编辑失败，用户不存在！',
                  type: 'error'
                })
              } else if (res.code == -3) {
                this.$message({
                  message: '编辑失败，用户名已存在，请修改后重新提交！',
                  type: 'error'
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
      handleRemove(index, row){
        this.$confirm('确定要删除' + row.name + '吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
            //name为表名
          this.$store.commit('setLoading', 1);
          this.$.ajax({
            method: "POST",
            url: 'remove.php',
            data: this.qs({name: 'zulin_user', type: 0, id: row.id})
          }).then((res) => {
            if (res.code == 0) {
              this.getUser();
              this.$message({
                message: '删除成功！',
                type: 'success'
              })
            }
          })
        }).catch(() => {
        });
      },
      formatter(row, column) {
        if (row.site && row.site.status == 1) {
          return row.site.name
        } else if(row.site && row.site.status == 0){
          return row.site.name + "(已失效)"
        } else {
          return "无"
        }
      },
      getSite(){
        this.$.ajax({
          url: 'get_site.php?status=1',
        }).then((res) => {
          if (res.code == 0) {
            this.siteList = res.data;
          }
        })
      },
      changeType(val){
        console.log(val)
        if (val == 0) {

        }
      },
      addCommit(form, formName){
        this.$refs[formName].validate((valid) => {
          if (valid) {
            //              console.log(form);
            this.$store.commit('setLoading', 1);
            var newData = {
              type: form.type,
              name: form.name,
              password: form.password
            }
            if (form.type == 0) {
              newData.site_id = form.site.id
            }
            this.add_disable = true;
            this.$.ajax({
              method: "POST",
              url: 'add_user.php',
              data: this.qs(newData)
            }).then((res) => {
              this.add_disable = false;
              if(res.code != 0){
                this.$store.commit('setLoading', 0);
              }
              if(res.code == 0){
                  this.getUser();
                  this.add_dialogFormVisible = false;
                  this.addForm = {name: '', type: "1", site: {}, password: ''};
                  this.$message({
                    message:'添加用户成功！',
                    type:'success'
                  })
              }else if(res.code == -1){
                  this.$message({
                    message:'添加用户失败，缺少参数！',
                    type:'error'
                  })
              }else if(res.code == 1){
                this.$message({
                  message:'添加用户失败，请稍后重试！',
                  type:'error'
                })
              }else if(res.code == -2){
                this.$message({
                  message:'添加用户失败，用户名已存在！',
                  type:'error'
                })
              }

            })
          } else {
            this.$message({
              message: '数据不合法',
              type: 'error'
            })
          }
        })
      },
      openEdit(id){
          this.dialogFormVisible = true;
          if(this.$refs.form){
            this.$refs.form.clearValidate();
          }
          for(var a = 0; a < this.allData.length; a++){
              if(id == this.allData[a].id){
                this.handleEdit(0, this.allData[a]);
              }
          }
      }
    },
    watch:{
      isEditUser(){
          if(this.isEditUser){
              if(this.isEditUser){
                this.openEdit(this.isEditUser);
              }
              this.$store.commit('setEditUser',false)
          }
      },
      dialogFormVisible(){
          if(this.dialogFormVisible == false){
            this.$store.commit('setEditUser',false)
          }
      }
    },
    mounted(){
      this.getUser();
      this.getSite();
      this.$store.commit('setActiveIndex', this.$route.name);
    },
    created(){
      this.$store.commit('setLoading', 1);
    }
  }
</script>
