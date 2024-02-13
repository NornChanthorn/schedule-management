<template>
    <Toast/>
      <div class="w-[100%] p-6">

        <h1 class=" text-start ml-7 text-2xl text-slate-400">Overview</h1>
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
       </div>

       <Dialog v-model:visible="visibleDelete" header="Confirm">
          <div class="">
            <h1>Are you sure to delete {{ major.name }} ?</h1>
            <div class="flex justify-between">
                <Button label="No" severity="danger" @click="visibleDelete= false" ></Button>
                <Button label="Yes"  @click="deleteMajor(major.id)"></Button>
            </div>

          </div>
       </Dialog>


       <Dialog v-model:visible="visible" modal header="Add Major"  :style="{ width: '50vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" >
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
            <!-- <form action="" class="bg-red-200 w-[100%]">
                <div>

                </div>

                <div class="flex justify-between ">
                    <Button @click="closeDialog" severity="danger">Close</Button>
                    <Button @click="closeDialog" class="">Submit</Button>
                </div>
            </form> -->
       </Dialog>
       <div class="card flex justify-content-center">

    </div>


    <OverlayPanel ref="op" >
        <div class=" grid grid-rows-2 gap-2">
            <!-- <p>This is id {{ itemID }}</p> -->
            <Button label="Edit"  class="" text raised />
            <Button label="Delete" @click="confirmDelete(itemID)" outlined severity="danger"  text raised />
        </div>
    </OverlayPanel>

</template>

<script>
import axios from 'axios';
import { ref } from 'vue';
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
            this.visibleDelete= true
            console.log('here is data')
            this.getByID(id)
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
