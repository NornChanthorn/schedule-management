<template>

    <div class="flex items-center mb-4 ml-4">
        <h1 class="text-custom-color-small font-istok text-4xl font-bold">{{ majorName}}, Generation {{ GenName }}, Term {{ termName }}</h1>
        <button class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" >
            <span class="flex items-center">
            <i class="fa-solid fa-circle-plus mr-2"></i>
            Add Course
            </span>
        </button>
        <!-- <Dropdown v-model="selectedTerm" :options="terms" optionValue="id" optionLabel="name" placeholder="Select a term" class="w-full md:w-14rem" /> -->
    </div>
    <p class="mb-4 ml-4">All Courses:</p>
    <div class=" flex gap-4 mb-4 ml-4">
        <ul v-for="course in courses" :key="course?.id" class="" >
            <li class=" border p-3 rounded-lg border-blue-400 text-gray-500">{{ course.name }}</li>
        </ul>
    </div>


</template>
<script>
import axios from 'axios';
export default{
    data(){
        return{
            termID: null,
            genID: null,
            majorID: null,
            termName: null,
            GenName: null,
            majorName: null,
            courses: [],

        }
    },
    mounted(){
        this.termID = this.$route.params.termId;
        this.genID = this.$route.params.genid;
        this.majorID = this.$route.params.majorId;
        this.majorName = this.$route.params.name;
        this.getCourse(this.majorID, this.genID, this.termID);
        this.getGenerationID(this.genID);
        this.getTermID(this.termID);

    },
    methods:{
        getCourse(majorId, genId, termId){
            axios.get(`course/${majorId}/${genId}/${termId}`).then(
                res=>{
                    this.courses= res.data.data
                    console.log(this.courses)
                }
            ).catch(er=>{
                console.error(er)
            })

        },
        getGenerationID(id){
            axios.get(`generations/${id}`).then(
                res=>{
                    this.GenName = res.data.gen
                }
            )

        },
        getTermID(id){
            axios.get(`terms/${id}`).then(
                res=>{
                    this.termName = res.data.name
                }
            )

        },



    }

}

</script>
