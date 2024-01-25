<template>
    <div class="w-full h-screen bg-slate-50">
        <div class="w-full flex justify-center items-center ">
            <form action="" class="w-[35%] bg-white justify-center mt-28 p-10 gap-y-5 shadow-lg rounded-lg" @submit.prevent="login">
                <h1 class=" text-2xl font-bold text-center ">Login</h1>
                <div class="lg:flex lg:justify-around lg:items-start mt-4 md:inline sm:inline ">
                    <label for="email" >Email</label>
                    <InputText type="email" v-model="email"></InputText>
                </div>
                <div class="lg:flex lg:justify-evenly lg:items-start mt-4 md:inline sm:inline">
                    <label for="password" >Password</label>
                    <InputText type="password" v-model="password"></InputText>
                </div>
                <div class="mt-5 flex justify-end mr-10">
                    <Button type="submit">Submit</Button>
                </div>

            </form>
        </div>

    </div>
    <!-- <h2 class="w-full bg-slate-300 p-5 text-center ">Login </h2>
    <div class="w-full flex justify-center items-center ">
      <form action="" class="w-[50%] bg-slate-300 flex justify-center mt-28 p-4">
        <div>
            <InputText></InputText>
        </div>

        </form>
    </div> -->


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
        axios.post('/api/login', {
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
    loginTest(){
        console.log('Here logged in')
         this.$router.push({ path: '/' });
    }
  },

};



</script>
