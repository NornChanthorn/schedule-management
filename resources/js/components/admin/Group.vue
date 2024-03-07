<template>
    <Toast/>
    <div class="lg:flex lg:justify-between items-center mb-4 ml-4 md:inline">
        <h1  class="text-custom-color-small font-istok text-4xl font-bold">Course List</h1>
        <div class="flex justify-evenly items-center lg:w-[32%] md:w-[80%] md:mt-2 sm:mt-8">
            <div class="relative flex items-center">
                <input type="text" v-model="filters['global'].value" class="border border-blue-300  rounded-lg px-3 py-2 focus:outline-blue-300 focus:outline-2 w-full" placeholder="Search ">
                <button type="button" class="absolute right-3 top-3 disabled ">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"></path></svg>
                </button>
            </div>
            <button @click="openDialog" class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" >
                <span class="flex items-center">
                    <i class="fa-solid fa-circle-plus mr-2"></i>
                    Add Group
                </span>
            </button>
        </div>

    </div>

    <TabMenu :model="majorTabs" @tabChange="handleTabChange" />
    <DataTable  :value="tableData"  dataKey="id" :resizableColumns="true" columnResizeMode="expand"
            :paginator="true" :rows="10"
            :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 25, 50 , 100]"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Group" responsiveLayout="scroll">
            <Column field="id" header="ID" :headerStyle="{ 'text-align': 'center' , 'font-size': '13px'}"></Column>
            <Column field="group_name" header="NAME" :headerStyle="{ 'text-align': 'center' , 'font-size': '13px'}" :bodyStyle="{ 'text-align': 'start' }"></Column>
            <Column field="genName" header="GENERATION" :headerStyle="{ 'text-align': 'center' , 'font-size': '13px'}" :bodyStyle="{ 'text-align': 'start' }"></Column>
            <Column  style="width:15%;  min-width:8rem; " header="ACTION" :headerStyle="{ 'text-align': 'center' , 'font-size': '13px'}" :bodyStyle="{ 'text-align': 'start' }" >
                <template #body="slotProps">
                    <div class="flex justify-between items-start w-[60%]">
                        <button @click="openEdit(slotProps.data)" class="text-green-600 hover:text-green-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button @click="confirmDelete(slotProps.data)" class="text-red-600 hover:text-red-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </template>
            </Column>
    </DataTable>

    <!-- add  -->
    <Dialog v-model:visible="visible" modal  :style="{ width: '40vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="w-full flex justify-center items-center">
            <form action="" @submit.prevent="addNewGroup">
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Group Name</label>
                    <input type="text" v-model="addGroup.group_name" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 " placeholder="Name of group"
                     :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                     : submitted && !addGroup.group_name }">
                     <small class="p-error " v-if="submitted && !addGroup.group_name">Name is required</small>
                </div>

                <div class="lg:justify-between items-center mb-4">
                    <label class="text-lg mr-2 mb-2" for="major">Major</label>
                    <select v-model="addGroup.major_id" class="mt-1 p-2 w-full border rounded   outline  outline-slate-200 py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                    :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                    : submitted && !addGroup.major_id }">
                        <option value="" disabled>Choose Major</option>
                        <option v-for="major in majors" :key="major.id" :value="major.id">{{ major.name }}</option>
                    </select>
                    <small class="p-error " v-if="submitted && !addGroup.major_id">Major is required</small>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Generation</label>
                    <select v-model="addGroup.gen_id" class="mt-1 p-2 w-full border rounded   outline  outline-slate-200 py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                    :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                    : submitted && !addGroup.gen_id }">
                        <option value="" disabled >Choose Generation</option>
                        <option  v-for="gen in generations" :key="gen.id" :value="gen.id">{{ gen.gen }}</option>
                    </select>
                    <small class="p-error " v-if="submitted && !addGroup.gen_id">Generation is required</small>
                </div>
                <div class="flex justify-between mt-6">
                    <button v-on:click="closeDialog" class="w-32 bg-red-500 mr-2 text-white border-2  hover:bg-red-700" >Cancel</button>
                    <button type="submit" class="w-32 py-2  bg-blue-500 border-2 text-white hover:bg-blue-700" >Save</button>
                </div>
            </form>
        </div>
        <template #header>
            <div class="text-center border-b border-gray-300 w-full pb-4">
               <h2 class="text-lg font-bold">Add Student Group</h2>
            </div>
        </template>
    </Dialog>
    <!-- Edit  -->
    <Dialog v-model:visible="visibleEdit" modal  :style="{ width: '40vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="w-full flex justify-center items-center">
             <form action="" @submit.prevent="editGroup(group.id)">
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Name</label>
                    <input type="text" v-model.trim="group.group_name" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 "
                     :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                     : submitted && !group.group_name }" />
                    <small class="p-error" v-if="submitted && !group.group_name">Group is required</small>
                </div>

                <div class="lg:justify-between items-center mb-4">
                    <label class="text-lg mr-2 mb-2" for="major">Major</label>
                    <select v-model.trim="group.major_id" class="mt-1 p-2 w-full border rounded   outline  outline-slate-200 py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                        :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                        : submitted && !group.major_id }">
                        <option value="" disabled>Choose Major</option>
                        <option v-for="major in majors" :key="major.id" :value="major.id">{{ major.name }}</option>
                    </select>
                    <small class="p-error " v-if="submitted && !group.major_id">Major is required</small>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Generation</label>
                    <select v-model.trim="group.gen_id" class="mt-1 p-2 w-full border rounded   outline  outline-slate-200 py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                        :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                        : submitted && !group.gen_id }">
                        <option value="" disabled >Choose Generation</option>
                        <option  v-for="gen in generations" :key="gen.id" :value="gen.id">{{ gen.gen }}</option>
                    </select>
                    <small class="p-error " v-if="submitted && !group.gen_id">Generation is required</small>
                </div>
                <div class="flex justify-between mt-6">
                    <button @click="closeEditDialog" class="w-32 bg-red-500 mr-2 text-white border-2  hover:bg-red-700" >Cancel</button>
                    <button type="submit" class="w-32 py-2  bg-blue-500 border-2 text-white hover:bg-blue-700" >Save</button>
                </div>
            </form>
        </div>
        <template #header>
            <div class="text-center border-b border-gray-300 w-full pb-4">
               <h2 class="text-lg font-bold">Edit Group</h2>
            </div>
        </template>
    </Dialog>




