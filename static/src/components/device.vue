<template>
  <div>
    {{msg}}
  </div>
</template>

<script>
  export default{
    name: 'device',
    data () {
      return {
        msg: '设备模块',
        dataList:[]
      }
    },
    methods:{
        getDevice(){
            this.$.ajax({
              method:'GET',
              url:'get_device.php'
            }).then((res)=>{
                if(res.code == 0){
                    this.dataList = res.data;
                }
            })
        }
    },
    mounted(){
      this.getDevice()
    }
  }
</script>
