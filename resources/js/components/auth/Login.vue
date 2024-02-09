<template>
  <div class="w-full h-screen bg-slate-50 flex font-istok">
    <div class="w-1/2 h-full" :style="{ 'background-image': `url('/img/login_pic.svg')`, 'background-size': '600px 450px', 'background-repeat': 'no-repeat', 'background-position': 'right' }"></div>
    <div class="w-1/2 h-full flex justify-center items-center">
      <form action="" class="w-3/4 p-10" @submit.prevent="login">
        <div class="flex mb-8">
          <h1 class="text-5xl font-bold transition-all duration-300 mr-2 text-custom-color font-istok">Hello,</h1>
          <span class="text-3xl transition-all duration-300 mt-2 text-custom-color-small font-istok"> Guyss!</span>
        </div>
          <div class="mb-6">
            <label for="email" class="absolute block text-sm font-medium text-gray-700" :class="{ 'transform -translate-y-4 text-s text-blue-500': emailFocused }">Email</label>
            <div class="relative mt-1">
              <input type="email" v-model="email" @focus="handleFocus" @blur="handleBlur" required class="p-2 w-full border rounded" :placeholder="emailFocused ? '' : 'Email'"/>
              <div class="absolute bottom-0 left-0 right-0 h-px normal-color"></div>
            </div>
        </div>
        <div class="mb-8 relative">
          <label for="password" class="absolute block text-sm font-medium text-gray-700" :class="{ 'transform -translate-y-4 text-s text-blue-500': pwFocused }">Password</label>
          <div class="relative mt-1">
            <input :type="showPassword ? 'text' : 'password'" v-model="password" required @focus="handle_PW_Focus" @blur="handleBlur" class="p-2 w-full border rounded" placeholder="Password "/>
            <div class="absolute bottom-0 left-0 right-0 h-px normal-color"></div>
            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" @click="togglePasswordVisibility">
              <!-- Placeholder text for accessibility -->
              <!-- <span class="sr-only">Toggle Password Visibility</span> -->
              <img
                class="h-5 w-5 text-gray-500"
                :src="showPassword ? 'https://img.icons8.com/?size=60&id=60022&format=png' : 'https://img.icons8.com/?size=160&id=3PEmEecIkSOo&format=png'"
                :alt="showPassword ? 'Show Password' : 'Hide Password'"
              />
            </button>
        </div>
       </div>
        <div class="flex justify-end">
          <button type="submit" class="px-4 py-2 text-white w-full font-istok" style="background-color: #4B687A;">Login</button>
        </div>
        <a href="#" @click.prevent="forgotPassword" class="block text-center mt-2 text-blue-500 text-xs font-istok">Forgot Password?</a>
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
      axios.post('login', {
      email: this.email,
      password: this.password,
    })
    .then(response => {
      const authToken = response.data.token;
      localStorage.setItem('authToken', authToken);
      axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
      this.$router.push({ path: '/'});
    })
    .catch(error => {
      console.log(error);
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
