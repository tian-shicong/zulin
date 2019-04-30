<template>
  <div style="padding:8px 24px;height: 100%;overflow: auto">
    <h3>设备库存柱状图</h3>
    <div id="myChart" style="width: 80%;height: 500px;background-color: #fff;margin: 0 auto;"></div>
  </div>
</template>

<script>
  import echarts from 'echarts';
  export default{
    name: 'count',
    data () {
      return {
        msg: '数据统计',
        deviceList:[],
        deviceEchart:'',
        deviceOption:{}
      }
    },
    methods:{
      getDevice(){
          this.$.ajax({
            url:'get_device.php'
          }).then((res)=>{
              this.$store.commit('setLoading', 0);
              if(res.code == 0){
                  this.deviceList = res.data;
                  this.initChart();
              }else {
                  this.$message({
                    message:'获取设备列表失败',
                    type:'error'
                  })
              }
          })
      },
      initChart(){
        var source = [];
        for(var a = 0; a < this.deviceList.length; a++){
            source.push({
              'name':this.deviceList[a].name,
              '总数':this.deviceList[a].count,
              '库存':this.deviceList[a].last
            })
        }
        this.deviceEchart.setOption({
          color: ['#91c7ae', '#d48265',  '#ca8622', '#bda29a','#6e7074', '#546570', '#c4ccd3','#c23531','#2f4554', '#61a0a8', '#749f83'],
          legend: {},
          tooltip: {},
          dataset: {
            dimensions: ['name', '总数', '库存'],
            source: source
          },
          xAxis: {type: 'category'},
          yAxis: {},
          // Declare several bar series, each will be mapped
          // to a column of dataset.source by default.
          series: [
            {type: 'bar'},
            {type: 'bar'}
          ]
        })
      }
    },
    created(){
      this.$store.commit('setLoading', 1);
      this.$store.commit('setActiveIndex', 'count');
    },
    mounted(){
      this.deviceEchart = echarts.init(document.getElementById('myChart'));
      this.getDevice();
    }
  }
</script>