</template>
<script>
import axios from 'axios';
import { FilterMatchMode } from "primevue/api";
import { ref } from "vue";
import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'
export default{
    data(){
        return{
            groups: [],
            majors: [],
            generations: [],
            majorTabs: [
                { label: 'All Group', icon: 'pi pi-book', major: null },
            ],
            selectedMajor: null,
            selectedTabId: 0,
            tableData: [],
            totalRecords: 0,
            visible: false,
            addGroup:{
                group_name: '',
                gen_id: '',
                major_id: '',
            },
            group: [],
            deleteDialog: false,
            visibleEdit: false,
            submitted: false,
            filters: {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS }
            }
        }
    },
    mounted(){
        this.getMajorName();
        this.getGroup();
        this.getGeneration();

    },
    methods:{
        async getGroup() {
            try {
                const response = await axios.get('groups');
                const groupWithGen = response.data.data.map((group) => {
                const majorName= group.major?.name;
                const genName = group.generation?.gen;
                return {
                    ...group,
                    majorName,
                    genName
                };
                });
                this.tableData = groupWithGen;
            } catch (error) {
                console.error('Error fetching courses:', error);
            }
        },
        async getGeneration(){
            axios.get('generations').then(
                res=>{
                    this.generations = res.data
                }

            )
        },

        async getMajorName(){
            try {
                const response = await axios.get('majors');
                this.majors = response.data;
                console.log(this.majors)
                this.majorTabs.push(...this.majors.map((major) => ({
                label: major.name,
                icon: 'pi pi-book',
                major,
                })));
                console.log(this.majorTabs)
            } catch (error) {
                console.error('Error fetching majors:', error);
            }
        },
        async handleTabChange(newTab) {
            this.selectedTabId = newTab.index;
            this.tableData = [];
            try {
                const response = await axios.get(this.selectedTabId != 0 ? `groupsByMajor/${this.selectedTabId}` : `groups`);
                const groupWithGen = response.data.data.map((group) => {
                const majorName = group.major?.name;
                const genName = group.generation?.gen;
                return {
                    ...group,
                    majorName,
                    genName

                };
                });
                this.tableData = groupWithGen;

                this.totalRecords = response.headers['x-total-count'];
            } catch (error) {
                console.error('Error fetching courses:', error);
            } finally {
                this.loading = false;
            }

        },
        addNewGroup(){
            // console.log(this.groups)
            // if(this.addGroup.group_name == this.groups.group_name && this.addGroup.gen_id == this.groups.gen_id && this.addGroup.major_id== this.groups.major_id){
            //         console.log('this course is duplicated')
            // }else{
            //     console.log('It okay')
            // }
            this.submitted= true
            axios.post('groups', this.addGroup).then(
                res=>{
                    console.log('success??');
                    this.getGroup();
                    this.visible = false
                    this.$toast.add({ severity: 'success', summary: 'Add Successfully', detail: 'Add Successfully', life: 3000 });
                }
            ).catch(er=>{
                console.error(er)
                
            })

        },
        editGroup(id){
            this.submitted= true
            axios.put(`groups/${id}`, this.group).then(
                res=>{
                    this.getGroup();
                    this.visibleEdit = false
                    this.$toast.add({ severity: 'success', summary: 'Edit Successfully', detail: 'Edit Successfully', life: 3000 });
                }
            )
        },
        openDialog(){
            this.visible = true;
        },
        closeDialog(){
            this.visible= false;
        },
        confirmDelete(prod){
            Swal.fire({
            title: 'Are you sure?',
            text: `${prod.group_name} will remove from your system`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.deleteGroup(prod.id);
                this.deleteDialog = false;
                this.$toast.add({ severity: 'success', summary: 'Delete Successfully', detail: 'Group deleted Successfully', life: 3000 });
            }
            else {
                console.log('Deletion canceled');
                // this.$toast.add({ severity: 'error', summary: 'Fail delete', detail: 'Faileddd', life: 3000 });
            }
        });
        },
        deleteGroup(id){
            axios.delete(`groups/${id}`).then(
                res=>{
                    this.getGroup();
                }
            ).catch(er=>{
                console.error(er)
            })
        },
        openEdit(prod){
            this.group = prod
            this.visibleEdit = true
        },
        closeEditDialog(){
            this.visibleEdit= false
            this.group = null
        }


    },
    setup(){
        const filters = ref({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        });

    }

}

</script>
<style>

</style>
