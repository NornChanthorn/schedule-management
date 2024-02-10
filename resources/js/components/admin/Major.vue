<template>
    <Toast/>
      <div class="w-[100%] p-6">
        <h1 class=" text-start ml-7 text-2xl text-stone-400">Overview</h1>
      </div>
       <div class=" p-6 grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 lg:gap-4 md:gap-3 sm:gap-2 justify-center">
            <div class="lg:w-[90%] md:w-[80%] sm:w-[70%] rounded-xl shadow-md border-2 flex justify-center items-center outline-emerald-50 pi pi-add">
                <Button class="bg-transparent hover:bg-transparent text-blue-900 pi pi-plus" @click="showDialog">Add </Button>
            </div>
            <div v-for="major in majors" :key="major?.id" class=" lg:w-[90%] md:w-[80%] sm:w-[70%] rounded-xl shadow-md border-2 " >
                <router-link :to="{ name: 'Generation', params: { name: major.name } }">
                    <div class=" flex justify-center items-center">
                        <img src="../../../../public/img/major-logo.jpg" alt="" class="  ">
                    </div>
                    <div class=" flex justify-between p-2">
                        <h3 class=" text-lg">{{ major.name}}</h3>
                        <h1 class=" pi pi-ellipsis-v p-2"></h1>
                    </div>
                </router-link>
            </div>
       </div>
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


</template>
<script>
import axios from 'axios';
import { useToast } from "primevue/usetoast";
export default{
    data(){
        return{
            majors: [],
            visible: false,
            name: '',
            toast: useToast()

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
                    toast.add({ severity: 'success', summary: 'Add Successfully', detail: 'New Major added', life: 3000 });
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
                    console.log(res.message)
                    this.getMajors()
                }
            ).catch(er=>{
                console.error(er)
            } )
        },
        showDialog(){
            this.visible=true;
        },
        closeDialog(){
            this.visible = false;
        },

    }

}
</script>
