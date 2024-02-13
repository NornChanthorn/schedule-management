<template>
    <div class=" p-9 grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 lg:gap-4 md:gap-3 sm:gap-2 justify-center ">
        <div v-for="term in terms" :key="term?.id" class=" lg:w-[90%] md:w-[80%] sm:w-[70%] rounded-xl shadow-md border-2 " >
                <router-link :to="{ name: 'schedule', params: { name: term.name } }">
                    <div class=" flex justify-center items-center">
                        <img src="../../../../public/img/major-logo.jpg" alt="" class="  ">
                    </div>
                    <div class=" flex justify-between p-2">
                        <h3 class=" text-lg">{{ term.name}}</h3>
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
            terms: []
        }
    },
    mounted(){
        this.getTerms();
    },
    methods:{
        getTerms(){
            axios.get('terms').then(
                res=>{
                    this.terms = res.data
                    console.log(this.terms)
                }
            ).catch(er=>{
                console.error(er)
            })
        }
    }

}
</script>
