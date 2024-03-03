<template>
    <!-- <div class="container max-w-7xl mx-auto mt-8"> -->
        <Toast/>
        <div class="lg:flex lg:justify-between items-center mb-4 ml-4 md:inline">
            <h1 class="text-custom-color-small font-istok text-4xl font-bold">Room List</h1>
            <div class="flex justify-evenly items-center lg:w-[32%] md:w-[80%] md:mt-2 sm:mt-8">
                <div class="relative flex items-center mr-2">
                    <input type="text" v-model="filters['global'].value" class="border border-blue-300  rounded-lg px-3 py-2 focus:outline-blue-300 focus:outline-2 w-full" placeholder="Search ">
                        <button type="button" class="absolute right-3 top-3 disabled ">
                        <svg class="w-5 h-5   text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"></path></svg>
                    </button>
                </div>
                <button class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" @click="showModal = true">
                    <span class="flex items-center">
                    <i class="fa-solid fa-circle-plus mr-2"></i>
                        Add Room
                    </span>
                </button>
            </div>

        </div>
        <!-- Modal for creating a new post -->
        <div v-if="showModal" class="fixed inset-0 z-10 overflow-y-auto bg-black bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white w-full max-w-lg p-6  border-2">
                    <div class="mb-4 text-center">
                        <h1 class="text-2xl font-bold decoration-gray-400 border-b-2 pb-2">Add Room</h1>
                    </div>
                    <form @submit.prevent="createPost">
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700" for="name">Name</label>
                            <input v-model="newPost.name"
                                class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                                type="text" name="name"  placeholder="Enter name" />
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700" for="size">Size</label>
                            <input v-model="newPost.size"
                                class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                                type="text" name="size" placeholder="Amount of student" />
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700" for="type">Type</label>
                            <input v-model="newPost.type"
                                class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                                type="text" name="type" placeholder="Type of room (Ex: Thoery, Lap...)" />
                        </div>
                        <div class="mb-2 hidden">
                            <label class="block text-sm font-medium text-gray-700" for="stutus">Status</label>
                            <input v-model="newPost.status"
                                class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                                type="text" name="status" placeholder="Enter status" />
                        </div>
                        <div class="flex items-center justify-end mt-4 gap-x-2">
                            <button @click="showModal = false" type="button"
                                class="w-32 py-2  bg-gray-600 text-white hover:bg-gray-400">Cancel</button>
                            <button type="submit"
                                class="w-32 py-2 bg-blue-600 mr-2 text-white  border-2 hover:bg-blue-400">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal for editing a post -->
        <div v-if="editModal" class="fixed inset-0 z-10 overflow-y-auto bg-black bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white w-full max-w-lg p-6">
                    <div class="mb-4 text-center">
                        <h1 class="text-2xl font-bold decoration-gray-400 border-b-2 pb-2">Edit Room Information</h1>
                    </div>
                    <div class="w-full px-6 py-4 bg-white">
                        <form @submit.prevent="updatePost">
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700" for="name">Name</label>
                                <input v-model="editedPost.name"
                                    class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                                    type="text" name="name" placeholder="Enter Name" />
                            </div>
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700" for="size">Size</label>
                                <input v-model="editedPost.size"
                                    class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                                    type="text" name="size" placeholder="Enter size" />
                            </div>
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700" for="type">Type</label>
                                <input v-model="editedPost.type"
                                    class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                                    type="text" name="type" placeholder="Enter type" />
                            </div>
                            <div class="mb-2 hidden" >
                                <label class="block text-sm font-medium text-gray-700" for="status">Status</label>
                                <input v-model="editedPost.status"
                                    class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                                    type="text" name="status" placeholder="Enter Status" />
                            </div>
                            <div class="flex items-center justify-end mt-4 gap-x-2">
                                <button @click="editModal = false" type="button"
                                    class="w-32 py-2  bg-gray-600 text-white hover:bg-gray-400">Cancel</button>
                                <button type="submit"
                                    class="w-32 py-2 bg-blue-600 mr-2 text-white  border-2 hover:bg-blue-400">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- room list -->
        <DataTable :value="posts" v-if="posts"  :filters="filters"
            dataKey="id" :resizableColumns="true" columnResizeMode="expand"  :paginator="true" :rows="10"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 25, 50 , 100]"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Courses" responsiveLayout="scroll">
            <Column field="id" header="ID" :headerStyle="{ 'text-align': 'center' , 'font-size': '13px'}"></Column>
            <Column field="name" header="NAME" :headerStyle="{ 'text-align': 'center' , 'font-size': '13px'}"></Column>
            <Column field="size" header="SIZE" :headerStyle="{ 'text-align': 'center' , 'font-size': '13px'}"></Column>
            <Column field="type" header="TYPE" :headerStyle="{ 'text-align': 'center' , 'font-size': '13px'}"></Column>
            <Column  style="width:15%;  min-width:8rem; " header="ACTION" :headerStyle="{ 'text-align': 'center' , 'font-size': '13px'}" :bodyStyle="{ 'text-align': 'start' }" >
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

</template>

<script>
import axios from 'axios';
import { FilterMatchMode } from "primevue/api";
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            showModal: false,
            newPost: {
                name: '',
                size: '',
                type: '',
                status: 1,
            },
            editModal: false,
            editedPost: {
                id: null,
                name: '',
                size: '',
                type: '',
                status: 1,
            },
            posts: [],
            filters: {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS }
            }
        };
    },
    mounted() {
        this.fetchPosts();
    },
    methods: {
        fetchPosts() {
            axios.get('rooms')
                .then(response => {
                    this.posts = response.data;
                })
                .catch(error => {
                    console.error('Error fetching posts:', error);
                });
        },
        createPost() {
            axios.post('rooms', this.newPost)
                .then(response => {
                    // Handle success, maybe show a success message or update the post list
                    console.log('Post created:', response.data);
                    this.showModal = false;
                    this.newPost.name = '';
                    this.newPost.size = '';
                    this.newPost.type = '';
                    this.newPost.status = '';

                    this.fetchPosts(); // Refresh posts after creating a new one
                    this.$toast.add({ severity: 'success', summary: 'Add Successfully', detail: 'New Room added', life: 3000 });
                })
                .catch(error => {
                    console.error('Error creating post:', error);
                    // Handle error, maybe show an error message
                });
        },
        editPost(post) {
            this.editedPost.id = post.id;
            this.editedPost.name = post.name;
            this.editedPost.type = post.type;
            this.editedPost.status = post.status;
            this.editedPost.size = post.size;
            this.editModal = true;
        },
        updatePost() {
            axios.put(`rooms/${this.editedPost.id}`, this.editedPost)
                .then(response => {
                    console.log('Post updated:', response.data);
                    this.editModal = false;
                    this.fetchPosts(); // Refresh posts after updating one
                })
                .catch(error => {
                    console.error('Error updating post:', error);
                });
        },
        async confirmDelete(prod) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: `Room ${prod.name} will be remove from this system!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            });

            if (result.isConfirmed) {
                this.deletePost(prod.id);
            }
        },

        async deletePost(postId) {
            try {
                const response = await axios.delete(`rooms/${postId}`);
                console.log('Post deleted:', response.data);
                this.fetchPosts();
                this.$toast.add({ severity: 'success', summary: 'Delete Successfully', detail: 'Room deleted Successfully', life: 3000 });
            } catch (error) {
                console.error('Error deleting post:', error);
                this.$toast.add({ severity: 'error', summary: 'Fail delete', detail: 'Failed to delete Room', life: 3000 });
            }
        },
    }
};
</script>
