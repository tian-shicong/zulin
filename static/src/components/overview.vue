<template>
  <div style="padding:8px 24px;height: 100%">
    <div class="breadcrumbWrap">
      <div class="breadcrumb-box">
        <el-breadcrumb separator-class="el-icon-arrow-right">
          <el-breadcrumb-item :to="{ path: '/order' }" >{{site.name}}</el-breadcrumb-item>
          <el-breadcrumb-item >订单总览</el-breadcrumb-item>
        </el-breadcrumb>
      </div>

      <!--<search :allData="allData" :dataList="dataList" v-model="dataList" style="float: right"></search>-->
      <el-button  plain style="float: right;margin-right: 8px" @click="settleAll" v-if="!settle_disable && user.role == 1 && overviewList.length > 0">结算全部</el-button>
      <span style="float: right;margin-right: 8px" v-if="settle_disable">已结算完成</span>
    </div>
    <div style="width: 500px;height: 87%;margin: 0 auto;">
      <el-table
        :data="overviewList"
        height="100%"
        style="width: 100%;"
      >
        <el-table-column
          prop="device.name"
          label="设备名称"
        >
        </el-table-column>
        <el-table-column
          prop="outNumber"
          label="出租数量"
        >
        </el-table-column>
        <el-table-column
          prop="inNumber"
          label="已还数量"
          :formatter="formatter"
        >
        </el-table-column>
        <el-table-column
          prop="last"
          label="待还数量"
        >
        </el-table-column>
      </el-table>
    </div>
    <!--结算全部弹框-->
    <el-dialog title="结算全部" :visible.sync="settle_dialogFormVisible" style="text-align: left;" width="40%">
      <el-table
        :data="settleDetalList"
        style="width: 100%;"
        max-height="350"
        show-summary
      >
        <el-table-column
          prop="device_name"
          label="设备名称"
        >
        </el-table-column>
        <el-table-column
          prop="last"
          label="待还数量"
        >
        </el-table-column>
        <el-table-column
          prop="loss_price"
          label="赔偿单价"
        >
        </el-table-column>
        <el-table-column
          prop="money"
          label="金额"
        >
        </el-table-column>

      </el-table>
      <div slot="footer" class="dialog-footer">
        <el-button @click="settle_dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="settleCommit" :disable="button_disable">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
  export default{
    name: 'Overview',
    computed:{
      user(){
        return this.$store.state.user;
      }
    },
    data () {
      return {
        msg: '订单总览',
        site:{},
        flowList:[],
        overviewList:[],
        deviceList:[],
        settle_dialogFormVisible:false,
        settleDetalList:[],
        button_disable:false,
        settleItem:{},
        settle_disable:false
      }
    },
    methods:{
      //获取流水
      getFlow(id){
        this.$.ajax({
          url:'get_flow.php?id=' + id
        }).then((res)=>{
          this.$store.commit('setLoading', 0);
          if(res.code == 0){
            this.flowList = res.data;
            this.getDeviceArr()
          }
        })
      },
      //查询site
      getSite(id){
        this.$.ajax({
          url:'get_site.php?id=' + id
        }).then((res)=>{
          if(res.code == 0 && res.data.length > 0){
            this.site = res.data[0];
            if(this.site.isfinished == 1){
                this.settle_disable = true;
            }
            console.log(this.site)
            this.getFlow(this.site.id)
          }else {
            this.$message({
              message:'获取客户信息失败！',
              type:'error'
            })
          }
        })
      },
      //统计设备种类
      getDeviceArr(){
          for(var a = 0; a < this.flowList.length; a++){
              if(this.deviceList.indexOf(this.flowList[a].device_id) < 0){
                  this.deviceList.push(this.flowList[a].device_id);
              }
          }
          this.computeOverView()
      },
      //计算设备数量
      computeOverView(){
          for(var a = 0; a < this.deviceList.length; a++){
              this.overviewList[a] = {outNumber:0, inNumber:0};
              for(var b = 0; b < this.flowList.length; b++){
                  if(this.flowList[b].device_id == this.deviceList[a]){
                    this.overviewList[a].device = this.flowList[b].device;
                    if(this.flowList[b].type == 1){
                      this.overviewList[a].outNumber += Number(this.flowList[b].number)
                    }
                    if(this.flowList[b].type == 0){
                      this.overviewList[a].inNumber += Number(this.flowList[b].number)
                    }else if(this.flowList[b].type == 1 && this.flowList[b].settle == 1 && this.flowList[b].settle_last > 0){
                      this.overviewList[a].inNumber -= Number(this.flowList[b].settle_last)
                    }
                  }
              }
              this.overviewList[a].last = this.overviewList[a].outNumber + this.overviewList[a].inNumber;
          }
          this.overviewList = this.overviewList.concat()
      },
      formatter(row, column){
          return -row.inNumber
      },
      settleAll(){
        this.getSettleDetail();
        this.settle_dialogFormVisible = true
      },
      getSettleDetail(){
          for(var a = 0; a < this.overviewList.length; a++){
              var item = {};
              item.device_id = this.overviewList[a].device.id;
              item.device_name = this.overviewList[a].device.name;
              item.last = this.overviewList[a].last;
              item.loss_price = this.overviewList[a].device.loss_price;
              item.money = (item.last * item.loss_price).toFixed(2);
              this.settleDetalList[a] = item;
          }
          console.log(this.settleDetalList)
      },
      settleCommit(){
          //更新库存 遍历
          //更新流水状态
          //写入结算明细
          //更新客户状态
          //计算总金额
          var allMoney = 0;
          for(var a = 0; a < this.settleDetalList.length; a++) {
            allMoney += Number(this.settleDetalList[a].money)
          }
          if(allMoney <= 0){
              this.$alert("总金额小于等于0，无需结算！");
              return;
          }
          this.settleItem = {
            site_id:this.site.id,
            create_date:(new Date()).valueOf(),
            detail:JSON.stringify(this.settleDetalList),
            money:allMoney
          }
          this.$.ajax({
            method:'POST',
            url:'add_settle.php',
            data:this.qs(this.settleItem)
          }).then((res)=>{
              this.button_disable = false;
              if(res.code == 0){
                  this.$store.commit('reload', false);
                  this.settle_dialogFormVisible = false;
                  this.updateCount();
                  this.updateFlowSettle();
                  this.updateSiteFinish();
              }else {
                this.$alert("提交结算失败！");
              }
          })
          this.button_disable = true;
      },
      //更新库存
      updateCount(){
          for(var a = 0; a < this.settleDetalList.length; a++){
              console.log(this.settleDetalList[a].device_id, -this.settleDetalList[a].last);
              //更新库存
              this.$.ajax({
                method:'POST',
                url:'update_device_count.php',
                data:this.qs({
                  id:this.settleDetalList[a].device_id,
                  num:-this.settleDetalList[a].last
                })
              }).then((res)=>{
                  if(res.code != 0){
                      this.$message({
                        message:'更新库存失败！',
                        type:'error'
                      })
                  }
              })
          }
      },
      //更新流水状态
      updateFlowSettle(){
          this.$.ajax({
            method:"POST",
            url:'batch_settle.php',
            data:this.qs({site_id:this.site.id})
          }).then((res=>{
              if(res.code != 0){
                this.$message({
                  message:'更新流水状态失败！',
                  type:'error'
                })
              }
          }))
      },
      //更新客户状态
      updateSiteFinish(){
        this.$.ajax({
          method:"POST",
          url:'edit_site.php',
          data:this.qs({
            id:this.site.id,
            isfinished:1,
            money:this.settleItem.money
          })
        }).then((res=>{
          if(res.code != 0){
            this.$message({
              message:'更新客户状态失败！',
              type:'error'
            })
          }
        }))
      }
    },
    created(){
      this.$store.commit('setActiveIndex', 'order');
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
