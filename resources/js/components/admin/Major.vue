<template>
    <Toast/>
      <!-- <div class="w-[100%] p-6">

        <h1 class=" text-start ml-7 text-2xl text-slate-400">Department</h1>
      </div>
       <div class=" p-6 grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 lg:gap-4 md:gap-3 sm:gap-2 ">
            <div class="lg:w-[90%] md:w-[80%] sm:w-[70%] rounded-xl shadow-md border-2 flex justify-center items-center ">
                <Button class=" text-black pi pi-plus" label="Add New" severity="secondary" @click="showDialog"></Button>
            </div>
            <div v-for="major in majors" :key="major?.id" class=" lg:w-[90%] md:w-[80%] sm:w-[70%] rounded-xl shadow-md border-2 " >
                <div>
                    <router-link :to="{ name: 'Generation', params: { name: major.name } }" class=" flex justify-center items-center">
                        <img src="../../../../public/img/major-logo.jpg" alt="" class="  ">
                    </router-link>
                    <div class=" flex justify-between p-2">
                        <h3 class=" text-lg">{{ major.name}}</h3>
                       <i class="pi pi-ellipsis-v p-2 bg-slate-400 rounded-full"   @click="showOptions(major.id)" ></i>
                    </div>
                </div>
            </div>
       </div> -->
       <div class="flex items-center mb-4 ml-4">
            <h1 class="text-custom-color-small font-istok text-4xl font-bold">Department</h1>
            <button class="ml-auto bg-green-500 text-white px-2 py-2 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2" label="Add New" severity="secondary" @click="showDialog">
                <span class="flex items-center">
                <i class="fa-solid fa-circle-plus mr-2"></i>
                Add More
                </span>
            </button>
        </div>

        <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 lg:gap-4 md:gap-3 sm:gap-2 justify-center mb-2">
            <div v-for="major in majors" :key="major?.id" class="border-2 hover:shadow-md transition-shadow duration-300 m-2">
                <router-link :to="{ name: 'Generation', params: { name: major.name } }" >
                    <div class="flex justify-center items-center">
                        <img src="../../../../public/img/major-logo.jpg" alt="" class="">
                    </div>
                </router-link>
                <div class="flex justify-between p-2">
                    <h3 class="text-lg hover:text-blue-500 transition-all duration-300">{{ major.name}}</h3>
                    <i class="pi pi-ellipsis-v p-2 hover:bg-gray-200 transition-all duration-300 rounded-full" @click="showOptions(major.id)" ></i>
                </div>
            </div>
        </div>
       <!-- <Dialog v-model:visible="visibleDelete" header="Confirm">
          <div class="">
            <h1>Are you sure to delete {{ major.name }} ?</h1>
            <div class="flex justify-between">
                <Button label="No" severity="danger" @click="visibleDelete= false" ></Button>
                <Button label="Yes"  @click="deleteMajor(major.id)"></Button>
            </div>
          </div>
       </Dialog> -->


       <!-- <Dialog v-model:visible="visible" modal header="Add Major"  :style="{ width: '50vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" >
            <div class=" w-[100%] flex justify-center items-center">
                <form action="" class="w-[80%] p-2" @submit.prevent="addMajor">
                    <div class="lg:flex lg:justify-between">
                        <label for="name">Major's name</label>
                        <InputText v-model="name"></InputText>
                    </div>
                    <div class="flex justify-between mt-2">
                        <Button @click="closeDialog" severity="danger">Close</Button>
                        <Button class="" type="submit">Submit</Button>
                    </div>
                </form>

            </div>
       </Dialog> -->
       <Dialog v-model:visible="visible" modal header="Add Major" :style="{ width: '40vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-full flex justify-center items-center">
                <form action="" @submit.prevent="addMajor">
                    <div class="lg:justify-between items-center mb-4">
                        <label for="name" class="text-lg mr-2 mb-2">Major</label>
                        <input type="text" v-model="name" class="mt-1 p-2 w-full border rounded">
                    </div>
                    <!-- <div class="flex justify-end">
                        <Button @click="closeDialog" severity="danger" class="mr-2">Close</Button>
                        <Button type="submit">Submit</Button>
                    </div> -->
                    <div class="flex justify-between mt-6">
                        <button v-on:click="closeDialog" class="w-32 bg-red-400 mr-2 text-white border border-2 hover:bg-red-700" >Cancel</button>
                        <button type="submit" class="w-32 py-2  bg-blue-400 text-white hover:bg-blue-700" >Save</button>
                    </div>
                </form>
            </div>
            <template #header>
                <div class="text-center border-b border-gray-300 w-full pb-4">
                <h2 class="text-lg font-bold">Add Major</h2>
                </div>
            </template>
        </Dialog>



       <div class="card flex justify-content-center">

    </div>


    <!-- <OverlayPanel class="" ref="op" >
        <div class=" grid grid-rows-2 gap-2">
            <p>This is id {{ itemID }}</p>
            <Button label="Edit"  class="" text raised />
            <Button label="Delete" @click="confirmDelete(itemID)" outlined severity="danger"  text raised />
        </div>
    </OverlayPanel> -->
    <OverlayPanel ref="op">
        <div class="w-40 flex flex-col items-start">
            <button class="w-full p-3 text-blue-500 flex items-center justify-start hover:bg-gray-200 focus:outline-none" >
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
            majors: [],
            major: [],
            visible: false,
            name: '',
            isDropdownVisible: false,
            id: null,
            visibleDelete: false,



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
                this.$toast.add({ severity: 'error', summary: 'Failed to add ', detail: er, life: 3000 });
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
                this.$toast.add({ severity: 'error', summary: 'Failed to Delete ', detail: er, life: 3000 });
            })
        },
        editMajor(){

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
        closeDialog(){
            this.visible = false;
        },
        confirmDelete(id){
            // this.visibleDelete= true
            // console.log('here is data')
            // this.getByID(id)
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
