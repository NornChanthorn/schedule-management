<template>
    <Toast/>
    <div class="flex items-center mb-4 ml-4">
        <h1 class="text-custom-color-small font-istok text-4xl font-bold">{{ MajorName}}, Generation {{ gen }}</h1>
        <button class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" @click="showDialog">
            <span class="flex items-center">
            <i class="fa-solid fa-circle-plus mr-2"></i>
            Add Term
            </span>
        </button>
        <!-- <Dropdown v-model="selectedTerm" :options="terms" optionValue="id" optionLabel="name" placeholder="Select a term" class="w-full md:w-14rem" /> -->
    </div>
    <!-- <p v-if="selectedTerm">Here is term {{ selectedTerm}}</p> -->
    <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 lg:gap-4 md:gap-3 sm:gap-2 justify-center mb-2">
            <div v-for="term in terms" :key="term?.id" class="border-2 hover:shadow-md transition-shadow duration-300 m-2">
                <router-link :to="{ name: 'schedule', params: { termId: term.id }}" >
                    <div class="flex justify-center items-center">
                        <img src="../../../../public/img/major-logo.jpg" alt="" class="">
                    </div>
                </router-link>
                <div class="flex justify-between p-2">
                    <h3 class="text-lg hover:text-blue-500 transition-all duration-300">Term {{ term.name}}</h3>
                    <i class="pi pi-ellipsis-v p-2 hover:bg-gray-200 transition-all duration-300 rounded-full" @click="showOptions(term.id)" ></i>
                </div>
            </div>
    </div>
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

        <!-- edit  -->
        <Dialog v-model:visible="visibleEdit" modal  :style="{ width: '35vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-full flex justify-center items-center ">
                <form action="" @submit.prevent="editTerm" class=" w-full flex justify-center ">
                    <div class="  w-[80%] ">
                        <div class="lg:justify-between items-center mb-4 ">
                            <label for="name" class="text-lg mr-2 mb-2">Term</label>
                            <input type="text" v-model="name" class=" outline  outline-slate-200 appearance-none  rounded w-full py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 "/>
                        </div>
                        <div class="lg:justify-between items-center mb-4">
                            <label for="name" class="text-lg mr-2 mb-2">Start Date</label>
                            <input  type="date" v-model="start_date" class="outline  outline-slate-200 appearance-none  rounded w-full py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200  "  />
                        </div>
                        <input type="text" v-model="active" hidden>
                        <div class="flex justify-between mt-6">
                            <button v-on:click="closeEditDialog" class="w-32 bg-red-400 mr-2 text-white  border-2 hover:bg-red-700" >Cancel</button>
                            <button type="submit" class="w-32 py-2  bg-blue-400 text-white hover:bg-blue-700" >Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <template #header>
                <div class="text-center border-b border-gray-300 w-full pb-4">
                <h2 class="text-lg font-bold">Edit Term</h2>
                </div>
            </template>
        </Dialog>



    <OverlayPanel ref="op" class="">
        <div class=" w-30 flex flex-col items-start ">
            <button @click="showEditDialog(itemID)" class="w-full p-2 text-blue-500 flex items-center justify-start hover:bg-gray-200 focus:outline-none" >
                <i class="fas fa-edit mr-2"></i> Edit
            </button>
            <button @click="confirmDelete(itemID)" class="w-full p-2 text-red-500 flex items-center justify-start hover:bg-gray-200 focus:outline-none">
                <i class="fas fa-trash-alt mr-2"></i> Delete
            </button>
        </div>
    </OverlayPanel>

</template>
<script>
import axios from 'axios';
import { ref } from 'vue';
export default{
    data(){
        return{
            genId: null,
            majorID: null,
            majorName: null,
            MajorName: null,

            gen: '',
            terms: [],
            term: [],
            name: '',
            start_date: '',
            active: true,
            schedule: [],
            selectedTerm: null,
            visible: false,
            visibleEdit: false,

        }
    },
    mounted(){
        this.getTerms();
        this.genId= this.$route.params.genid;
        this.majorID = this.$route.params.majorId;
        this.MajorName= this.$route.params.name;
        this.getMajorID(this.majorID);
        this.getSchedule(this.majorID, this.genId, this.selectedTerm)
        this.getGenerationID(this.genId)

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
        getGenerationID(id){
            axios.get(`generations/${id}`).then(
                res=>{
                    this.gen = res.data.gen
                }
            )

        },
        getMajorID(id){
            axios.get(`majors/${id}`).then(
                res=>{
                    this.majorName = res.data.name
                    console.log(this.majorName)
                }
            )
        },
        editTerm(id){
            axios.put(`terms/${id}`, {name: this.name, start_date: this.start_date}).then(
                res=>{
                    this.getTerms();
                    this.visibleEdit= false
                    this.$toast.add({ severity: 'success', summary: 'Edit Successfully', detail: 'Term Edit Successfully', life: 3000 });
                }

            ).catch(er=>{
                this.$toast.add({ severity: 'error', summary: 'Failed to Edit ', detail: er, life: 3000 });
            })
        },
        getByID(id){
            axios.get(`terms/${id}`).then(
                res=>{
                    this.term = res.data
                    console.log(this.major)
                }
            )
        },
        // dialog
        showDialog(){
            this.visible= true
        },
        closeDialog(){
            this.visible = false
        },
        showEditDialog(id){
            this.visibleEdit= true
            this.id = id
            this.getByID(this.id);
        },
        closeEditDialog(){
            this.visibleEdit= false
        },

    },
    setup() {
    const op = ref(null);
    const itemID = ref(null);


    const showOptions = (id) => {
        itemID.value= id
        op.value.toggle(event);

    };

    return {
      op,
      itemID,
      showOptions,
    };
  },


}
</script>
