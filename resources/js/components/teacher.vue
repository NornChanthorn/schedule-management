<template>
    <div class="flex items-center mb-4 ml-4">
        <h1 class="text-custom-color-small font-istok text-4xl font-bold">Teacher List</h1>
        <div class="flex items-ceter justify-center ml-16">
              <router-link to="/teacher"  :class="{ 'active-link': $route.path === '/teacher' }" class="w-16 ml-auto text-black px-2 py-2 hover:bg-blue-100 nav-link">All</router-link>
              <router-link to="/ga" :class="{ 'active-link': $route.path === '/' }"  class="w-16 ml-auto text-black px-2 py-2 hover:bg-blue-100 nav-link " >CS</router-link>
              <router-link to="/gb" :class="{ 'active-link': $route.path === '/' }" class="w-16 ml-auto text-black px-2 py-2 hover:bg-blue-100 nav-link ">TN</router-link>
              <router-link to="/gb" :class="{ 'active-link': $route.path === '/' }" class="w-16 ml-auto text-black px-2 py-2 hover:bg-blue-100 nav-link ">EM</router-link>
        </div>
           <button class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" @click="showModal = true">
               <span class="flex items-center">
               <i class="fa-solid fa-circle-plus mr-2"></i>
               Add Teacher
               </span>
        </button>
    </div>
        <div v-if="showModal" class="fixed inset-0 z-10 overflow-y-auto bg-black bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white w-full max-w-lg p-6 border-2">
                    <div class="mb-4 text-center">
                        <h1 class="text-2xl font-bold decoration-gray-400 border-b-2 pb-2">Teacher Information</h1>
                    </div>
                    <form @submit.prevent="createPost">
                        <div class="flex mb-2">
                            <div class="w-1/2 mr-2">
                            <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" v-model="newPost.f_name" name="f_name" class="mt-1 p-2 w-full border rounded" placeholder="User first name">
                            </div>

                            <div class="w-1/2 ml-2">
                            <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" v-model="newPost.l_name"  name="l_name" class="mt-1 p-2 w-full border rounded" placeholder="User last name">
                            </div>
                        </div>
                        <div class="flex mb-2">
                            <div class="w-1/2 mr-2">
                                <label class="block text-sm font-medium text-gray-700" for="gender">Gender</label>
                                <select v-model="newPost.gender" class="mt-1 p-2 w-full border rounded">
                                    <option value="" disabled>Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="w-1/2 ml-2">
                                <label class="block text-sm font-medium text-gray-700" for="dob">DOB</label>
                                <input v-model="newPost.dob" class="mt-1 p-2 w-full border rounded" type="date" name="dob" placeholder="Enter dob" />
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" v-model="newPost.email" name="email"  class="mt-1 p-2 w-full border rounded" placeholder="Enter Email">
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700" for="phone_num">Phone Number</label>
                            <input v-model="newPost.phone_num" class="mt-1 p-2 w-full border rounded" type="tel" name="phone_num" placeholder="Enter phone number"/>
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700" for="title">Title</label>
                            <input v-model="newPost.title" class="mt-1 p-2 w-full border rounded" type="text" name="title" placeholder="Enter title" />
                        </div>
                        <div class="flex items-center justify-end mt-4 gap-x-2">
                            <button @click="showModal = false" type="button"
                                class="w-32 py-2  bg-gray-500 text-white border-2 hover:bg-gray-600">Cancel</button>
                            <button type="submit"
                                class="w-32 py-2 bg-blue-500 mr-2 text-white border-2 hover:bg-blue-600">Save</button>
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
                        <h1 class="text-2xl font-bold decoration-gray-400 border-b-2 pb-2">Edit Teacher Information</h1>
                    </div>
                    <div class="w-full px-6 py-4 bg-white">
                        <form @submit.prevent="updatePost">
                            <div class="flex mb-2">
                                <div class="w-1/2 mr-2">
                                    <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" v-model="editedPost.f_name" name="f_name" class="mt-1 p-2 w-full border rounded" placeholder="User first name">
                                </div>

                                <div class="w-1/2 ml-2">
                                    <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" v-model="editedPost.l_name"  name="l_name" class="mt-1 p-2 w-full border rounded" placeholder="User last name">
                                </div>
                            </div>
                            <div class="flex mb-2">
                                <div class="w-1/2 mr-2">
                                    <label for="lastName" class="block text-sm font-medium text-gray-700">Gender</label>
                                    <input type="text" v-model="editedPost.gender"  name="l_name" class="mt-1 p-2 w-full border rounded" placeholder="Gender">
                                </div>
                                <div class="w-1/2 ml-2">
                                    <label class="block text-sm font-medium text-gray-700" for="dob">DOB</label>
                                    <input v-model="editedPost.dob" class="mt-1 p-2 w-full border rounded" type="text" name="dob" placeholder="Enter dob" />
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" v-model="editedPost.email" name="email"  class="mt-1 p-2 w-full border rounded" placeholder="Enter Email">
                            </div>
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700" for="phone_num">Phone Number</label>
                                <input v-model="editedPost.phone_num" class="mt-1 p-2 w-full border rounded" type="tel" name="phone_num" placeholder="Enter phone number"/>
                            </div>
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700" for="title">Title</label>
                                <input v-model="editedPost.title" class="mt-1 p-2 w-full border rounded" type="text" name="title" placeholder="Enter title" />
                            </div>
                            <div class="flex items-center justify-end mt-4 gap-x-2">
                                <button @click="editModal = false" type="button"
                                    class="w-32 py-2  bg-gray-500 text-white border-2 hover:bg-gray-600">Cancel</button>
                                <button type="submit"
                                    class="w-32 py-2 bg-blue-500 mr-2 text-white  border-2 hover:bg-blue-600">Update</button>
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
                                    Gender</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Phone Number</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Date of Birth</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                    Title</th>
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
                                    <div class="text-sm leading-5 text-gray-900">{{ post.l_name }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ post.gender }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ post.user.email }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ post.phone_num }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ post.dob }}
                                    </div>
                                </td>



                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ post.title }}
                                    </div>
                                </td>

                                <td
                                    class="text-sm font-medium leading-5 text-center whitespace-no-wrap border-b border-gray-200">
                                    <button @click="editPost(post)" class="text-green-600 hover:text-green-800">
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
                title: '',
                f_name: '',
                l_name: '',
                email: '',
                gender: '',
                dob: '',
                phone_num: '',
            },
            editModal: false,
            editedPost: {
                id: null,
                title: '',
                f_name: '',
                l_name: '',
                gender: '',
                dob: '',
                phone_num: '',
                user_id: '',
                f_name: ''
            },
            posts: []
        };
    },
    mounted() {
        this.fetchPosts();
    },
    methods: {
        fetchPosts() {
            axios.get('teachers')
                .then(response => {
                    this.posts = response.data;
                })
                .catch(error => {
                    console.error('Error fetching posts:', error);
                });
        },
        createPost() {
            axios.post('teachers', this.newPost)
                .then(response => {
                    // Handle success, maybe show a success message or update the post list
                    console.log('Post created:', response.data);
                    this.showModal = false;
                    this.newPost.title = '';
                    this.newPost.f_name = '';
                    this.newPost.l_name = '';
                    this.newPost.email = '';
                    this.newPost.gender = '';
                    this.newPost.dob = '';
                    this.newPost.phone_num = '';

                    this.fetchPosts(); // Refresh posts after creating a new one
                })
                .catch(error => {
                    console.error('Error creating post:', error);
                    // Handle error, maybe show an error message
                });
        },
        editPost(post) {
            this.editedPost.id = post.id;
            this.editedPost.title = post.title;
            this.editPost.f_name = post.f_name;
            this.editedPost.l_name = post.l_name;
            this.editedPost.gender = post.gender;
            this.editedPost.dob = post.dob;
            this.editedPost.phone_num = post.phone_num;
            this.editedPost.user_id = post.user_id;
            this.editedPost.f_name = post.f_name;
            this.editModal = true;
        },
        updatePost() {
            axios.put(`teachers/${this.editedPost.id}`, this.editedPost)
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
            axios.delete(`teachers/${postId}`)
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
