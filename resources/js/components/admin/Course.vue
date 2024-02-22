<template>

    <div class="flex items-center mb-4 ml-4">
        <h1  class="text-custom-color-small font-istok text-4xl font-bold">All Course</h1>
        <button class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" >
            <span class="flex items-center">
            <i class="fa-solid fa-circle-plus mr-2"></i>
            Add Course
            </span>
        </button>
        <!-- <Dropdown v-model="selectedTerm" :options="terms" optionValue="id" optionLabel="name" placeholder="Select a term" class="w-full md:w-14rem" /> -->
    </div>

    <div>

        <TabMenu :model="majorTabs" @onChange="handleTabChange" />
        <DataTable :value="filteredCourses">
            <Column field="id" header="ID"></Column>
            <Column field="name" header="Name"></Column>
            <Column field="term.name" header="Term"></Column>
         
            <Column field="duration" header="Duration"></Column>
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
            termName: null,
            majorId: null,
            GenName: null,
            majorName: null,
            courses: [],
            majors: [],
            majorTabs: [],
            selectedMajor: null,
            filteredCourses: [],

        }
    },
    mounted(){
        this.termID = this.$route.params.termId;
        this.genID = this.$route.params.genid;
        this.majorName = this.$route.params.name;
        this.getCourse();
        this.getMajorName();

    },
    methods:{
        getCourse() {
        axios.get('courses')
            .then((res) => {
            this.courses = res.data.data;
            this.filteredCourses = this.courses; // Directly assign the courses array
            console.log(this.filteredCourses);
            })
            .catch((error) => {
            console.error('Error fetching courses:', error);
            });
        }
        ,
        getMajorName(){
            axios.get('majors').then(
                (res) => {
                    this.majors = res.data;
                    // Add an option for all courses
                    this.majorTabs = [
                        ...this.majors.map((major) => ({ label: major.name, icon: 'pi pi-users', major })),
                        { label: 'All Courses', icon: 'pi pi-users', major: null },
                    ];
            })
            .catch((er) => {
            console.error(er);
            });

        },
        getCourseByMajorID(majorId){
            if (majorId) {
                axios
                .get(`courseMajor/${majorId}`) // Adjust according to your API endpoint
                .then((res) => {
                    this.filteredCourses = res.data.data; // Update filteredCourses with filtered data
                })
                .catch((error) => {
                    console.error('Error fetching courses by major ID:', error);
                });
            } else {
                // Return all courses when the "All Courses" tab is selected
                this.filteredCourses = this.courses;
            }
        },


        handleTabChange(event) {
        console.log('handleTabChange called');
        this.selectedMajor = event.item.major;
        this.getCourseByMajorID(this.selectedMajor ? this.selectedMajor.id : null);
        },



    }

}

</script>
