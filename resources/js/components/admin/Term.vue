<template>
    <Toast/>
    <div class="flex items-center mb-4 ml-4">
        <h1 class="text-custom-color-small font-istok text-4xl font-bold">Term in Major {{ majorId }} Gen {{ genId }}</h1>
        <button class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2" label="Add New" severity="secondary" @click="showDialog">
            <span class="flex items-center">
            <i class="fa-solid fa-circle-plus mr-2"></i>
            Add Term
            </span>
        </button>
        <Dropdown v-model="selectedTerm" :options="terms" optionValue="id" optionLabel="name" placeholder="Select a term" class="w-full md:w-14rem" />
    </div>
    <p v-if="selectedTerm">Here is term {{ selectedTerm}}</p>
      <!-- Add dialog -->
      <Dialog v-model:visible="visible" modal  :style="{ width: '35vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-full flex justify-center items-center ">
                <form action="" @submit.prevent="addTerm" class=" w-full flex justify-center ">
                    <div class="  w-[80%] ">
                        <div class="lg:justify-between items-center mb-4 ">
                            <label for="name" class="text-lg mr-2 mb-2">Term</label>
                            <input type="text" v-model="name" class=" outline  outline-slate-200 appearance-none  rounded w-full py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 "/>
                        </div>
                        <div class="lg:justify-between items-center mb-4">
                            <label for="name" class="text-lg mr-2 mb-2">Start Date</label>
                            <input  type="date" v-model="start_date"  class="outline  outline-slate-200 appearance-none  rounded w-full py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200  "  />
                        </div>
                        <input type="text" v-model="active" hidden>
                        <div class="flex justify-between mt-6">
                            <button v-on:click="closeDialog" class="w-32 bg-red-400 mr-2 text-white  border-2 hover:bg-red-700" >Cancel</button>
                            <button type="submit" class="w-32 py-2  bg-blue-400 text-white hover:bg-blue-700" >Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <template #header>
                <div class="text-center border-b border-gray-300 w-full pb-4">
                <h2 class="text-lg font-bold">Add New Term</h2>
                </div>
            </template>
        </Dialog>

    <!-- <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 lg:gap-4 md:gap-3 sm:gap-2 justify-center mb-2">
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
    </div> -->


</template>
<script>
import axios from 'axios';
export default{
    data(){
        return{
            genId: null,
            majorId: null,
            terms: [],
            term: [],
            name: '',
            start_date: '',
            active: true,
            schedule: [],
            selectedTerm: null,
            visible: false,


        }
    },
    mounted(){
        this.getTerms();
        this.genId= this.$route.params.genid;
        this.majorId = this.$route.params.id;
        this.getSchedule(this.majorId, this.genId, this.selectedTerm)
    },
    methods:{
        // term
        getTerms(){
            axios.get('terms').then(
                res=>{
                    this.terms = res.data
                    console.log(this.terms)
                }
            ).catch(er=>{
                console.error(er)
            })
        },
        addTerm(){
            axios.post('terms',{
                name: this.name,
                start_date: this.start_date,
                active: this.active
            }).then(
                res=>{
                    this.getTerms();
                    this.visible = false
                    this.$toast.add({ severity: 'success', summary: 'Add Successfully', detail: 'New Term added', life: 3000 });
                }
            )

        },



        // schedule
        getSchedule(majorId, genId, termId){
            axios.get(`schedule/${majorId}/${genId}/${termId}`).then(
                res=>{
                    this.schedule = res.data
                    console.log(this.schedule)
                }
            )

        },
        // dialog
        showDialog(){
            this.visible= true
        },
        closeDialog(){
            this.visible = false
        }

    }

}
</script>
