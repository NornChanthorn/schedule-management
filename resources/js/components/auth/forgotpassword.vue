<template>
  <div class="flex items-center justify-center h-screen bg-blue-700 bg-opacity-50">
    <div class="w-full max-w-md p-10 mx-auto my-auto bg-white">
      <h2 class="text-3xl font-bold transition-all duration-300 mr-2 text-custom-color font-istok text-center">Forgot Password</h2>
      <form @submit.prevent="submitForm" class="bg-white px-8 pt-6 pb-8">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email:</label>
          <input 
            type="email" 
            id="email" 
            v-model="email" 
            placeholder="Enter your email" 
            class="appearance-none border w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
            required
          />
        </div>
        <a href="/login" class="block text-center mt-2 text-blue-500 text-xs font-istok">Login?</a>
        <button type="submit" class="text-sm bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 focus:outline-none focus:shadow-outline">
          Submit
        </button>
      </form>
      <div v-if="emailSent" class="text-green-500 text-center">
        Password reset email sent successfully! Check your email.
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: '',
      emailSent: false
    };
  },
  methods: {
    submitForm() {
      // Send the email to the Laravel backend
      axios.post('/api/password/email', { email: this.email })
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
    }
  }
};
</script>
