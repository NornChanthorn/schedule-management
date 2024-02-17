<template>
    <div class="flex items-center mb-4 ml-4">
        <h1 class="text-custom-color-small font-istok text-4xl font-bold">Term</h1>
        <button class="ml-auto bg-green-500 text-white px-2 py-2 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2" label="Add New" severity="secondary" @click="showDialog">
            <span class="flex items-center">
            <i class="fa-solid fa-circle-plus mr-2"></i>
            Add More
            </span>
        </button>
    </div>
    <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 lg:gap-4 md:gap-3 sm:gap-2 justify-center mb-2">
        <div v-for="term in terms" :key="term?.id"  class="border-2 hover:shadow-md transition-shadow duration-300 m-2">
        <router-link :to="{ name: 'schedule', params: { name: term.name } }">
          <div class="flex justify-center items-center">
            <img src="../../../../public/img/major-logo.jpg" alt="" class="">
          </div>
          <div class="flex justify-between p-2">
            <h3 class="text-lg hover:text-blue-500 transition-all duration-300">{{ term.name }}</h3>
            <h1 class="pi pi-ellipsis-v p-2 hover:bg-gray-200 transition-all duration-300 rounded-full"></h1>
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
