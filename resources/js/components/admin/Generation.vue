<template>
    <div class="p-4 ">
        Here is list of generation
        <div v-for="gen in generations" :key="gen?.id" class=" grid grid-cols-3">

            <div>
                <p v-if="gen">Generation{{ gen.gen }}</p>
                <p v-else>Generation information not available</p>
            </div>
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
            axios.get('api/generations').then(
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
