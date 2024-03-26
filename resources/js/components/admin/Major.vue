<template>
    <Toast/>
       <div class="flex items-center mb-2 ml-2">
            <h1 class="text-custom-color-small font-istok text-4xl font-bold">Department</h1>
            <button class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" @click="showDialog">
                <span class="flex items-center">
                <i class="fa-solid fa-circle-plus mr-2"></i>
                Add More
                </span>
            </button>
        </div>
        <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 lg:gap-4 md:gap-3 sm:gap-2 justify-center mb-2">
            <div v-for="major in majors" :key="major?.id" class="border-2 hover:shadow-md transition-shadow duration-300 m-2">
                <router-link :to="{ name: 'Generation', params: { name: major.name, majorId: major.id } }" >
                    <div class="flex justify-center items-center">
                        <img src="../../../../public/img/major-logo.jpg" alt="" class="">
                    </div>
                </router-link>
                <div class="flex justify-between p-2">
                    <h3 class="text-lg hover:text-blue-500 transition-all duration-300">{{ major.name}}</h3>
                    <i class="pi pi-ellipsis-v p-2 hover:bg-gray-200 transition-all duration-300 rounded-full" @click="showOptions(major)" ></i>
                </div>
            </div>
        </div>
        <!-- Add dialog -->
       <Dialog v-model:visible="visible" modal  :style="{ width: '40vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-full flex justify-center items-center">
                <form action="" @submit.prevent="addMajor">
                    <div class="lg:justify-between items-center mb-4">
                        <label for="name" class="text-lg mr-2 mb-2">Major</label>
                        <input type="text" v-model="name" class="mt-1 p-2 w-full border rounded outline  outline-slate-200 appearance-none  py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 " placeholder="Name of Major">
                    </div>
                    <div class="flex justify-between mt-6">
                        <button v-on:click="closeDialog" class="w-32 bg-red-500 mr-2 text-white border-2  hover:bg-red-700" >Cancel</button>
                        <button type="submit" class="w-32 py-2  bg-blue-500 border-2 text-white hover:bg-blue-700" >Save</button>
                    </div>
                </form>
            </div>
            <template #header>
                <div class="text-center border-b border-gray-300 w-full pb-4">
                <h2 class="text-lg font-bold">Add Major</h2>
                </div>
            </template>
        </Dialog>
        <!-- Edit dialog -->
        <Dialog v-model:visible="visibleEdit" modal  :style="{ width: '40vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-full flex justify-center items-center">
                <p class=" hidden">id: {{ major.id }} </p>
                <form action="" @submit.prevent="editMajor(major.id)">
                    <div class="lg:justify-between items-center mb-4">
                        <label for="name" class="text-lg mr-2 mb-2">Major's name</label>
                        <input type="text" v-model="major.name"  class="mt-1 p-2 w-full border rounded outline  outline-slate-200 appearance-none  py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                 >
                    </div>
                    <p v-if="error == true"> Hererrr</p>
                    <div class="flex justify-between mt-6">
                        <button v-on:click="closeEditDialog" class="w-32 bg-red-500 mr-2 text-white border-2 hover:bg-red-700" >Cancel</button>
                        <button type="submit" class="w-32 py-2  border-2 bg-blue-500 text-white hover:bg-blue-700" >Save</button>
                    </div>
                </form>
            </div>
            <template #header>
                <div class="text-center border-b border-gray-300 w-full pb-4">
                <h2 class="text-lg font-bold">Edit Major</h2>
                </div>
            </template>
        </Dialog>

    <OverlayPanel ref="op" class="">
        <div class=" w-30 flex flex-col items-start ">
            <button @click="showEditDialog(itemID)" class="w-full p-2 text-blue-500 flex items-center justify-start hover:bg-gray-200 focus:outline-none" >
                <i class="fas fa-edit mr-2"></i> Edit
            </button>
            <button @click="confirmDelete(itemID.id)" class="w-full p-2 text-red-500 flex items-center justify-start hover:bg-gray-200 focus:outline-none">
                <i class="fas fa-trash-alt mr-2"></i> Delete
            </button>
        </div>
    </OverlayPanel>

</template>

<script>
import axios from 'axios';
import { ref } from 'vue';
import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'
export default{
    data(){
        return{
            majors: [],
            major:[],
            visible: false,
            name: '',
            id: null,
            visibleDelete: false,
            visibleEdit: false,
            error: false
        }
    },
    mounted(){
        this.getMajors();

    },
    methods:{
        getMajors(){
            axios.get('majors').then(
                res=>{
                    this.majors = res.data
                    console.log(this.majors)
                }
            ).catch(er=>{
                console.error(er)
            })
        },
        addMajor(){
            axios.post('majors',{
             name: this.name
            } ).then(
                res=>{
                    console.log(res.message);
                    this.getMajors();
                    this.visible= false
                    this.$toast.add({ severity: 'success', summary: 'Add Successfully', detail: 'New Major added', life: 3000 });
                }
            ).catch(er=>{
                console.error(er);
                // this.$toast.add({ severity: 'error', summary: 'Failed to add ', detail: er, life: 3000 });
            } )
        },
        deleteMajor(id){
            axios.delete(`majors/${id}`).then(
                res=>{
                    this.getMajors()
                    this.visibleDelete= false
                    this.$toast.add({ severity: 'success', summary: 'Major has been delete', life: 3000 });
                }
            ).catch(er=>{
                // this.$toast.add({ severity: 'error', summary: 'Failed to Delete ', detail: er, life: 3000 });
            })
        },
        editMajor(id){
            // const id = this.major.id
            axios.put(`majors/${id}`, this.major).then(
                res=>{
                    this.getMajors();
                    this.visibleEdit= false
                    this.$toast.add({ severity: 'success', summary: 'Edit Successfully', detail: 'Major Edit Successfully', life: 3000 });
                }

            ).catch(er=>{
                this.error = true
                this.$toast.add({ severity: 'error', summary: 'Failed to Edit ', detail: 'Error some part', life: 3000 });
            })
        },
        getByID(id){
            axios.get(`majors/${id}`).then(
                res=>{
                    this.major = res.data
                    console.log(this.major)
                }
            )
        },

        showDialog(){
            this.visible=true;
        },
        showEditDialog(itemId){
            this.visibleEdit= true
            this.major= itemId
        },
        closeEditDialog(){
            // this.major = null
            this.visibleEdit= false
            // this.major = null

        },
        closeDialog(){
            this.visible = false;
        },
        confirmDelete(id){
            Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                this.deleteMajor(id);
                this.visibleDelete = false;
            }
            else {
                console.log('Deletion canceled');
            }
        });
        }

    },
    setup() {
    const op = ref(null);
    const itemID = ref(null);


    const showOptions = (major) => {
        itemID.value= major
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
