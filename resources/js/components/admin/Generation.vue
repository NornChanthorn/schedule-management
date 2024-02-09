<template>
    <div class=" p-9 grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 lg:gap-4 md:gap-3 sm:gap-2 justify-center">
        <div v-for="gen in generations" :key="gen?.id" class=" lg:w-[90%] md:w-[80%] sm:w-[70%] rounded-xl shadow-md border-2 " >
                <router-link :to="{ name: 'Term', params: { id: gen.id } }">
                    <div class=" flex justify-center items-center">
                        <img src="../../../../public/img/major-logo.jpg" alt="" class="  ">
                    </div>
                    <div class=" flex justify-between p-2">
                        <h3 class=" text-lg">Generation {{ gen.gen}}</h3>
                        <h1 class=" pi pi-ellipsis-v p-2"></h1>
                    </div>
                </router-link>
            </div>
    </div>
</template>
<script>
import axios from 'axios';
export default{
    data(){
        return{
            generations: []
        }
    },
    mounted(){
        this.getGeneration();


    },
    methods:{
        getGeneration(){
            axios.get('generations').then(
                res=>{
                    this.generations = res.data
                    console.log(this.generations)
                }
            ).catch(er=>{
                console.error(er)
            })
        }
    }

}
</script>
