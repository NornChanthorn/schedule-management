<template>
    <div class="flex items-center mb-4 ml-4">
        <h1  class="text-custom-color-small font-istok text-4xl font-bold">All Course</h1>
        <button @click="openDialog" class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" >
            <span class="flex items-center">
            <i class="fa-solid fa-circle-plus mr-2"></i>
            Add Course
            </span>
        </button>
    </div>
    <TabMenu :model="majorTabs" @tabChange="handleTabChange" />
    <DataTable :value="tableData" dataKey="id" :resizableColumns="true" columnResizeMode="expand"
            showGridlines :paginator="true" :rows="10"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 25, 50 , 100]"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Courses" responsiveLayout="scroll">
            <Column field="id" header="ID"></Column>
            <Column field="name" header="Name"></Column>
            <Column field="teacherName" header="Teacher"></Column>
            <Column field="termName" header="Term"></Column>
            <Column field="genName" header="Generation"></Column>
    </DataTable>
    <Dialog v-model:visible="visible" modal  :style="{ width: '40vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="w-full flex justify-center items-center">
            <form action="" @submit.prevent="">
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Name</label>
                    <input type="text" v-model="addCourse.name" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 " placeholder="Name of Major">
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Teacher</label>

                    <select v-model="addCourse.teacher_id" class="  mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200">
                        <option value="" disabled >Choose Teacher  </option>
                        <option  v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{ teacher.name }}</option>
                    </select>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label class="text-lg mr-2 mb-2" for="major">Major</label>
                    <select v-model="addCourse.major_id" class="mt-1 p-2 w-full border rounded   outline  outline-slate-200 py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200">
                        <option value="" disabled>Choose Major</option>
                        <option v-for="major in majors" :key="major.id" :value="major.id">{{ major.name }}</option>
                                    <!-- <option value="2">Ecommerce</option> -->
                    </select>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Generation</label>
                    <!-- <input type="text" v-model="addCourse.gen_id" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 " placeholder="Name of Major"> -->
                    <select v-model="addCourse.gen_id" class="mt-1 p-2 w-full border rounded   outline  outline-slate-200 py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200">
                        <option value="" disabled >Choose Generation</option>
                        <option  v-for="gen in generations" :key="gen.id" :value="gen.id">{{ gen.gen }}</option>
                    </select>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Term</label>
                    <!-- <input type="text" v-model="addCourse.term_id" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 " placeholder="Name of Major"> -->
                    <select v-model="addCourse.term_id" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200">
                        <option value="" disabled >Choose Term</option>
                        <option  v-for="term in terms" :key="term.id" :value="term.id">{{ term.name }}</option>
                    </select>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Duration</label>
                    <input type="text" v-model="addCourse.duration" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 " placeholder="Duration (Ex: 45 hours)">
                </div>
                <div class="flex justify-between mt-6">
                    <button v-on:click="this.visible==false" class="w-32 bg-red-500 mr-2 text-white border-2  hover:bg-red-700" >Cancel</button>
                    <button type="submit" class="w-32 py-2  bg-blue-500 border-2 text-white hover:bg-blue-700" >Save</button>
                </div>
            </form>
        </div>
        <template #header>
            <div class="text-center border-b border-gray-300 w-full pb-4">
               <h2 class="text-lg font-bold">Add Course</h2>
            </div>
        </template>
    </Dialog>




</template>
<script>
import axios from 'axios';
import { FilterMatchMode } from "primevue/api";
import { ref } from "vue";
export default{
    data(){
        return{
            courses: [],
            majors: [],
            generations: [],
            teachers: [],
            terms: [],
            majorTabs: [
                { label: 'All Courses', icon: 'pi pi-book', major: null },
            ],
            selectedMajor: null,
            filteredCourses: [],
            selectedTabId: 0,
            tableData: [],
            totalRecords: 0,
            visible: false,
            addCourse:{
                name: '',
                teacher_id: '',
                term_id: '',
                gen_id: '',
                major_id: '',
                duration: ''
            }
        }
    },
    mounted(){
        this.getMajorName();
        this.getCourse();
        this.getGeneration();
        this.getTeachers();
        this.getTerms();

    },
    methods:{
        async getCourse() {
            // axios.get('courses')
            //     .then((res) => {
            //     this.tableData = res.data.data;
            //     this.termName = this.tableData.term.name
            //     })
            //     .catch((error) => {
            //     console.error('Error fetching courses:', error);
            //     });
            try {
                const response = await axios.get('courses');
                const coursesWithTermNames = response.data.data.map((course) => {
                const termName = course.term?.name;
                const teacherFName = course.teacher?.f_name;
                const teacherLName = course.teacher?.l_name;
                const teacherName = teacherFName +' '+ teacherLName;
                const genName = course.generation?.gen;
                return {
                    ...course,
                    termName,
                    teacherName,
                    genName
                };
                });
                this.tableData = coursesWithTermNames;
            } catch (error) {
                console.error('Error fetching courses:', error);
                // Handle errors appropriately (e.g., display an error message to the user)
            }
        },
        async getGeneration(){
            axios.get('generations').then(
                res=>{
                    this.generations = res.data
                }

            )
        },
        async getTeachers(){
            axios.get('teachers').then(
                res=>{
                    // this.teachers = res.data
                    this.teachers = res.data.map((teacher) => {
                        const l_name = teacher?.l_name;
                        const f_name = teacher?.f_name;
                        const name = f_name + ' ' + l_name;
                        return {
                            name
                        };
                    })
                }
            )
        },
        async getTerms(){
            axios.get('terms').then(
                res=>{
                    this.terms = res.data
                }
            )
        },
        async getMajorName(){
            try {
                const response = await axios.get('majors');
                this.majors = response.data;
                this.majorTabs.push(...this.majors.map((major) => ({
                label: major.name,
                icon: 'pi pi-book',
                major,
                })));
            } catch (error) {
                console.error('Error fetching majors:', error);
            }
        },
        async handleTabChange(newTab) {
            this.selectedTabId = newTab.index;
            this.tableData = []; // Clear previous data
            try {
                const response = await axios.get(this.selectedTabId != 0 ? `courseMajor/${this.selectedTabId}` : `courses`); // Adjust for your API endpoint
                // this.tableData = response.data.data;
                this.tableData = response.data.data.map((course) => {
                const termName = course.term?.name;
                const teacherFName = course.teacher?.f_name;
                const teacherLName = course.teacher?.l_name;
                const teacherName = teacherFName +' '+ teacherLName;
                const genName = course.generation?.gen;
                return {
                    ...course,
                    termName,
                    teacherName,
                    genName
                };
                });
                this.tableData = coursesWithTermNames;

                this.totalRecords = response.headers['x-total-count']; // Assume API provides total count
            } catch (error) {
                console.error('Error fetching courses:', error);
                // Handle errors appropriately
            } finally {
                this.loading = false;
            }

        },
        openDialog(){
            this.visible = true;
        }


    },
    setup(){
        const filters = ref({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        });

    }

}

</script>
