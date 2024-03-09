<template>
    <div>
      <h1>Reset Password</h1>
      <p>Email: {{ email }}</p>
      <p>Token: {{ token }}</p>
      <form @submit.prevent="resetPassword" class="bg-white px-8 pt-6 pb-8">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="password">New Password:</label>
          <input 
            type="password" 
            id="password" 
            v-model="password" 
            placeholder="Enter your new password" 
            class="appearance-none border w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
            required
          />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">Confirm Password:</label>
          <input 
            type="password" 
            id="password_confirmation" 
            v-model="passwordConfirmation" 
            placeholder="Confirm your new password" 
            class="appearance-none border w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
            required
          />
        </div>
        <button type="submit" class="text-sm bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 focus:outline-none focus:shadow-outline">
          Reset Password
        </button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      email: {
        type: String,
        required: true,
      },
      token: {
        type: String,
        required: true,
      },
    },
    data() {
      return {
        password: '',
        passwordConfirmation: '',
      };
    },
    methods: {
      resetPassword() {
        // Send a request to the backend to reset the password
        axios.post('/api/password/reset', {
          email: this.email,
          token: this.token,
          password: this.password,
          password_confirmation: this.passwordConfirmation
        })
        .then(response => {
          // Handle the response
          console.log(response.data.message);
        })
        .catch(error => {
          // Handle the error
          console.error('Error resetting password:', error.response.data.message);
        });
      }
    }
  };
  </script>
  