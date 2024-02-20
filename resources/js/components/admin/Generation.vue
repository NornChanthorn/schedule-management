<template>
   <Toast/>
    <div class="flex items-center mb-4 ml-4">
        <h1 class="text-custom-color-small font-istok text-4xl font-bold">Generation</h1>
        <button class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2" label="Add New" severity="secondary" @click="showDialog">
            <span class="flex items-center">
            <i class="fa-solid fa-circle-plus mr-2"></i>
                Add More
            </span>
        </button>
    </div>
    <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 lg:gap-4 md:gap-3 sm:gap-2 justify-center mb-2">
      <div v-for="gen in generations" :key="gen?.id" class="border-2 hover:shadow-md transition-shadow duration-300 m-2">
        <router-link :to="{ name: 'Term', params: { genid: gen.id } }" >
            <div class="flex justify-center items-center">
                <img src="../../../../public/img/major-logo.jpg" alt="" class="">
            </div>
        </router-link>
        <div class="flex justify-between p-2">
            <h3 class="text-lg hover:text-blue-500 transition-all duration-300">Generation {{ gen.gen}}</h3>
            <i class="pi pi-ellipsis-v p-2 hover:bg-gray-200 transition-all duration-300 rounded-full" @click="showOptions(gen.id)" ></i>
        </div>
      </div>
    </div>
    <!-- Add -->
    <Dialog v-model:visible="visible" modal  :style="{ width: '40vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-full flex justify-center items-center">
                <form action="" @submit.prevent="addGeneration">
                    <div class="lg:justify-between items-center mb-4">
                        <label for="name" class="text-lg mr-2 mb-2">Generation</label>
                        <input type="text" v-model="gen" class="mt-1 p-2 w-full border rounded outline  outline-slate-200 appearance-none  py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 ">
                    </div>
                    <div class="flex justify-between mt-6">
                        <button v-on:click="closeDialog" class="w-32 bg-red-500 mr-2 text-white  border-2 hover:bg-red-700" >Cancel</button>
                        <button type="submit" class="w-32 py-2 border-2  bg-blue-500 text-white hover:bg-blue-700" >Save</button>
                    </div>
                </form>
            </div>
            <template #header>
                <div class="text-center border-b border-gray-300 w-full pb-4">
                <h2 class="text-lg font-bold">Add Generation</h2>
                </div>
            </template>
    </Dialog>
    <!-- edit -->
    <Dialog v-model:visible="visibleEdit" modal  :style="{ width: '40vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-full flex justify-center items-center">
                <form action="" @submit.prevent="editGeneration(id)">
                    <div class="lg:justify-between items-center mb-4">
                        <label for="name" class="text-lg mr-2 mb-2">Generation</label>
                        <input type="text" v-model="gen" class="mt-1 p-2 w-full border rounded outline  outline-slate-200 appearance-none  py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"  :placeholder="generation.gen">
                    </div>
                    <div class="flex justify-between mt-6">
                        <button v-on:click="closeEdit" class="w-32 bg-red-500 mr-2 text-white border-2 hover:bg-red-700" >Cancel</button>
                        <button type="submit" class="w-32 py-2 border-2  bg-blue-500 text-white hover:bg-blue-700" >Save</button>
                    </div>
                </form>
            </div>
            <template #header>
                <div class="text-center border-b border-gray-300 w-full pb-4">
                <h2 class="text-lg font-bold">Edit Major</h2>
                </div>
            </template>
        </Dialog>
        <OverlayPanel ref="op">
            <div class="w-40 flex flex-col items-start">
                <button @click="showEditDialog(itemID)" class="w-full p-3 text-blue-500 flex items-center justify-start hover:bg-gray-200 focus:outline-none" >
                    <i class="fas fa-edit mr-2"></i> Edit
                </button>
                <button @click="confirmDelete(itemID)" class="w-full p-3 text-red-500 flex items-center justify-start hover:bg-gray-200 focus:outline-none">
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
            majorId: null,
            generations: [],
            generation: [],
            visible: false,
            gen: '',
            id: null,
            visibleDelete: false,
            visibleEdit: false,


        }
    },
    mounted(){
        this.getGeneration();
        this.majorId = this.$route.params.id;

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
        },
        addGeneration(){
            axios.post('generations',{
                gen: this.gen
            }).then(
                res=>{
                    this.getGeneration();
                    this.visible = false
                    this.$toast.add({ severity: 'success', summary: 'Add Successfully', detail: 'New Generation added', life: 3000 });
                }
            )

        },
        editGeneration(id){
            axios.put(`generations/${id}`, {gen: this.gen}).then(
                res=>{
                    this.getGeneration();
                    this.visibleEdit= false
                    this.$toast.add({ severity: 'success', summary: 'Edit Successfully', detail: 'Generation Edit Successfully', life: 3000 });
                }
            ).catch(er=>{
                this.$toast.add({ severity: 'error', summary: 'Failed to Edit ', detail: er, life: 3000 });
            })

        },
        deleteGen(id){
            axios.delete(`generations/${id}`).then(
                res=>{
                    this.getGeneration()
                    this.visibleDelete= false
                    this.$toast.add({ severity: 'success', summary: 'Generation has been delete', life: 3000 });
                }
            ).catch(er=>{
                this.$toast.add({ severity: 'error', summary: 'Failed to Delete ', detail: er, life: 3000 });
            })
        },
        getByID(id){
            axios.get(`generations/${id}`).then(
                res=>{
                    this.generation = res.data
                }
            )
        },
        showDialog(){
            this.visible= true
        },
        closeDialog(){
            this.visible = false
        },
        showEditDialog(id){
            this.visibleEdit = true
            this.id = id
            this.getByID(this.id);
        },
        closeEdit(){
            this.visibleEdit= false
        },
        confirmDelete(id){
            Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.deleteGen(id);
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
