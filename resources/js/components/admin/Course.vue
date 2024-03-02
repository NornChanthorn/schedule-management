<template>
    <Toast/>
    <div class="flex items-center mb-4 ml-4">
        <h1  class="text-custom-color-small font-istok text-4xl font-bold">Course</h1>
        <button @click="openDialog" class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" >
            <span class="flex items-center">
            <i class="fa-solid fa-circle-plus mr-2"></i>
            Add Course
            </span>
        </button>
    </div>
    <TabMenu :model="majorTabs" @tabChange="handleTabChange" class="inline"/>
    <DataTable :value="tableData" dataKey="id" :resizableColumns="true" columnResizeMode="expand"
            :paginator="true" :rows="10"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 25, 50 , 100]"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Courses" responsiveLayout="scroll">
            <Column field="id" header="ID"></Column>
            <Column field="name" header="Name"></Column>
            <Column field="teacherName" header="Teacher"></Column>
            <Column field="termName" header="Term"></Column>
            <Column field="genName" header="Generation"></Column>
            <Column  style="width:15%;  min-width:8rem; " header="Action" bodyStyle="text-align:center" class=" space-x-2  ">
                <template #body="slotProps">
                    <div class="flex justify-evenly">
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
                        <!-- <Button icon="pi pi-pencil" label="Edit" class=" p-button-success mr-2 p-button-sm "  @click="editData(slotProps.data)" />
                        <Button icon="pi pi-trash" label="Delete" severity="danger" class=" p-button-sm" @click="confirmDelete(slotProps.data)" /> -->
                    </div>
                </template>
            </Column>
    </DataTable>

    <!-- add  -->
    <Dialog v-model:visible="visible" modal  :style="{ width: '40vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="w-full flex justify-center items-center">
            <form action="" @submit.prevent="addNewCourse">
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Name</label>
                    <input type="text" v-model="addCourse.name" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 " placeholder="Name of course"
                     :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                     : submitted && !addCourse.name }">
                     <small class="p-error " v-if="submitted && !addCourse.name">Course is required</small>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Teacher</label>
                    <select v-model="addCourse.teacher_id" class="  mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                    :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                    : submitted && !addCourse.teacher_id }">
                        <option value="" disabled >Choose Teacher  </option>
                        <option  v-for="teacher in teachers" :key="teacher.id" :value="teacher.teacherID">{{ teacher.name }}</option>
                    </select>
                    <small class="p-error" v-if="submitted && !addCourse.teacher_id">Teacher is required</small>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label class="text-lg mr-2 mb-2" for="major">Major</label>
                    <select v-model="addCourse.major_id" class="mt-1 p-2 w-full border rounded   outline  outline-slate-200 py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                    :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                    : submitted && !addCourse.major_id }">
                        <option value="" disabled>Choose Major</option>
                        <option v-for="major in majors" :key="major.id" :value="major.id">{{ major.name }}</option>
                    </select>
                    <small class="p-error " v-if="submitted && !addCourse.major_id">Major is required</small>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Generation</label>
                    <select v-model="addCourse.gen_id" class="mt-1 p-2 w-full border rounded   outline  outline-slate-200 py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                    :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                    : submitted && !addCourse.gen_id }">
                        <option value="" disabled >Choose Generation</option>
                        <option  v-for="gen in generations" :key="gen.id" :value="gen.id">{{ gen.gen }}</option>
                    </select>
                    <small class="p-error " v-if="submitted && !addCourse.gen_id">Generation is required</small>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Term</label>
                    <select v-model="addCourse.term_id" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                     :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                     : submitted && !addCourse.term_id }">
                        <option value="" disabled >Choose Term</option>
                        <option  v-for="term in terms" :key="term.id" :value="term.id">{{ term.name }}</option>
                    </select>
                    <small class="p-error " v-if="submitted && !addCourse.term_id">Term is required</small>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Duration</label>
                    <input type="text" v-model="addCourse.duration" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 " placeholder="Duration (Ex: 45 hours)"
                     :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                     : submitted && !addCourse.duration }">
                    <small class="p-error " v-if="submitted && !addCourse.duration">Duration is required</small>
                </div>
                <div class="flex justify-between mt-6">
                    <button v-on:click="closeDialog" class="w-32 bg-red-500 mr-2 text-white border-2  hover:bg-red-700" >Cancel</button>
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
    <!-- Edit  -->
    <Dialog v-model:visible="visibleEdit" modal  :style="{ width: '40vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="w-full flex justify-center items-center">
             <form action="" @submit.prevent="editCourse(course.id)">
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Name</label>
                    <input type="text" v-model.trim="course.name" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 " placeholder="Name of Course"
                     :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                     : submitted && !course.name }" />
                    <small class="p-error" v-if="submitted && !course.name">Course is required</small>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Teacher</label>
                    <select v-model.trim="course.teacher_id" class="  mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                        :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                        : submitted && !course.teacher_id }">
                        <option value="" disabled >Choose Teacher  </option>
                        <option  v-for="teacher in teachers" :key="teacher.id" :value="teacher.teacherID">{{ teacher.name }}</option>
                    </select>
                    <small class="p-error " v-if="submitted && !course.teacher_id">Teacher is required</small>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label class="text-lg mr-2 mb-2" for="major">Major</label>
                    <select v-model.trim="course.major_id" class="mt-1 p-2 w-full border rounded   outline  outline-slate-200 py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                        :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                        : submitted && !course.major_id }">
                        <option value="" disabled>Choose Major</option>
                        <option v-for="major in majors" :key="major.id" :value="major.id">{{ major.name }}</option>
                    </select>
                    <small class="p-error " v-if="submitted && !course.major_id">Major is required</small>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Generation</label>
                    <select v-model.trim="course.gen_id" class="mt-1 p-2 w-full border rounded   outline  outline-slate-200 py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                        :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                        : submitted && !course.gen_id }">
                        <option value="" disabled >Choose Generation</option>
                        <option  v-for="gen in generations" :key="gen.id" :value="gen.id">{{ gen.gen }}</option>
                    </select>
                    <small class="p-error " v-if="submitted && !course.gen_id">Generation is required</small>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Term</label>
                    <select v-model.trim="course.term_id" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                        :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                        : submitted && !course.term_id }">
                        <option value="" disabled >Choose Term</option>
                        <option  v-for="term in terms" :key="term.id" :value="term.id">{{ term.name }}</option>
                    </select>
                    <small class="p-error " v-if="submitted && !course.term_id">Term is required</small>
                </div>
                <div class="lg:justify-between items-center mb-4">
                    <label for="name" class="text-lg mr-2 mb-2">Duration</label>
                    <input type="text" v-model.trim="course.duration" class="mt-1 p-2 w-full border rounded outline  outline-slate-200   py-2 px-3  leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200 " placeholder="Duration (Ex: 45 hours)"
                        :class="{ ' border-red-500  outline outline-red-200 hover:outline-red-200'
                        : submitted && !course.duration }">
                    <small class="p-error " v-if="submitted && !course.duration">Duration is required</small>
                </div>
                <div class="flex justify-between mt-6">
                    <button @click="closeEditDialog" class="w-32 bg-red-500 mr-2 text-white border-2  hover:bg-red-700" >Cancel</button>
                    <button type="submit" class="w-32 py-2  bg-blue-500 border-2 text-white hover:bg-blue-700" >Save</button>
                </div>
            </form>
        </div>
        <template #header>
            <div class="text-center border-b border-gray-300 w-full pb-4">
               <h2 class="text-lg font-bold">Edit Course</h2>
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
            },
            course: [],
            deleteDialog: false,
            visibleEdit: false,
            submitted: false
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
                        const teacherID = teacher?.id;
                        return {
                            name,
                            teacherID
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
            this.tableData = [];
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
            } finally {
                this.loading = false;
            }

        },
        addNewCourse(){
            this.submitted= true
            axios.post('courses', this.addCourse).then(
                res=>{
                    console.log('success??');
                    this.getCourse();
                    this.visible = false
                    this.$toast.add({ severity: 'success', summary: 'Add Successfully', detail: 'Add Successfully', life: 3000 });
                }
            ).catch(er=>{
                console.error(er)
            })

        },
        editCourse(id){
            this.submitted= true
            axios.put(`courses/${id}`, this.course).then(
                res=>{
                    this.getCourse();
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
            text: `${prod.name} will remove from your system`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.deleteCourse(prod.id);
                this.deleteDialog = false;
                this.$toast.add({ severity: 'success', summary: 'Delete Successfully', detail: 'Course deleted Successfully', life: 3000 });
            }
            else {
                console.log('Deletion canceled');
                // this.$toast.add({ severity: 'error', summary: 'Fail delete', detail: 'Faileddd', life: 3000 });
            }
        });
        },
        deleteCourse(id){
            axios.delete(`courses/${id}`).then(
                res=>{
                    this.getCourse();
                }
            ).catch(er=>{
                console.error(er)
            })
        },
        openEdit(prod){
            console.log(prod.id)
            const id = prod.id
            this.course = prod
            // axios.get(`courses/${prod.id}`)
            console.log('my id: ', id)
            this.visibleEdit = true
            return{
                id
            }
        },
        closeEditDialog(){
            this.visibleEdit= false
            this.course = null
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
