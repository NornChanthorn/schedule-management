<template>
  <div class="flex items-center justify-center h-screen bg-blue-700 bg-opacity-50">
    <div class="w-full max-w-md p-10 mx-auto my-auto bg-white">
      <h2 class="text-3xl font-bold transition-all duration-300 mr-2 text-custom-color font-istok text-center">Forgot Password</h2>
      <form @submit.prevent="submitForm" class="bg-white px-8 pt-8 pb-8 font-istok">
        <div class="mb-4">
          <label for="email" class="absolute block text-sm font-medium text-gray-700" :class="{ 'transform -translate-y-5 text-sm text-blue-300': emailFocused, 'transition-all duration-100': emailFocused }">Email</label>
          <div class="relative">
            <input type="email" id="email" v-model="email" @focus="handleFocus" @blur="handleBlur" required class="p-2 w-full border focus:outline-none" :placeholder="emailFocused ? '' : 'Email'" />
            <div class="absolute bottom-0 left-0 right-0 h-px normal-color"></div>
          </div>
        </div>
        <a href="/login" class="block text-center mt-2 text-blue-500 text-xs font-istok hover:underline hover:text-bule-700">Login?</a>
        <div v-if="emailSent" class="text-sm text-green-500 text-center w-full">
            Password reset email sent successfully! Check your email.
        </div>
        <button type="submit" class=" text-sm bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 focus:outline-none focus:shadow-outline float-right">
          Submit
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref } from 'vue';

export default {
  data() {
    return {
      email: '',
      emailSent: false,
      emailFocused: false,
    };
  },
  methods: {
    submitForm() {
      // Send the email to the Laravel backend
      axios.post('password/email', { email: this.email })
        .then(response => {
          // Handle the response
          if (response.status === 200) {
            this.emailSent = true;
          }
        })
        .catch(error => {
          console.error('Error sending password reset email:', error);
          // Handle the error
        });
    },
    handleFocus() {
      this.emailFocused = true;
    },
    handleBlur() {
      if (!this.email) {
        this.emailFocused = false;
      }
    },
  }
};
</script>

<style>
.font-istok {
    font-family: 'Istok Web', sans-serif;
}
.text-custom-color {
    color: #7A999C;
}
</style>