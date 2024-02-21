<template>

    <div class="flex items-center mb-4 ml-4">
        <h1 v-if="majorID==null" class="text-custom-color-small font-istok text-4xl font-bold">All Course</h1>
        <button class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" >
            <span class="flex items-center">
            <i class="fa-solid fa-circle-plus mr-2"></i>
            Add Course
            </span>
        </button>
        <!-- <Dropdown v-model="selectedTerm" :options="terms" optionValue="id" optionLabel="name" placeholder="Select a term" class="w-full md:w-14rem" /> -->
    </div>
    <p class="mb-4 ml-4">All Courses:</p>
    <div v-for="Course in courses" :key="Course.id">
        <p>{{ Course.name }}</p>

    </div>
    <div>
        <TabMenu :model="majorTabs" @onChange="handleTabChange" />
        <DataTable :value="selectedMajor ? getCourseByMajorID(selectedMajor.id) : courses" :paginator="true">
            <Column field="id" header="ID"></Column>
            <Column field="name" header="Name"></Column>
       </DataTable>
    </div>



</template>
<script>
import axios from 'axios';
export default{
    data(){
        return{
            termID: null,
            genID: null,
            majorID: null,
            termName: null,
            GenName: null,
            majorName: null,
            courses: [],
            majors: [],
            majorTabs: [],
            selectedMajor: null,

        }
    },
    mounted(){
        this.termID = this.$route.params.termId;
        this.genID = this.$route.params.genid;
        this.majorID = this.$route.params.majorId;
        this.majorName = this.$route.params.name;
        this.getCourse();
        this.getMajorName();


    },
    methods:{
        getCourse(){
            axios.get('courses').then(
                res=>{
                    this.courses= res.data
                    console.log(this.courses)
                }
            ).catch(er=>{
                console.error(er)
            })

        },
        getMajorName(){
            axios.get('majors').then(
                res=>{
                    this.majors = res.data
                    this.majorTabs = this.majors.map((major) => ({ label: major.name, icon: 'pi pi-users', major }));
                }
            )

        },
        getCourseByMajorID(majorId){
            axios.get(`course/${majorId}`).then(
                res=>{
                    this.courses = res.data.data
                    return this.courses.filter((course) => course.major_id === majorId);

                }
            )

        },

        getTermID(id){
            axios.get(`terms/${id}`).then(
                res=>{
                    this.termName = res.data.name
                }
            )

        },
        handleTabChange(event) {
            this.selectedMajor = event.item.major;
            console.log(this.selectedMajor)
        },



    }

}

</script>
