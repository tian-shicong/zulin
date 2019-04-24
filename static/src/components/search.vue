<template>
  <el-input type="text" v-model="currentValue" @change="handleChange" style="width: 200px" @input="handleChange" placeholder="输入关键词查询"></el-input>
</template>

<script>
  export default{
    name: 'search',
    props:{
      allData:{
        type:Array,
        default:[]
      },
      dataList:{
        type:Array,
        default:[]
      }
    },
    data () {
      return {
        currentValue:'',
        myDataList:this.dataList
      }
    },
    methods:{
        searchData(){
            this.myDataList = [];
            if(this.currentValue != '' && this.currentValue != undefined){
              for(var a = 0; a < this.allData.length; a++){
                if(JSON.stringify(this.allData[a]).indexOf(this.currentValue) > -1){
                  this.myDataList.push(this.allData[a]);
                }
              }
            }else {
              this.myDataList = this.allData;
            }
        },
        handleChange(){
            this.searchData();
            this.$emit('input', this.myDataList)
        }
    },
    watch:{
      allData(){
        this.searchData();
        this.$emit('input', this.myDataList)
      }
    }
  }
</script>
