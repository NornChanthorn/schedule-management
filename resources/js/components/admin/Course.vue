<template>
    <div class="flex items-center mb-4 ml-4">
        <h1  class="text-custom-color-small font-istok text-4xl font-bold">All Course</h1>
        <button class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" >
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
            <Column field="term.name" header="Term"></Column>

        </DataTable>
        <!-- <Paginator :first="1" :totalRecords="totalRecords" :rows="5" @change="handlePageChange" /> -->

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
            majorTabs: [
                { label: 'All Courses', icon: 'pi pi-book', major: null },
            ],
            selectedMajor: null,
            filteredCourses: [],
            selectedTabId: 0,
            tableData: [],
            totalRecords: 0,
        }
    },
    mounted(){
        this.getMajorName();
        this.getCourse();

    },
    methods:{
        getCourse() {
            axios.get('courses')
                .then((res) => {
                this.tableData = res.data.data;
                })
                .catch((error) => {
                console.error('Error fetching courses:', error);
                });
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
                this.tableData = response.data.data;

                this.totalRecords = response.headers['x-total-count']; // Assume API provides total count
            } catch (error) {
                console.error('Error fetching courses:', error);
                // Handle errors appropriately
            } finally {
                this.loading = false;
            }

        },

        handlePageChange(event) {
        // Update data based on new page number
        this.fetchData(event.page + 1); // Adjust for 0-based indexing if needed
        },
    },
    setup(){

        const filters = ref({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        });

    }

}

</script>
