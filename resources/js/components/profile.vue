<template>
  <!-- <Header> -->
    <!-- <div class="content-container"> -->
      <div class="text-custom-color-small font-istok text-4xl font-bold p-4">
        <h1>Profile</h1>
      </div>
      <div class="absolute right-60 top-40 items-end">
        <label for="profileImage" class="cursor-pointer">
          <img @click="changeProfileImage" src="/img/profile.png" alt="User Profile" class="rounded-full h-20 w-20 mb-4 border border-2 cursor-pointer hover:border-blue-500"/>
        </label>
        <input type="file" id="profileImage" style="display: none" @change="handleProfileImageChange"/>
      </div>
      <div class="flex item-center justify-center mt-8">
        <div class="w-2/3">
          <div class="flex mb-4">
            <div class="w-1/2 mr-2">
              <label for="firstName" class="block text-m font-medium text-gray-700">First Name</label>
              <input type="text" v-model="user.firstName" id="firstName" class="mt-1 p-2 w-full border rounded">
            </div>

            <div class="w-1/2 ml-2">
              <label for="lastName" class="block text-m font-medium text-gray-700">Last Name</label>
              <input type="text" v-model="user.lastName" id="lastName" class="mt-1 p-2 w-full border rounded">
            </div>
          </div>

          <!-- Email -->
          <div class="mb-4">
            <label for="email" class="block text-m font-medium text-gray-700">Email</label>
            <input type="email" v-model="user.email" id="email" class="mt-1 p-2 w-full border rounded">
          </div>

          <!-- Address -->
          <div class="mb-4">
            <label for="address" class="block text-m font-medium text-gray-700">Address</label>
            <textarea v-model="user.address" id="address" rows="1" class="mt-1 p-2 w-full border rounded"></textarea>
          </div>

          <!-- Contact Number -->
          <div class="mb-4">
            <label for="contactNumber" class="block text-m font-medium text-gray-700">Contact Number</label>
            <input type="tel" v-model="user.contactNumber" id="contactNumber" class="mt-1 p-2 w-full border rounded">
          </div>

          <div class="flex justify-start mt-6">
            <button @click="cancel" class="w-32 px-4 py-2 mr-2 text-gray-700 rounded border border-2 hover:bg-gray-200">Cancel</button>
            <button @click="save" class="w-32 px-4 py-2 text-white rounded bg-blue-500 hover:bg-blue-700">Save</button>
          </div>
        </div>
      </div>
    <!-- </div> -->
  <!-- </Header> -->
</template>

<script>
export default {
  data(){
    return{
      user: []
    }
  },
  methods: {
    userInfo(){
      axios.get('/api/user')
        .then(response => {
          this.user = response.data;
        })
        .catch(error => {
          console.error('Error fetching user information:', error);
        });
    },
    cancel() {
      // Implement cancel logic
      console.log('Cancelled');
    },
    save() {
      // Implement save logic
      console.log('Saved', this.user);
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