<template>
  <div style="padding:8px 24px;height: 100%">
    <div class="breadcrumbWrap">
      <div class="breadcrumb-box">
        <el-breadcrumb separator-class="el-icon-arrow-right">
          <el-breadcrumb-item :to="{ path: '/device' }">设备管理</el-breadcrumb-item>
        </el-breadcrumb>
      </div>

      <search :allData="allData" :dataList="dataList" v-model="dataList" style="float: right"></search>
      <el-button type="primary" plain style="float: right;margin-right: 8px" @click="handleAdd">添加设备</el-button>
    </div>

    <el-table
      height="87%"
      :data="dataList"
      style="width: 100%;"
      :default-sort = "{prop: 'id', order: 'ascending'}"
    >
      <el-table-column
        prop="id"
        label="设备编号"
        sortable
      >
      </el-table-column>
      <el-table-column
        prop="name"
        label="名称"
        sortable
        >
      </el-table-column>
      <el-table-column
        prop="price"
        label="出租价格"
        sortable
        :formatter="formatterPrice"
        >
      </el-table-column>
      <el-table-column
        prop="loss_price"
        label="赔偿价格"
        sortable
        :formatter="formatterPrice"
      >
      </el-table-column>
      <el-table-column
        prop="count"
        label="总数"
        sortable
        >
      </el-table-column>
      <el-table-column
        prop="last"
        label="库存剩余"
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
      <el-table-column label="操作" width="150px">
        <template slot-scope="scope">
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
    <el-dialog title="编辑设备" :visible.sync="dialogFormVisible">
      <el-form :model="form" label-width="100px" :rules="rules" ref="form">
        <el-form-item label="设备名称" prop="name">
          <el-input v-model="form.name"  placeholder="请输入名称"></el-input>
        </el-form-item>
        <el-form-item label="出租价格" prop="price">
          <el-input v-model="form.price" autocomplete="off" placeholder="请输入出租价格" type="number"></el-input>
        </el-form-item>
        <el-form-item label="赔偿价格" prop="loss_price">
          <el-input v-model="form.loss_price" autocomplete="off" placeholder="请输入赔偿价格" type="number"></el-input>
        </el-form-item>
        <el-form-item label="单位" prop="unit">
          <el-input v-model="form.unit" autocomplete="off" placeholder="请输入单位"></el-input>
        </el-form-item>
        <el-form-item label="总数" prop="count">
          <el-input v-model="form.count" autocomplete="off" placeholder="请输入总数" type="number"></el-input>
        </el-form-item>
        <el-form-item label="库存剩余" prop="last">
          <el-input v-model="form.last" autocomplete="off" placeholder="请输入库存" type="number"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="editCommit(form,'form')">确 定</el-button>
      </div>
    </el-dialog>

    <!--添加弹窗-->
    <el-dialog title="添加设备" :visible.sync="add_dialogFormVisible">
      <el-form :model="addForm" label-width="100px" :rules="rules" ref="addForm">
        <el-form-item label="设备名称" prop="name">
          <el-input v-model="addForm.name"  placeholder="请输入名称"></el-input>
        </el-form-item>
        <el-form-item label="价格" prop="price">
          <el-input v-model="addForm.price" autocomplete="off" placeholder="请输入价格" type="number"></el-input>
        </el-form-item>
        <el-form-item label="赔偿价格" prop="loss_price">
          <el-input v-model="addForm.loss_price" autocomplete="off" placeholder="请输入赔偿价格" type="number"></el-input>
        </el-form-item>
        <el-form-item label="单位" prop="unit">
          <el-input v-model="addForm.unit" autocomplete="off" placeholder="请输入单位，如：个，根，条，台"></el-input>
        </el-form-item>
        <el-form-item label="总数" prop="count">
          <el-input v-model="addForm.count" autocomplete="off" placeholder="请输入总数" type="number"></el-input>
        </el-form-item>
        <el-form-item label="库存剩余" prop="last">
          <el-input v-model="addForm.last" autocomplete="off" placeholder="请输入库存" type="number"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="add_dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="addCommit(addForm,'addForm')" :diaable="add_disable">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
  export default{
    name: 'device',
    data () {
      return {
        msg: '设备模块',
        dataList:[],
        allData:[],
        form:{},
        addForm:{name:'',price:'',count:'',last:'',loss_price:'',unit:'个'},
        dialogFormVisible:false,
        add_dialogFormVisible:false,
        rules:{
            name:[{
               required: true, message: '请输入名称', trigger: 'blur'
            }],
            price:[{
              required: true, message: '请输入出租价格', trigger: 'blur'
            }],
            count:[{
              required: true, message: '请输入总数', trigger: 'blur'
            }],
            last:[{
              required: true, message: '请输入库存', trigger: 'blur'
            }],
            loss_price:{
              required: true, message: '请输入赔偿价格', trigger: 'blur'
            },
            unit:{
              required: true, message: '请输入单位，如：个，根，条，台', trigger: 'blur'
            }
        },
        add_disable:false
      }
    },
    methods:{
        getDevice(){
            this.$.ajax({
              method:'GET',
              url:'get_device.php'
            }).then((res)=>{
                if(res.code == 0){
                    this.$store.commit('setLoading', 0)
                    for(var a = 0; a < res.data.length; a++){
                        if(res.data[a].status == 1){
                          res.data[a].status1 = "上架"
                        }else {
                          res.data[a].status1 = "下架"
                        }
                        res.data[a].id = Number(res.data[a].id);
                        res.data[a].count = Number(res.data[a].count);
                        res.data[a].last = Number(res.data[a].last);
                        res.data[a].price = Number(res.data[a].price);
                        res.data[a].loss_price = Number(res.data[a].loss_price);
                    }
                    this.allData = res.data.concat()
                }
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
            if(this.$refs.form){
              this.$refs.form.clearValidate();
            }
            this.form = Object.assign({}, this.form, {
              name: item.name,
              price: item.price,
              count: item.count,
              last: item.last,
              id: item.id,
              loss_price:item.loss_price,
              unit:item.unit
            })

        },
        editStatus(index,item){
            console.log(item.status);
            this.$.ajax({
              method:"POST",
              url:'remove.php',
              data:this.qs({
                name:'device',
                id:item.id,
                type:item.status==1?0:1
              })
            }).then((res)=>{
                if(res.code == 0){
                    this.getDevice();
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
        editCommit(form,formName){
            this.$refs[formName].validate((valid) => {
              if (valid) {
                this.$store.commit('setLoading', 1);
                console.log(form);
                this.$.ajax({
                  method:"post",
                  url:'edit_device.php',
                  data:this.qs(this.form)
                }).then((res=>{
                    console.log(res);
                    if(res.code != 0){
                      this.$store.commit('setLoading', 0);
                    }
                    if(res.code == 0){
                      this.dialogFormVisible = false;
                      this.getDevice()
                      this.$message({
                        message:'编辑设备信息成功',
                        type:"success"
                      })
                    }else if(res.code == -1){
                      this.$message({
                        message:'编辑失败，缺少参数！',
                        type:"error"
                      })
                    }else if(res.code == -2){
                      this.$message({
                        message:'编辑失败，找不到设备！',
                        type:"error"
                      })
                    }else if(res.code == 1){
                      this.$message({
                        message:'编辑失败，请重试！',
                        type:"error"
                      })
                    }else if(res.code == -3){
                      this.$message({
                        message:'编辑失败，设备名称已经存在，请修改后重试！',
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
            this.addForm = {name:'',price:'',count:'',last:'',loss_price:'',unit:'个'};
            if(this.$refs.addForm){
              this.$refs.addForm.clearValidate();
            }
      },
      addCommit(addForm,formName){
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.$store.commit('setLoading', 1);
            console.log(addForm);
            this.add_disable = true;
            this.$.ajax({
              method:"POST",
              url:'add_device.php',
              data:this.qs(this.addForm)
            }).then((res)=>{
                this.add_disable = false;
                if(res.code != 0){
                  this.$store.commit('setLoading', 0);
                }
                if(res.code == 0){
                    this.add_dialogFormVisible = false;
                    this.addForm = {name:'',price:'',count:'',last:''};
                    this.getDevice();
                    this.$message({
                      message:'添加设备成功',
                      type:'success'
                    })
                }else if(res.code == -1){
                  this.$message({
                    message:'添加设备失败，缺少参数！',
                    type:'error'
                  })
                }else if(res.code == -2){
                  this.$message({
                    message:'添加设备失败，设备名已存在，请更改后重新提交！',
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
      formatterPrice(row, column){
        if(column.property == 'loss_price'){
            if(row[column.property]){
              return row[column.property] + '(元/' + (row.unit?(row.unit+')'):'个)')
            }
            return ''
        }
        return row[column.property] + '(元/天' + (row.unit?('/'+row.unit+')'):'/个)')
      }
    },
    mounted(){
      this.getDevice();
      console.log(this.$route)
      this.$store.commit('setActiveIndex', this.$route.name);
      console.log(this.$store.state.activeIndex)
    },
    created(){
      this.$store.commit('setLoading', 1)
    }
  }
</script>
