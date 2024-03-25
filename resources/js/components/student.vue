<template>
    <Toast></Toast>
    <div class="lg:flex lg:justify-between mb-4 ml-4 md:inline sm:inline">
        <h1 class="text-custom-color-small font-istok text-4xl font-bold">
            Student List
        </h1>
        <div class="flex justify-between items-center w-auto">
            <div class="relative flex items-center mr-2">
                <input type="text" v-model="filters['global'].value"
                    class="border border-blue-300 px-3 py-2 focus:outline-blue-300 focus:outline-2 w-full"
                    placeholder="Search " />
                <button type="button" class="absolute right-3 top-3 disabled">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"></path>
                    </svg>
                </button>
            </div>
            <button
                class="mr-2 bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                label="Add New" severity="secondary" @click="showModal = true">
                <span class="flex items-center">
                    <i class="fa-solid fa-circle-plus mr-2"></i>
                    Add Student
                </span>
            </button>
            <button @click="openDialog"
                class="cursor-pointer bg-blue-500 text-white hover:bg-blue-700 focus:outline-none px-4 py-2 mr-2">
                <span class="flex items-center">
                    <i class="fa-solid fa-upload mr-2"></i>
                    Import
                </span>
            </button>
            <Dialog v-model:visible="visible" modal :style="{ width: '35vw' }"
                :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <div class="w-full flex justify-center items-center"></div>
                <template #header>
                    <div class="text-center border-b border-gray-300 w-full pb-4">
                        <h2 class="text-lg font-bold">Import Students</h2>
                    </div>
                </template>
                <div class="p-4">
                    <div class="flex justify-center items-center space-x-6">
                        <button @click="exportHeaderCSV"
                            class="bg-teal-600 cursor-pointer text-white hover:bg-teal-700 focus:outline-none px-4 py-2">
                            <span class="flex items-center">Export Empty Template</span>
                        </button>
                        <input type="file" ref="fileInput" style="display: none" @change="importStudentsFromCSV"
                            accept=".csv" />
                        <button @click="importCSV"
                            class="cursor-pointer bg-blue-500 text-white hover:bg-blue-700 focus:outline-none px-4 py-2">
                            <span class="flex items-center"> Import Students Data </span>
                        </button>
                    </div>
                </div>
            </Dialog>

            <button @click="exportCSV"
                class="bg-teal-600 cursor-pointer text-white hover:bg-teal-700 focus:outline-none px-4 py-2 mr-2">
                <span class="flex items-center">
                    <i class="fa-solid fa-file-export mr-2"></i>
                    Export
                </span>
            </button>
        </div>
    </div>
    <TabMenu :model="majorTabs" @tabChange="handleTabChange" class="inline" />
    <DataTable :value="tableData" v-if="tableData" :filters="filters" dataKey="id" :resizableColumns="true"
        columnResizeMode="expand" :paginator="true" :rows="10" class="text-center"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        :rowsPerPageOptions="[5, 10, 25, 50, 100]"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Courses" responsiveLayout="scroll">
        <Column field="student_id" header="ID" :headerStyle="{ 'text-align': 'center', 'font-size': '13px' }"></Column>
        <Column field="fullName" header="NAME" :headerStyle="{ 'text-align': 'center', 'font-size': '13px' }"></Column>
        <Column field="gender" header="GENDER" :headerStyle="{ 'text-align': 'center', 'font-size': '13px' }"></Column>
        <Column field="group_name" header="GROUP" :headerStyle="{ 'text-align': 'center', 'font-size': '13px' }">
        </Column>
        <Column field="genName" header="GENERATION" :headerStyle="{ 'text-align': 'center', 'font-size': '13px' }">
        </Column>
        <Column style="width: 15%; min-width: 8rem" header="ACTION"
            :headerStyle="{ 'text-align': 'center', 'font-size': '13px' }" :bodyStyle="{ 'text-align': 'start' }">
            <template #body="slotProps">
                <div class="flex justify-between items-start w-[60%]">
                    <button @click="editPost(slotProps.data)" class="text-green-600 hover:text-green-800">
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
    <p v-if="tableData == null">Loading...</p>

    <!-- Modal for creating a new post -->
    <div v-if="showModal" class="fixed inset-0 z-10 overflow-y-auto bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white w-full max-w-lg p-6 border-2">
                <div class="mb-4 text-center">
                    <h1 class="text-2xl font-bold decoration-gray-400 border-b-2 pb-2">
                        Student Information
                    </h1>
                </div>
                <form @submit.prevent="createPost">
                    <div class="flex mb-2">
                        <div class="w-1/2 mr-2">
                            <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" v-model="newPost.f_name" name="f_name"
                                class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                                placeholder="User first name" />
                        </div>

                        <div class="w-1/2 ml-2">
                            <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" v-model="newPost.l_name" name="l_name"
                                class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                                placeholder="User last name" />
                        </div>
                    </div>
                    <div class="flex mb-2">
                        <div class="w-1/2 mr-2">
                            <label class="block text-sm font-medium text-gray-700" for="gender">Gender</label>
                            <select v-model="newPost.gender"
                                class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200">
                                <option value="" disabled>Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="w-1/2 ml-2">
                            <label class="block text-sm font-medium text-gray-700" for="dob">Date of Birth</label>
                            <input v-model="newPost.dob"
                                class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                                type="date" name="dob" placeholder="Enter dob" />
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" v-model="newPost.email" name="email"
                            class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                            placeholder="Enter Email" />
                    </div>
                    <div v-if="error" class="text-red-500 text-sm">
                        {{ error }}
                    </div>
                    <div class="flex justify-between mb-2">
                        <div class="w-[48%]">
                            <label class="block text-sm font-medium text-gray-700" for="student_id">Student ID</label>
                            <input v-model="newPost.student_id"
                                class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                                type="text" name="student_id" placeholder="Example B20210021" />
                        </div>
                        <div class="w-[48%]">
                            <label class="block text-sm font-medium text-gray-700" for="gender">Generation</label>
                            <select v-model="newPost.generation_id"
                                class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200">
                                <option value="" disabled>Select Generation</option>
                                <option v-for="gen in generations" :key="gen.id" :value="gen.id">
                                    {{ gen.gen }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between mb-2">
                        <div class="w-[48%]">
                            <label class="block text-sm font-medium text-gray-700" for="gender">Group</label>
                            <select v-model="newPost.group_id"
                                class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200">
                                <option value="" disabled>Select Group</option>
                                <option v-for="group in groups" :key="group.id" :value="group.id">
                                    {{ group.group_name }}
                                </option>
                            </select>
                        </div>
                        <div class="w-[48%]">
                            <label class="block text-sm font-medium text-gray-700" for="gender">Major</label>
                            <select v-model="newPost.major_id"
                                class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200">
                                <option value="" disabled>Select Major</option>
                                <option v-for="major in majors" :key="major.id" :value="major.id">
                                    {{ major.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-4 gap-x-2">
                        <button @click="showModal = false" type="button"
                            class="w-32 py-2 bg-red-500 text-white border-2 hover:bg-red-600">
                            Cancel
                        </button>
                        <button type="submit" class="w-32 py-2 bg-blue-500 mr-2 text-white border-2 hover:bg-blue-600">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for editing a post -->
    <div v-if="editModal" class="fixed inset-0 z-10 overflow-y-auto bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white w-full max-w-lg p-6 border border-2">
                <div class="mb-4 text-center">
                    <h1 class="text-2xl font-bold decoration-gray-400 border-b-2 pb-2">
                        Edit Student Information
                    </h1>
                </div>
                <form @submit.prevent="updatePost">
                    <div class="flex mb-2">
                        <div class="w-1/2 mr-2">
                            <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" v-model="editedPost.f_name" name="f_name"
                                class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                                placeholder="User first name" />
                        </div>

                        <div class="w-1/2 ml-2">
                            <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" v-model="editedPost.l_name" name="l_name"
                                class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                                placeholder="User last name" />
                        </div>
                    </div>
                    <div class="flex mb-2">
                        <div class="w-1/2 mr-2">
                            <label class="block text-sm font-medium text-gray-700" for="gender">Gender</label>
                            <select v-model="editedPost.gender"
                                class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200">
                                <option value="" disabled>Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="w-1/2 ml-2">
                            <label class="block text-sm font-medium text-gray-700" for="dob">Date of Birth</label>
                            <input v-model="editedPost.dob"
                                class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                                type="date" name="dob" placeholder="Enter dob" />
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" v-model="editedPost.email" name="email"
                            class="mt-1 p-2 w-full border rounded outline outline-slate-200 py-2 px-3 leading-tight focus:outline-none focus:shadow-outline focus:outline-blue-200"
                            placeholder="Enter Email" />
                    </div>
                    <div class="flex mb-2">
                        <div class="w-1/2 mr-2">
                            <label class="block text-sm font-medium text-gray-700" for="student_id">Student ID</label>
                            <input v-model="editedPost.student_id" class="mt-1 p-2 w-full border rounded" type="text"
                                name="student_id" placeholder="Enter student_id" />
                        </div>
                        <div class="w-1/2 mr-2">
                            <label class="block text-sm font-medium text-gray-700" for="gender">Generation</label>
                            <select v-model="editedPost.generation_id" class="mt-1 p-2 w-full border rounded">
                                <option value="" disabled>Select Generation</option>
                                <option v-for="gen in generations" :key="gen.id" :value="gen.id">
                                    {{ gen.gen }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex mb-2">
                        <div class="w-1/2 mr-2">
                            <label class="block text-sm font-medium text-gray-700" for="gender">Group</label>
                            <select v-model="editedPost.group_id" class="mt-1 p-2 w-full border rounded">
                                <option value="" disabled>Select Group</option>
                                <option v-for="group in groups" :key="group.id" :value="group.id">
                                    {{ group.group_name }}
                                </option>
                            </select>
                        </div>
                        <div class="w-1/2 mr-2">
                            <label class="block text-sm font-medium text-gray-700" for="gender">Major</label>
                            <select v-model="editedPost.major_id" class="mt-1 p-2 w-full border rounded">
                                <option value="" disabled>Select Major</option>
                                <option v-for="major in majors" :key="major.id" :value="major.id">
                                    {{ major.name }}
                                </option>
                                <!-- <option value="2">Ecommerce</option> -->
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4 gap-x-2">
                        <button @click="editModal = false" type="button"
                            class="w-32 py-2 bg-red-500 text-white border-2 hover:bg-red-600">
                            Cancel
                        </button>
                        <button type="submit" class="w-32 py-2 bg-blue-500 mr-2 text-white border-2 hover:bg-blue-600">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Papa from "papaparse";
import { FilterMatchMode } from "primevue/api";
import { ref } from "vue";
import Swal from "sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
export default {
    data() {
        return {
            visible: false,
            showModal: false,
            newPost: {
                f_name: "",
                l_name: "",
                email: "",
                gender: "",
                dob: "",
                student_id: "",
                generation_id: "",
                group_id: "",
                major_id: "",
            },
            editModal: false,
            majors: [],
            generations: [],
            groups: [],
            editedPost: {
                id: null,
                f_name: "",
                l_name: "",
                gender: "",
                dob: "",
                student_id: "",
                generation_id: "",
                group_id: "",
                major_id: "",
                user_id: "",
            },
            students: [],
            student: [],
            selectedTabId: 0,
            tableData: [],
            totalRecords: 0,
            selectedMajor: null,
            majorTabs: [
                {
                    label: "All Student",
                    icon: "pi pi-book",
                    major: null,
                    majorId: null
                },
            ],
            filters: {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
            },
            // upload csv
            fileInput: null,
            isLoading: false,
            errorMessage: null,
            studentData: [],
            error: null,
        };
    },
    mounted() {
        this.fetchPosts();
        this.getMajor();
        this.getGeneration();
        this.getGroups();
        this.getMajorName();
    },
    methods: {
        openDialog() {
            this.visible = true;
        },
        fetchPosts() {
            axios
                .get("students")
                .then((response) => {
                    const students = response.data.map((student, index) => {
                        const genName = student.generation.gen;
                        const group_name = student.group.group_name;
                        const fullName = student.l_name + " " + student.f_name;
                        const Major = student.major.name;
                        return {
                            ...student,
                            genName,
                            group_name,
                            Major,
                            fullName,
                            sequenceNumber: index + 1,
                            index,
                        };
                    });
                    this.tableData = students;
                })
                .catch((error) => {
                    console.error("Error fetching posts:", error);
                });
        },
        getMajor() {
            axios.get("majors").then((res) => {
                this.majors = res.data;
            });
        },
        async getMajorName() {
            try {
                const response = await axios.get("majors");
                this.majors = response.data;
                this.majorTabs.push(
                    ...this.majors.map((major) => ({
                        label: major.name,
                        icon: "pi pi-book",
                        major,
                        majorId: major.id
                    }))
                );
            } catch (error) {
                console.error("Error fetching majors:", error);
            }
        },
        getGeneration() {
            axios.get("generations").then((res) => {
                this.generations = res.data;
            });
        },
        getGroups() {
            axios.get("groups").then((res) => {
                this.groups = res.data.data;
            });
        },
        async handleTabChange(newTab) {
            this.selectedTabId = this.majorTabs[newTab.index];
            const id = this.selectedTabId.majorId
            console.log(id)
            this.tableData = [];
            if (!id) {
                this.fetchPosts()
            }
            try {
                const response = await axios.get(
                    this.selectedTabId != 0 ? `student/${id}` : `students`
                ); // Adjust for your API endpoint
                // this.tableData = response.data;
                const coursesWithTermNames = response.data.map((student) => {
                    const genName = student.generation?.gen;
                    const group_name = student.group?.group_name;
                    const Major = student.major.name;
                    const fullName = student.l_name + " " + student.f_name;
                    return {
                        ...student,
                        group_name,
                        genName,
                        Major,
                        fullName,
                    };
                });
                this.tableData = coursesWithTermNames;
                this.totalRecords = response.headers["x-total-count"]; // Assume API provides total count
            } catch (error) {
                console.error("Error fetching courses:", error);
            } finally {
                this.loading = false;
            }
        },

        createPost() {
            axios
                .post("students", this.newPost)
                .then((response) => {
                    // Handle success, maybe show a success message or update the post list
                    console.log("Post created:", response.data);
                    this.showModal = false;
                    this.newPost.f_name = "";
                    this.newPost.l_name = "";
                    this.newPost.email = "";
                    this.newPost.gender = "";
                    this.newPost.dob = "";
                    (this.newPost.generation_id = ""),
                        (this.newPost.group_id = ""),
                        (this.newPost.major_id = ""),
                        (this.newPost.student_id = ""),
                        this.fetchPosts(); // Refresh posts after creating a new one
                    this.$toast.add({
                        severity: "success",
                        summary: "Add Successfully",
                        detail: "Add Successfully",
                        life: 3000,
                    });
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        console.log("Error:", error.response.data.message);
                        this.error = error.response.data.errors.email[0];
                        // Handle specific validation errors if present (optional)
                    } else {
                        console.log("Unexpected error:", error);
                    }
                    // Handle error, maybe show an error message
                });
        },
        editPost(post) {
            this.editedPost.id = post.id;
            this.editedPost.l_name = post.l_name;
            this.editedPost.gender = post.gender;
            this.editedPost.dob = post.dob;
            this.editedPost.email = post.user.email;
            this.editedPost.user_id = post.user_id;
            this.editedPost.student_id = post.student_id;
            this.editedPost.generation_id = post.generation_id;
            this.editedPost.group_id = post.group_id;
            this.editedPost.major_id = post.major_id;
            this.editedPost.f_name = post.f_name;
            this.editModal = true;
        },
        updatePost() {
            axios
                .put(`students/${this.editedPost.id}`, this.editedPost)
                .then((response) => {
                    console.log("Post updated:", response.data);
                    this.editModal = false;
                    this.fetchPosts(); // Refresh posts after updating one
                    this.$toast.add({
                        severity: "success",
                        summary: "Edit Successfully",
                        detail: "Edit Successfully",
                        life: 3000,
                    });
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        console.log("Error:", error.response.data.message);
                        // Handle specific validation errors if present (optional)
                    } else {
                        console.log("Unexpected error:", error);
                    }
                });
        },
        confirmDelete(prod) {
            Swal.fire({
                title: "Are you sure?",
                text: `${prod.fullName} will remove from your system`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deletePost(prod.id);
                    this.deleteDialog = false;
                    this.$toast.add({
                        severity: "success",
                        summary: "Delete Successfully",
                        detail: "Course deleted Successfully",
                        life: 3000,
                    });
                } else {
                    console.log("Deletion canceled");
                    // this.$toast.add({ severity: 'error', summary: 'Fail delete', detail: 'Faileddd', life: 3000 });
                }
            });
        },
        deletePost(postId) {
            axios
                .delete(`students/${postId}`)
                .then((response) => {
                    this.fetchPosts();
                })
                .catch((error) => {
                    console.error("Error deleting post:", error);
                });
        },
        exportHeaderCSV() {
            const csvHeaders = [
                'ID',
                'FirstName',
                'LastName',
                'Email',
                'Gender',
                'DateOfBirth',
                'Generation',
                'Major',
                'Group',
            ];
            const csv = Papa.unparse([csvHeaders]);
            const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "empty_template_student.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            this.visible = false;
        },
        exportCSV() {
            const studentData = this.tableData.map((student) => ({
                ID: student.student_id,
                Name: student.fullName,
                Gender: student.gender,
                DateOfBirth: student.dob,
                Group: student.group.group_name,
                Major: student.major.name,
                Generation: student.generation.gen,
                Email: student.user.email,
            }));
            this.tableData = studentData;
            const csv = Papa.unparse(this.tableData);
            const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "student_data.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            this.fetchPosts();
        },
        async importCSV() {
            // Trigger the file input click event
            this.$refs.fileInput.click();
        },
        async importStudentsFromCSV() {
            try {
                const fileInput = this.$refs.fileInput;

                // Check if a file is selected
                if (!fileInput.files.length) {
                    // Handle case where no file is selected
                    console.error("No file selected");
                    return;
                }

                // Get the first selected file
                const file = fileInput.files[0];

                // Create a FormData object to send the file
                const formData = new FormData();
                formData.append("file", file);

                // Make a POST request to the backend endpoint
                const response = await axios.post("students_import", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data", // Ensure proper content type for file uploads
                    },
                });
                this.fetchPosts();
                console.log("Import response:", response.data);
                this.$toast.add({
                    severity: "success",
                    summary: "Import Successful",
                    detail: "Students imported successfully",
                    life: 3000,
                });
                this.visible = false;
            } catch (error) {
                console.error("Error importing CSV:", error);
                this.$toast.add({
                    severity: "error",
                    summary: "Import Failed",
                    detail: "Failed to import students",
                    life: 3000,
                });
            }
        },

        async parseCSV(file) {
            return new Promise((resolve, reject) => {
                Papa.parse(file, {
                    complete: (results) => resolve(results.data),
                    error: (error) => reject(error),
                });
            });
        },
    },
};
</script>
<style scoped>
.nav-link {
    text-align: center;
    transition: background-color 0.3s;
}

.active-link {
    border-bottom: 2px solid #4299e1;
    color: #4299e1;
}
</style>
