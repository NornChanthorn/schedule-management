<template>
    <div class="container max-w-7xl mx-auto mt-8">
        <div class="mb-4">
            <h1 class="font-serif text-3xl font-bold underline decoration-gray-400"> Post Index</h1>
            <div class="flex justify-end">
                <button @click="showModal = true"
                    class="px-4 py-2 rounded-md bg-sky-500 text-sky-100 hover:bg-sky-600">Create Post</button>
            </div>
        </div>
        <!-- Modal for creating a new post -->
        <div v-if="showModal" class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white w-full max-w-lg p-6 rounded-lg shadow-lg">
                    <div class="mb-4">
                        <h1 class="font-serif text-3xl font-bold underline decoration-gray-400">Create Post</h1>
                    </div>
                    <form @submit.prevent="createPost">
                        <div>
                            <label class="block text-sm font-bold text-gray-700" for="name">Name</label>
                            <input v-model="newPost.name"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 placeholder:text-right focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" name="name" placeholder="Enter name" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700" for="size">Size</label>
                            <input v-model="newPost.size"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 placeholder:text-right focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" name="size" placeholder="Enter size" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700" for="type">Type</label>
                            <input v-model="newPost.type"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 placeholder:text-right focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" name="type" placeholder="Enter type" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700" for="stutus">Status</label>
                            <input v-model="newPost.status"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 placeholder:text-right focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" name="status" placeholder="Enter status" />
                        </div>
                        <div class="flex items-center justify-start mt-4 gap-x-2">
                            <button type="submit"
                                class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-sky-100 bg-sky-500 hover:bg-sky-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Save</button>
                            <button @click="showModal = false" type="button"
                                class="px-6 py-2 text-sm font-semibold text-gray-100 bg-gray-400 rounded-md shadow-md hover:bg-gray-600 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal for editing a post -->
        <div v-if="editModal" class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white w-full max-w-lg p-6 rounded-lg shadow-lg">
                    <div class="mb-4">
                        <h1 class="font-serif text-3xl font-bold underline decoration-gray-400">
                            Edit Post
                        </h1>
                    </div>
                    <div class="w-full px-6 py-4 bg-white rounded shadow-md ring-1 ring-gray-900/10">
                        <form @submit.prevent="updatePost">
                            <div>
                                <label class="block text-sm font-bold text-gray-700" for="name">Name</label>
                                <input v-model="editedPost.name"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 placeholder:text-right focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    type="text" name="name" placeholder="Enter Name" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700" for="size">Size</label>
                                <input v-model="editedPost.size"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 placeholder:text-right focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    type="text" name="size" placeholder="Enter size" />
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-bold text-gray-700" for="type">Type</label>
                                <input v-model="editedPost.type"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 placeholder:text-right focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    type="text" name="type" placeholder="Enter type" />
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-bold text-gray-700" for="status">Status</label>
                                <input v-model="editedPost.status"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 placeholder:text-right focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    type="text" name="status" placeholder="Enter Status" />
                            </div>
                            <div class="flex items-center justify-start mt-4 gap-x-2">
                                <button type="submit"
                                    class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-sky-100 bg-sky-500 hover:bg-sky-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                                    Update
                                </button>
                                <button @click="editModal = false" type="button"
                                    class="px-6 py-2 text-sm font-semibold text-gray-100 bg-gray-400 rounded-md shadow-md hover:bg-gray-600 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">
                                    Cancel
                                </button>
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
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Size</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Type</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Status</th>
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

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ post.status }}
                                    </div>
                                </td>
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
    </div>
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
                status: ''
            },
            editModal: false,
            editedPost: {
                id: null,
                name: '',
                size: '',
                type: '',
                status: ''
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
