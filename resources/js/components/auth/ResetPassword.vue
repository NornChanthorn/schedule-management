<template>
    <div class=" flex justify-center items-center  bg-blue-200   w-screen h-screen">
      <!-- <h1>Reset Password</h1>
      <p>Email: {{ email }}</p>
      <p>Token: {{ token }}</p> -->
      <!-- <div>
        <img src="/img/reset.png" alt="">
      </div> -->
      <div class=" bg-zinc-50 shadow-md flex  w-[60%] justify-center rounded-md ">

        <div class=" w-[80%]  ">
            <div class=" flex justify-center items-center">
                 <img src="/img/reset.png" class=" w-32 z-10  " alt="">
            </div>
             <h1 class=" mt-4 text-center text-lg font-bold">School Management System</h1>
            <form @submit.prevent="resetPassword" class=" w-full h-auto mb-4  p-5">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm mb-2" for="password">New Password:</label>
                    <input
                        type="password"
                        id="password"
                        v-model="password"
                        placeholder="Enter your new password"
                        class="appearance-none border w-full py-2 px-3 text-gray-700 focus:outline-blue-300 focus:shadow-outline"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm  mb-2" for="password_confirmation">Confirm Password:</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        v-model="passwordConfirmation"
                        placeholder="Confirm your new password"
                        class="appearance-none border w-full py-2 px-3 text-gray-700 focus:outline-blue-300 focus:shadow-outline"
                        required
                    />
                </div>
                <button type="submit" class="text-sm bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 focus:outline-none focus:shadow-outline">
                    Reset Password
                </button>
            </form>
        </div>

      </div>

    </div>
  </template>

  <script>
  import axios from 'axios';
import { ref } from 'vue';
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
        axios.post('/password/reset', {
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
