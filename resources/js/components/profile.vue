<template>
    <div class="text-custom-color-small font-istok text-4xl font-bold p-4">
        <h1>Profile</h1>
    </div>
    <div class="  w-[85%] flex justify-end">
        <label for="profileImage" class="cursor-pointer">
          <img @click="changeProfileImage" src="/img/profile.png" alt="User Profile" class="rounded-full h-20 w-20 mb-4 border border-2 cursor-pointer hover:border-blue-500"/>
        </label>
        <input type="file" id="profileImage" style="display: none" @change="handleProfileImageChange"/>
    </div>
   <!-- <p>teacher data: {{ teacher }}</p> -->
    <form action="">
        <div class="flex item-center justify-center mt-8">
            <div class="w-2/3">
                <div class="mb-4" v-if="user.role=='teacher'">
                    <label for="title" class="block text-m font-medium text-gray-700">Title </label>
                    <input type="text" v-model="teacher.title" id="title" class="mt-1 p-2 w-full border outline-none focus:outline-blue-200">
                </div>
                <div class="flex mb-4" v-if="user.role=='teacher'">
                    <div class="w-1/2 mr-2">
                        <label for="firstName" class="block text-m font-medium text-gray-700">First Name</label>
                        <input type="text" v-model="teacher.f_name"  id="firstName" class="mt-1 p-2 w-full border outline-none focus:outline-blue-200">
                    </div>

                    <div class="w-1/2 ml-2" >
                        <label for="lastName" class="block text-m font-medium text-gray-700">Last Name</label>
                        <input type="text" v-model="teacher.l_name" id="lastName" class="mt-1 p-2 w-full border outline-none focus:outline-blue-200">
                    </div>
                </div>
                <div class="mb-4" v-else>
                    <label for="name" class="block text-m font-medium text-gray-700">Name</label>
                    <input type="text" v-model="user.name" id="email" class="mt-1 p-2 w-full border outline-none focus:outline-blue-200">
                </div>
                <div class="mb-4" v-if="user.role=='teacher'">
                    <label for="gender" class="block text-m font-medium text-gray-700">Gender</label>
                    <input type="text" v-model="teacher.gender" id="gender" class="mt-1 p-2 w-full border outline-none focus:outline-blue-200">
                </div>

                <!-- Email -->
                <div class="mb-4" >
                    <label for="email" class="block text-m font-medium text-gray-700">Email</label>
                    <input type="email" v-model="user.email" id="email" class="mt-1 p-2 w-full border outline-none focus:outline-blue-200">
                </div>

                <!-- Address -->
                <div class="mb-4" v-if="user.role=='teacher'">
                    <label for="address" class="block text-m font-medium text-gray-700">Date of Birth</label>
                    <textarea v-model="teacher.dob" id="address" rows="1" class="mt-1 p-2 w-full border outline-none focus:outline-blue-200"></textarea>
                </div>

                <!-- Contact Number -->
                <div class="mb-4" v-if="user.role=='teacher'">
                    <label for="contactNumber" class="block text-m font-medium text-gray-700">Contact Number</label>
                    <input type="tel" v-model="teacher.phone_num" id="contactNumber" class="mt-1 p-2 w-full border outline-none focus:outline-blue-200">
                </div>

                <div class="flex justify-start mt-6 float-right">
                    <button @click="cancel" class="w-32 px-4 py-2 mr-2 text-gray-700 border hover:bg-gray-200">Cancel</button>
                    <button @click="save(teacher)" class="w-32 px-4 py-2 text-white border bg-blue-500 hover:bg-blue-700">Save</button>
                </div>
            </div>
        </div>
   </form>

</template>

<script>
import axios from 'axios';
export default {
  data(){
    return{
      user: [],
      teacher: [],
    }
  },
  mounted(){
    this.userInfo();

  },
  methods: {
    userInfo(){
      axios.get('user')
        .then(response => {
          this.user = response.data;
          if(this.user.role =='teacher'){
            const user_id = this.user.id;
            axios.get(`teacher_user/${user_id}`).then(
                res=>{
                    this.teacher = res.data.data[0]
                }
            )
          }
        })
        .catch(error => {
          console.error('Error fetching user information:', error);
        });
    },
    cancel() {
      // Implement cancel logic
      console.log('Cancelled');
    },

    save(teacher) {
      console.log('Saved', this.user);
      axios.put(`teachers/${teacher}`, this.teacher).then(
        res=>{
            console.log('update success???')
        }
      ).catch(er=>{
        console.error(er);
      })
    },
    changeProfileImage() {
      // Trigger file input click when clicking on the profile image
      document.getElementById('profileImage').click();
    },
    handleProfileImageChange(event) {
      // Implement logic to handle profile image change
      console.log('Profile Image Changed', event.target.files[0]);
    }
  }
};
</script>
