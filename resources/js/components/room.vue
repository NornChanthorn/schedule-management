<template>
    <!-- <div class="container max-w-7xl mx-auto mt-8"> -->
        <Toast/>
        <div class="flex items-center mb-4 ml-4">
            <h1 class="text-custom-color-small font-istok text-4xl font-bold">Room List</h1>
            <button class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" @click="showModal = true">
                <span class="flex items-center">
                <i class="fa-solid fa-circle-plus mr-2"></i>
                    Add Room
                </span>
            </button>
            </div>
        <!-- Modal for creating a new post -->
        <div v-if="showModal" class="fixed inset-0 z-10 overflow-y-auto bg-black bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white w-full max-w-lg p-6 border border-2">
                    <div class="mb-4 text-center">
                        <h1 class="text-2xl font-bold decoration-gray-400 border-b-2 pb-2">Add Room</h1>
                    </div>
                    <form @submit.prevent="createPost">
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700" for="name">Name</label>
                            <input v-model="newPost.name"
                                class="mt-1 p-2 w-full border rounded"
                                type="text" name="name"  placeholder="Enter name" />
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700" for="size">Size</label>
                            <input v-model="newPost.size"
                                class="mt-1 p-2 w-full border rounded"
                                type="text" name="size" placeholder="Amount of student" />
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700" for="type">Type</label>
                            <input v-model="newPost.type"
                                class="mt-1 p-2 w-full border rounded"
                                type="text" name="type" placeholder="Type of room (Ex: Thoery, Lap...)" />
                        </div>
                        <div class="mb-2 hidden">
                            <label class="block text-sm font-medium text-gray-700" for="stutus">Status</label>
                            <input v-model="newPost.status"
                                class="mt-1 p-2 w-full border rounded"
                                type="text" name="status" placeholder="Enter status" />
                        </div>
                        <div class="flex items-center justify-end mt-4 gap-x-2">
                            <button @click="showModal = false" type="button"
                                class="w-32 py-2  bg-gray-600 text-white hover:bg-gray-400">Cancel</button>
                            <button type="submit"
                                class="w-32 py-2 bg-blue-600 mr-2 text-white border border-2 hover:bg-blue-400">Save</button>
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
                                    class="mt-1 p-2 w-full border rounded"
                                    type="text" name="name" placeholder="Enter Name" />
                            </div>
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700" for="size">Size</label>
                                <input v-model="editedPost.size"
                                    class="mt-1 p-2 w-full border rounded"
                                    type="text" name="size" placeholder="Enter size" />
                            </div>
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700" for="type">Type</label>
                                <input v-model="editedPost.type"
                                    class="mt-1 p-2 w-full border rounded"
                                    type="text" name="type" placeholder="Enter type" />
                            </div>
                            <div class="mb-2 hidden" >
                                <label class="block text-sm font-medium text-gray-700" for="status">Status</label>
                                <input v-model="editedPost.status"
                                    class="mt-1 p-2 w-full border rounded"
                                    type="text" name="status" placeholder="Enter Status" />
                            </div>
                            <div class="flex items-center justify-end mt-4 gap-x-2">
                                <button @click="editModal = false" type="button"
                                    class="w-32 py-2  bg-gray-600 text-white hover:bg-gray-400">Cancel</button>
                                <button type="submit"
                                    class="w-32 py-2 bg-blue-600 mr-2 text-white border border-2 hover:bg-blue-400">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50" >
                                    Size</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Type</th>
                                <!-- <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Status</th> -->
                                <th class="px-6 py-3 text-sm text-left text-gray-500 border-b border-gray-200 bg-gray-50"
                                    colspan="3">
                                    Action</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white">
                            <tr v-for="post in posts" :key="post.id">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="flex items-center">
                                        {{ post.id }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ post.name }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ post.size }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ post.type }}
                                    </div>
                                </td>

                                <!-- <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ post.status }}
                                    </div>
                                </td> -->
                                <td
                                    class="text-sm font-medium leading-5 text-center whitespace-no-wrap border-b border-gray-200">
                                    <button @click="editPost(post)" class="text-green-900 hover:text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="text-sm font-medium leading-5 whitespace-no-wrap border-b border-gray-200">
                                    <button @click="deletePost(post.id)" class="text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <!-- </div> -->
</template>

<script>
import axios from 'axios';

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
            posts: []
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
        deletePost(postId) {
            axios.delete(`rooms/${postId}`)
                .then(response => {
                    // Handle success, maybe show a success message or update the post list
                    console.log('Post deleted:', response.data);
                    this.fetchPosts(); // Refresh posts after deleting one
                })
                .catch(error => {
                    console.error('Error deleting post:', error);
                    // Handle error, maybe show an error message
                });
        }
    }
};
</script>
