<template>
 <div class="w-full  flex h-screen font-istok">
      <!-- Use image as background with 3/4 width on medium screens -->
    <div class="md:w-1/2 md:flex items-center justify-center">
      <img class="w-full md:w-1/2 absolute md:block max-h-full py-10" src="/img/login_Pic.svg" alt="Login Image"/>
    </div>
    <div class="z-10  md:w-1/2 flex justify-center items-center bg-blue-700 bg-opacity-50 relative w-full ">
      <form action="" class="w-full max-w-md p-10 mx-auto my-auto bg-white" @submit.prevent="login">
        <div class="flex mb-8">
          <!-- <h1 class="text-5xl font-bold transition-all duration-300 mr-2 text-custom-color font-istok"></h1> -->
          <span class="text-2xl transition-all duration-300 mt-2 text-custom-color-small font-bold font-istok">Schedule Management System</span>
        </div>
          <div class="mb-6">
            <label for="email" class="absolute block text-sm font-medium text-gray-700" :class="{ 'transform -translate-y-4 text-s text-blue-500': emailFocused }">Email</label>
            <div class="relative mt-1">
              <input type="email" v-model="email" @focus="handleFocus" @blur="handleBlur" required class="p-2 w-full border rounded focus:outline-none" :placeholder="emailFocused ? '' : 'Email'"/>
              <div class="absolute bottom-0 left-0 right-0 h-px normal-color"></div>
            </div>
        </div>
        <div class="mb-5 relative">
          <label for="password" class="absolute block text-sm font-medium text-gray-700" :class="{ 'transform -translate-y-4 text-s text-blue-500': pwFocused }">Password</label>
          <div class="relative mt-1">
            <input :type="showPassword ? 'text' : 'password'" v-model="password" required @focus="handle_PW_Focus" @blur="handleBlur" class="p-2 w-full border rounded focus:outline-none" :placeholder="pwFocused ? '' : 'Password'"/>
            <div class="absolute bottom-0 left-0 right-0 h-px normal-color"></div>
            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" @click="togglePasswordVisibility">
              <img
                class="h-5 w-5 text-gray-500"
                :src="showPassword ? 'https://img.icons8.com/?size=60&id=60022&format=png' : 'https://img.icons8.com/?size=160&id=3PEmEecIkSOo&format=png'"
                :alt="showPassword ? 'Show Password' : 'Hide Password'"
              />
            </button>
        </div>
       </div>
       <div v-if="errors" class="text-red-500 text-sm mt-1 text-center">{{ errors }}</div>
        <div class="flex justify-end">
          <button type="submit" class="px-4 py-2 text-white w-full font-istok" style="background-color: #4B687A;">Login</button>
        </div>
        <a href="/forgotpassword" class="block text-center mt-2 text-blue-500 text-xs font-istok">Forgot Password?</a>
      </form>
    </div>
  </div>



</template>
<script>


import router from '../../router/router'
// Import Axios in your Vue component
import axios from 'axios';

// Include CSRF token in Axios headers
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

export default {
data() {
  return {
    email: '',
    password: '',
    showPassword: false,
    emailFocused: false,
    pwFocused: false,
    errors: null,
    error_pass: null,
    error_email: null,
  };
},
mounted(){
  this.checkRoute();
},

methods: {
  checkRoute(){
      if(localStorage.getItem('authToken')!==null){
      this.$router.push({path: '/'})
      }else{
          console.log('Not loggin yet')
      }
  },
  login() {
        this.error_email='';
        this.error_pass= '';
        axios.post('login', {
        email: this.email,
        password: this.password,
    })
    .then(response => {
      const authToken = response.data.token;
      localStorage.setItem('authToken', authToken);
      axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
      axios.get('/user')
        .then(response => {
            const user = response.data;
            const userRole = user.role;
            if(userRole == 'admin'){
                this.$router.push({ name: 'Main'});

            }else{
                this.$router.push({ name: 'TeacherPage'});
            }
            // Update your application state with user role (optional)
            // const retrievedUserRole = getUserRole(); // Replace with your logic
            // localStorage.setItem('userRole', retrievedUserRole);
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
        });
    //   this.$router.push({ path: '/'});
    })
    .catch(error => {
      if (error.response && error.response.status === 422) {
        const errors = error.response.data.errors;
      if (errors.email) {
            this.error_email = 'Invalid email address or email not found.';
          } else {
            this.error_email = null;
          }

          if (errors.password) {
            this.error_pass = 'Incorrect password.';
          } else {
            this.error_pass = null;
          }
        } else {
          this.errors = 'An error occurred while logging in.';
        }
    });
  },

  togglePasswordVisibility() {
    this.showPassword = !this.showPassword;
  },
  handleFocus() {
    this.emailFocused = true;
  },
  handle_PW_Focus() {
    this.pwFocused = true;
  },
  handleBlur() {
    if (!this.email) {
      this.emailFocused = false;
    }
    if (!this.password) {
      this.pwFocused = false;
    }
  },
},

};



</script>
<style>
.font-istok {
    font-family: 'Istok Web', sans-serif;
}
.text-custom-color {
    color: #7A999C;
}
.text-custom-color-small {
    color: #567281;
}
.back{
  background-color: rgba(0, 0, 0, 0.3);
}
</style>
