<template>
  <div style="padding:8px 24px;height: 100%">
    <div class="breadcrumbWrap">
      <div class="breadcrumb-box">
        <el-breadcrumb separator-class="el-icon-arrow-right">
          <el-breadcrumb-item :to="{ path: '/order' }" >{{site.name}}</el-breadcrumb-item>
          <el-breadcrumb-item >订单列表</el-breadcrumb-item>
        </el-breadcrumb>
      </div>

      <search :allData="allData" :dataList="dataList" v-model="dataList" style="float: right"></search>
      <el-button type="primary" plain style="float: right;margin-right: 8px" @click="handleAdd" v-if="user && user.role == 1">新增订单</el-button>
      <el-button type="primary" plain style="float: right;margin-right: 8px" @click="printFlow" v-if="user && user.role == 1">导出为Excel</el-button>
    </div>
    <el-table
      height="87%"
      :data="dataList"
      style="width: 100%;"
      :default-sort = "{prop: 'id', order: 'ascending'}"
      show-summary
      ref="flow_table"
      id="flow_table"
    >
      <table v-if="printing">
        <tr>
          <th colspan="7" style="text-align: center;font-size: 18px;font-weight: 600">{{site.name}} 订单列表</th>
        </tr>
      </table>
      <el-table-column
        prop="id"
        label="订单编号"
        sortable
        align="center"
        :sort-method="sortById"
      >
        <template slot-scope="scope">
          <el-popover trigger="hover" placement="right" popper-class="" v-if="!printing">
            <p>设备名称: {{ scope.row.device.name}}</p>
            <p>订单类型: {{ scope.row.type1}}</p>
            <div v-if="scope.row.type==1">
              <p>起始日期: {{ scope.row.begin_date1}}</p>
              <p>是否结清: {{ scope.row.settle1}}</p>
              <p>待结算数量: {{ scope.row.last}}</p>
            </div>
            <div v-if="scope.row.type==0">
              <p>结束日期: {{ scope.row.end_date1}}</p>
              <p>总金额: {{ scope.row.money}}</p>
              <p>优惠金额: {{ scope.row.discount}}</p>
              <p>应收金额: {{ scope.row.realMoney}}</p>
            </div>
            <p>生成日期: {{ scope.row.create_date1}}</p>

            <div slot="reference" class="name-wrapper" style="display: inline-block;">
              <el-tag size="medium">{{ scope.row.id }}</el-tag>
            </div>
          </el-popover>
          <span v-if="printing">{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column
        prop="device.name"
        label="设备名称"
        sortable
      >
      </el-table-column>
      <el-table-column
        prop="number"
        label="数量"
        sortable
      >
      </el-table-column>
      <el-table-column
        prop="type1"
        label="订单类型"
        sortable
        :filters="[{text: '出租', value: '出租'}, {text: '归还', value: '归还'}]"
        :filter-method="filterHandler"
      >
        <!--:filters="[{text: '出租', value: '出租'}, {text: '归还', value: '归还'}]"
        :filter-method="filterHandler"-->
      </el-table-column>
      <!--<el-table-column
        prop="create_date1"
        label="生成日期"
        sortable
        width="100px"
      >
      </el-table-column>-->

      <el-table-column
        prop="begin_date1"
        label="开始日期"
        sortable
        width="130px"
      >
      </el-table-column>
      <el-table-column
        prop="end_date1"
        label="结束日期"
        sortable
        width="100px"
      >
      </el-table-column>

      <!--<el-table-column
        prop="settle1"
        label="是否结清"
      ></el-table-column>
      <el-table-column
        prop="last"
        label="待结算"
        sortable
      ></el-table-column>-->
      <!--<el-table-column
        prop="money"
        label="总金额"
        sortable
        :formatter="formatterMoney"
      >
      </el-table-column>
      <el-table-column
        prop="discount"
        label="优惠金额"
        :formatter="formatterDiscount"
        sortable
      >
      </el-table-column>-->
      <el-table-column
        label="应收金额"
        prop="realMoney"
      >
        <!--<template slot-scope="scope">-->
          <!--<span v-if="scope.row.type == 0">{{scope.row.money - scope.row.discount}}</span>-->
          <!--<span v-if="scope.row.type == 1">-&#45;&#45;</span>-->
        <!--</template>-->
      </el-table-column>
      <el-table-column
        label="是否支付"
        v-if="!printing"
      >
        <template slot-scope="scope">
          <div v-if="user && user.role == 1">
            <el-tooltip content="点击切换支付状态" v-if="scope.row.type == 0" effect="dark" placement="top" style="cursor: pointer">
              <el-tag type="success" v-if="scope.row.ispaid==1" @click="togglePaid(scope.row)">已支付</el-tag>
              <el-tag type="danger" v-if="scope.row.ispaid==0" @click="togglePaid(scope.row)">未支付</el-tag>
            </el-tooltip>
            <span v-if="scope.row.type == 1">---</span>
          </div>
          <div v-if="user && user.role != 1">
            <span v-if="scope.row.type == 0">
              <el-tag type="success" v-if="scope.row.ispaid==1">已支付</el-tag>
              <el-tag type="danger" v-if="scope.row.ispaid==0">未支付</el-tag>
            </span>
            <span v-if="scope.row.type == 1">---</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="操作" width="150px" v-if="!printing">
        <template slot-scope="scope">
          <el-button
            size="mini"
            :disabled="scope.row.type == 1"
            @click="showDetail(scope.$index, scope.row)">明细</el-button>
          <el-button
            size="mini"
            type="danger"
            :disabled="scope.row.settle == 1 || (scope.row.settle_last != scope.row.number && scope.row.number  >= 0) || (scope.row.type == 0 && scope.row.ispaid == 1)"
            @click="handleRemove(scope.$index, scope.row)" v-if="user && user.role == 1">删除</el-button>
        </template>
      </el-table-column>
    </el-table>

    <!--添加订单-->
    <el-dialog :title="site.name + ' 新增订单'" :visible.sync="add_dialogFormVisible" style="text-align: left" width="45%">
      <el-form :model="addForm" label-width="100px" :rules="rules" ref="addForm">
        <el-form-item label="设备名称" prop="device_id">
          <el-select v-model="addForm.device_id" placeholder="请选择设备" style="width: 100%" @change="findDevice">
            <el-option :label="item.name" :value="item.id" :key="item.id" v-for="(item, index) in deviceList"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="数量" prop="number" v-if="addForm.device_id">
          <el-input v-model="addForm.number"  placeholder="请输入数量" type="number" @change="computedMoney"></el-input>
        </el-form-item>

        <el-form-item label="类型" prop="type">
          <el-radio v-model="addForm.type" label="1" @change="computedMoney">出租</el-radio>
          <el-radio v-model="addForm.type" label="0" @change="computedMoney">归还</el-radio>
        </el-form-item>

        <el-form-item label="起始日期" prop="begin_date" v-if="addForm.type == 1">
          <el-date-picker
            v-model="addForm.begin_date"
            format="yyyy / MM / dd"
            type="date"
            placeholder="选择日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="结束日期" prop="begin_date" v-if="addForm.type == 0">
          <el-date-picker
            v-model="addForm.end_date"
            format="yyyy / MM / dd"
            type="date"
            @change="computedMoney"
            placeholder="选择日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="总金额" v-if="addForm.type == 0">
          <span>{{addForm.money}}</span>
        </el-form-item>
        <el-form-item label="优惠金额" v-if="addForm.type == 0">
          <el-input v-model="addForm.discount" placeholder="请输入优惠金额" type="number"></el-input>
        </el-form-item>
        <el-form-item label="应收"v-if="addForm.type == 0 && addForm.money">
          <span>{{(addForm.money - addForm.discount).toFixed(2)}}</span>
        </el-form-item>
        <el-form-item label="明细" v-if="addForm.type == 0 && addForm.money">
          <template slot-scope="scope">
            <el-table
              :data="flowDetail"
              style="width: 100%">
              <el-table-column
                label="订单编号"
                prop="id"
              >
              </el-table-column>
              <el-table-column
                label="起始日期"
                prop="begin_date"
                width="100px"
              >
              </el-table-column>
              <el-table-column
                label="天数"
                prop="days"
              >
              </el-table-column>
              <el-table-column
                label="数量"
                prop="number"
              >
              </el-table-column>
              <el-table-column
                label="价格"
                prop="price"
              >
              </el-table-column>
              <el-table-column
                label="金额"
                prop="money"
              >
              </el-table-column>
            </el-table>
          </template>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="add_dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="addCommit(addForm,'addForm')" :disable="button_disable">确 定</el-button>
      </div>
    </el-dialog>

    <!--明细窗口-->
    <el-dialog :title="'收费明细--订单编号' + check_flow_id + '(' + currFlow.end_date1 + ')'" :visible.sync="detail_dialogFormVisible" id="detailPage">
      <div  @click="myPrint" class="print" style="display: inline-block;position: absolute;top: 20px;left: 12px">打印</div>
      <div ref="printTable" id="printContent">
        <div class="detailTop">
          <span>总金额：{{currFlow.money}}</span>   <span>优惠：{{currFlow.discount}}</span>   <span>应收：{{Number(currFlow.money) - Number(currFlow.discount)}}</span>
        </div>
        <div v-if="printing" style="text-align: center">{{'收费明细--订单编号' + check_flow_id + '(' + currFlow.end_date1 + ')'}}</div>
        <el-table :data="check_flow_detail" show-summary :summary-method="getSummaries" >
          <el-table-column property="id" label="订单编号"></el-table-column>
          <el-table-column property="begin_date" label="起始日期"></el-table-column>
          <el-table-column property="days" label="天数"></el-table-column>
          <el-table-column property="price" label="价格"></el-table-column>
          <el-table-column property="number" label="数量"></el-table-column>
          <el-table-column property="money" label="金额"></el-table-column>
        </el-table>
      </div>

    </el-dialog>
  </div>
</template>
<script>
  import FileSaver from 'file-saver'
  import XLSX from 'xlsx'
  export default{
    computed:{
      user(){
        return this.$store.state.user;
      }
    },
      data(){
          return{
            addForm:{type:'1',begin_date:(new Date).valueOf(),discount:0,end_date:(new Date).valueOf()},
            site:{},
            deviceList:[],
            dataList:[],
            allData:[],
            add_dialogFormVisible:false,
            dialogFormVisible:false,
            rules:{
              device_id: {required: true, message: '请选择设备', trigger: 'blur'},
              type: {required: true, message: '请选择订单类型', trigger: 'blur'},
              begin_date: {required: true, message: '请选择起始日期', trigger: 'blur'},
              number: {required: true, message: '请输入数量', trigger: 'blur'},
            },
            currDevice:{},
            flowArr:[],
            countNumber:0,
            flowDetail:[],
            button_disable:true,
            detail_dialogFormVisible:false,
            check_flow_detail:[],
            check_flow_id:'',
            currFlow:'',
            printing:false
          }
      },
    methods:{
      //获取订单
      getFlow(id){
          this.$.ajax({
            url:'get_flow.php?id=' + id
          }).then((res)=>{
              this.$store.commit('setLoading', 0);
              if(res.code == 0){
                  this.allData = res.data;
                  console.log(this.allData);
                  for(var a = 0; a < this.allData.length; a++){
                    this.allData[a].begin_date1 = this.allData[a].type==1?this.formatDate(Number(this.allData[a].begin_date)):'---';
                    this.allData[a].create_date1 = this.formatDate(Number(this.allData[a].create_date),true);
                    this.allData[a].end_date1 = this.allData[a].type==0?this.formatDate(Number(this.allData[a].end_date)):'---';
                    this.allData[a].type1 = this.allData[a].type==1?'出租':'归还'
                    this.allData[a].settle1 = this.allData[a].type==1?(this.allData[a].settle==1?'已结清':'未结清'):'---';
                    this.allData[a].last = this.allData[a].type==1?this.allData[a].settle_last:'---'
                    this.allData[a].realMoney = this.allData[a].type==1?'---':this.allData[a].money - this.allData[a].discount
                    this.allData[a].ispaid1 = this.allData[a].type==1?'---':(this.allData[a].ispaid==1?'已支付':'未支付');
                  }
              }
          })
      },
      //查询site
      getSite(id){
          this.$.ajax({
            url:'get_site.php?id=' + id
          }).then((res)=>{
              console.log(res)
              if(res.code == 0 && res.data.length > 0){
                  this.site = res.data[0];
                  this.getFlow(this.site.id)
              }else {
                  this.$message({
                    message:'获取客户信息失败！',
                    type:'error'
                  })
              }
          })
      },
      //获取设备
      getDevice(){
          this.$.ajax({
            url:'get_device.php?status=1',
          }).then((res)=>{
              if(res.code == 0){
                this.deviceList = res.data
              }else {
                this.$message({
                  message:'获取设备列表失败！',
                  type:'error'
                })
              }

          })
      },
      handleAdd(){
          this.add_dialogFormVisible = true;
          if(this.$refs.addForm){
            this.$refs.addForm.clearValidate();
          }
          this.currDevice = {}
      },
      addCommit(form, formName){
        this.$refs[formName].validate((valid) => {
          if (valid) {
            console.log(form);
            this.$store.commit('setLoading', 1);
            console.log(form.begin_date.valueOf());
            form.create_date = (new Date()).valueOf();
            form.end_date = form.end_date.valueOf();
            form.site_id = this.site.id;
//            return
            if (form.type == 1) {
              console.log(form.number - this.currDevice.last > 0);
              delete form.discount;
              form.begin_date = form.begin_date.valueOf();
              if(form.number - this.currDevice.last > 0){
                  this.$message({
                    message:'添加订单失败，库存不足!',
                    type:'error'
                  });
                this.$store.commit('setLoading', 0);
                return false;
              }
            }else {
              delete form.begin_date;
              form.detail = JSON.stringify(this.flowDetail);
            }
            this.button_disable = false;
            this.$.ajax({
              method: "POST",
              url: 'add_flow.php',
              data: this.qs(form)
            }).then((res) => {
              this.button_disable = true;
              if(res.code == 0){
//                  this.getFlow(this.site.id);
                  //更新客户信息
                  this.$.ajax({
                    method:'POST',
                    url:'edit_site.php',
                    data:this.qs({
                      id:this.site.id,
                      isfinished:0
                    })
                  }).then((res)=>{
                      if(res.code != 0){
                          this.$message({
                            message:'更新客户信息失败！',
                            type:'error'
                          })
                      }
                  })
                  this.add_dialogFormVisible = false;
                  this.updateDevice(form.device_id, form.type==1?(-form.number):form.number);
                  //更新涉及订单状态
                  for(var a = 0; a < this.flowDetail.length; a++){
                      if(this.flowDetail[a].isFlow){
                          this.updateFlow(this.flowDetail[a].id, this.flowDetail[a].newLast)
                      }
                  }
                  this.addForm = {type:'1',begin_date:(new Date).valueOf(),discount:0,end_date:(new Date).valueOf()},
                  this.$message({
                    message:'新增订单成功！',
                    type:'success'
                  })

              }else {
                  if(res.code == -1){
                      var message = '新增订单失败，缺少参数！'
                  }else if(res.code == 1){
                      var message = '新增订单失败，请稍后重试！'
                  }
                  this.$message({
                    message:message,
                    type:"error"
                  });
                  this.$store.commit('setLoading', 0)
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
      formatter(row, column) {
          if(row.type == 1){
            return this.formatDate(Number(row.begin_date))
          }else {
              return '---'
          }
      },
      formatterDate(row, colum){
        return this.formatDate(Number(row.create_date), 1)
      },
      formatterMoney(row, colum){
          if(row.type == 0){
              return row.money
          }else {
              return '---'
          }
      },
      formatterDiscount(row, colum){
        if(row.type == 0){
          return row.discount
        }else {
          return '---'
        }
      },
      //时间格式化
      formatDate: function (value, type) {
        let date = new Date(value);
        let y = date.getFullYear();
        let MM = date.getMonth() + 1;
        MM = MM < 10 ? ('0' + MM) : MM;
        let d = date.getDate();
        d = d < 10 ? ('0' + d) : d;
        let h = date.getHours();
        h = h < 10 ? ('0' + h) : h;
        let m = date.getMinutes();
        m = m < 10 ? ('0' + m) : m;
        let s = date.getSeconds();
        s = s < 10 ? ('0' + s) : s;
        if(type){
          return y + '-' + MM + '-' + d + ' ' + h + ':' + m + ':' + s;
        }else {
          return y + '-' + MM + '-' + d ;
        }
      },
      //更新库存
      updateDevice(id, num){
        var $this = this;
        this.$.ajax({
          method:'POST',
          url:'update_device.php',
          data:this.qs({id,num})
        }).then((res)=>{
            if(res.code != 0){
                if(res.code == -1){
                    var message = "更新库存失败，缺少参数！"
                }else if(res.code == 1){
                    var message = "更新库存失败，请重试！"
                }
                this.$message({
                  message:message,
                  type:'error'
                })
            }else {
              $this.getFlow(this.site.id);
            }
        })
      },
      //删除
      handleRemove(index, row){
        this.$confirm('确定要删除编号为 ' + row.id + ' 的订单吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.$store.commit('setLoading', 1);
          this.$.ajax({
            method: "POST",
            url: 'remove.php',
            data: this.qs({name: 'flow', type: 0, id: row.id})
          }).then((res) => {
            if (res.code == 0) {
              //更新客户信息
              this.$.ajax({
                method:'POST',
                url:'edit_site.php',
                data:this.qs({
                  id:this.site.id,
                  isfinished:0
                })
              }).then((res)=>{
                if(res.code != 0){
                  this.$message({
                    message:'更新客户信息失败！',
                    type:'error'
                  })
                }
              })
              this.updateDevice(row.device_id, row.number);
              if(row.type == 0){
                var detail = JSON.parse(row.detail);
                for(var a = 0; a < detail.length; a++){
                  if(detail[a].isFlow){
                    this.updateFlow(detail[a].id, detail[a].number, 'remove')
                  }
                }
              }
//              this.getFlow(this.site.id);
              this.$message({
                message: '删除成功！',
                type: 'success'
              })
            }
          })
        }).catch(() => {
        });
      },
      //匹配当前device
      findDevice(device){
          for(var a = 0; a < this.deviceList.length; a++){
              if(this.deviceList[a].id == device){
                  this.currDevice = this.deviceList[a];
                  break;
              }
          }
      },
      //计算总金额
      computedMoney(){
          console.log(this.addForm.number);
          if(this.addForm.type == 0 && this.addForm.device_id){
              this.flowArr = [];
              this.countNumber = 0;
              for(var a = 0; a < this.allData.length; a++){
                  if(this.allData[a].type == 1 && this.allData[a].device_id == this.currDevice.id && this.allData[a].settle == 0){
                      if(this.allData[a].settle_last - (this.addForm.number - this.countNumber) >= 0){
                        this.flowArr.push({number:this.addForm.number - this.countNumber, flow:this.allData[a]});
                        this.countNumber += (this.addForm.number - this.countNumber)
                        break;
                      }else {
                        this.flowArr.push({number:Number(this.allData[a].settle_last), flow:this.allData[a]});
                        this.countNumber += Number(this.allData[a].settle_last);
                      }
                  }
              }
              console.log(this.flowArr);
              console.log(this.countNumber);
              this.addForm.money = 0;
              this.flowDetail = [];
              for(var a = 0; a < this.flowArr.length; a++){
                  var item_days = (Number(this.addForm.end_date) - Number(this.flowArr[a].flow.begin_date)) / (60 * 60 * 1000 * 24);
                  if(item_days < 0){
                    item_days = 0;
                    this.$alert("归还数量大于以往出租数量！");
                  }
                  item_days = Math.round(item_days);
                this.addForm.money += Number(this.currDevice.price) * this.flowArr[a].number * item_days
                this.flowDetail.push({
                  days:item_days,
                  id:this.flowArr[a].flow.id,
                  begin_date:this.formatDate(Number(this.flowArr[a].flow.begin_date)),
                  number:this.flowArr[a].number,
                  money:(Number(this.currDevice.price) * this.flowArr[a].number * item_days).toFixed(2),
                  isFlow:true,
                  newLast:Number(this.flowArr[a].flow.settle_last) - Number(this.flowArr[a].number),
                  price:this.currDevice.price
                });
                console.log(this.flowArr[a].flow.settle_last,this.flowArr[a].number)
              }
              if(this.countNumber - this.addForm.number < 0){
                this.$alert("归还数量大于以往出租数量！");
                this.flowDetail.push({
                  id:'---',
                  begin_date:'---',
                  number:this.addForm.number - this.countNumber,
                  money:0,
                  isFlow:false,
                });
              }
              console.log(this.flowDetail)
              this.addForm.money = this.addForm.money.toFixed(2);
              this.addForm = Object.assign({}, this.addForm, {...this.addForm});
          }
      },
      //计算天数
      computedDay(){
          var today = (new Date()).valueOf();
      },
      //更新涉及订单状态
      updateFlow(id, settle_last, remove){
          var $this = this;
          var data = {
              id:id,settle_last:settle_last
          };
          console.log(data)
          if(remove){
            data.remove = true;
          }
          this.$.ajax({
            method:'POST',
            url:'update_flow.php',
            data:this.qs(data)
          }).then((res)=>{
              console.log(res);
              if(res.code != 0){
                this.$message({
                  message:'更新订单失败！',
                  type:'error'
                })
              }else {
                $this.getFlow(this.site.id);
              }
          })
      },
      //查看明细
      showDetail(index, row){
          this.check_flow_detail = JSON.parse(row.detail);
          this.currFlow = row;
          console.log(this.check_flow_detail)
          this.detail_dialogFormVisible = true;
          this.check_flow_id = row.id
      },
      getSummaries(param){
        var data = param.data;
        var sums = ['总计', '', '','', 0, 0]
        for(var a = 0; a < data.length; a++){
            sums[4] += Number(data[a].number);
            sums[5] += Number(data[a].money);
        }
        return sums
      },
      //切换支付状态
      togglePaid(row){
        this.$confirm('确定要更改订单 ' + row.id + ' 的支付状态吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.$store.commit('setLoading', 1);
          this.$.ajax({
            url:'toggle_ispaid.php?flow_id=' + row.id
          }).then((res)=>{
            this.$store.commit('setLoading', 0);
            if(res.code != 0){
              this.$message({
                message:'操作支付状态失败！',
                type:'error'
              })
            }else {
              this.getFlow(this.site.id)
            }
          })
        })
      },
      myPrint(){
          this.printing = true;
          document.getElementById('siteTitle').innerText = '收费明细--订单编号' + this.check_flow_id;
          setTimeout(()=>{
            this.$print(this.$refs.printTable);
          })

        setTimeout(()=>{
          this.printing = false;
          document.getElementById('siteTitle').innerText = '订单管理';
        },2000)
      },
      filterHandler(value, row, column) {
        const property = column['property'];
        return row[property] === value;
      },
      printFlow(){
          this.printing = true;
          setTimeout(()=>{
            /* generate workbook object from table */
            var wb = XLSX.utils.table_to_book(document.querySelector('#flow_table'))
            /* get binary string as output */
            var wbout = XLSX.write(wb, { bookType: 'xlsx', bookSST: true, type: 'array' })
            try {
              FileSaver.saveAs(new Blob([wbout], { type: 'application/octet-stream' }), this.site.name + '订单' + '_' + this.formatDate(new Date()) + '.xlsx')
            } catch (e) { if (typeof console !== 'undefined') console.log(e, wbout) }
            return wbout;
          },100);
          setTimeout(()=>{
            this.printing = false;
          },500)

      },
      sortById(a, b){
//          console.log(a);
          var item1 = Number(a.id);
          var item2 = Number(b.id);
          return item1 - item2
      }
    },
    created(){
      this.$store.commit('setLoading', 1);
      this.getDevice()
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
    },
    watch:{
      add_dialogFormVisible(val){
          if(val == false){
            this.addForm = {type:'1',begin_date:(new Date).valueOf(),discount:0, end_date:(new Date).valueOf()}
          }
      }
    }
  }
</script>

<style>
  .el-table .caret-wrapper{
    height: 34px;
    width: 0!important;
    left:-4px;
  }
  .detailTop{
    margin: -20px 0 12px
  }
  .detailTop span{
    padding:0 18px;
  }
  .el-table .el-table__column-filter-trigger{
    margin-left: 15px;
  }
</style>
