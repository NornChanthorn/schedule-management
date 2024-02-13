<template>
    <div class="backgroud_new" >
      <div class="flex header_fixed items-center justify-between bg-white p-4 shadow-md">
        <div class="flex items-center">
          <a href="/"><img src="/img/cadt_logo.png" alt="Logo" class="h-10 w-full ml-8"></a>
        </div>
        <div class="menu-bar">
      </div>
      <div class="search-container">
        <div class="search-icon">
          <i class="fas fa-search"></i>
        </div>
        <input type="text" class="search-input" placeholder="Search...">
      </div>
      <div class="flex items-center">
          <i class="fas fa-bell text-blue text-xl mr-4"></i> 
          <button class="button" @click="TogglePopup('buttonTrigger')">
            <img src="/img/user-avatar.jpg" alt="User Avatar" class="h-8 w-8 rounded-full mr-2 border-2">
          </button>
          <transition name="slide" appear>
            <div class="modal" v-if="popupTriggers.buttonTrigger">
              <div class="modal-content">
                <div class="modal-item bg-blue flex">
                  <a href="/profile" class="flex_item">
                    <i class="fas fa-user-circle text-white text-xl mr-2"></i> Profile
                  </a>
                </div>
                <div class="modal-item bg-blue flex" @click="logout">
                  <i class="fas fa-sign-out-alt text-white text-xl mr-2"></i> Logout
                </div>
              </div>
            </div>
          </transition>
          <span class="text-black">{{user.name }}</span>
        </div>
      </div>
      <div class="max-w-95 mx-auto mt-20 bg-black shadow-md header_fixed">
        <nav>
            <router-link to="/schedule" exact class="nav-link" :class="{ 'active': $route.path === '/schedule' }">Schdeule</router-link>
            <router-link to="/studentList" class="nav-link" :class="{ 'active': $route.path === '/studentList' }">Student</router-link>
            <router-link to="/teacherlist" class="nav-link" :class="{ 'active': $route.path === '/teacherlist' }">Teacher</router-link>
        </nav>
      </div>
      <div class="max-w-95 mx-auto p-8 mt-40">
        <slot></slot>
      </div>
    </div>
  </template>
<script>
import { ref } from 'vue';
import axios from 'axios';
export default{
    data(){
        return{
            user: []
        }
    },

    mounted(){
        this.userInfo();
    },
    setup() {
      const popupTriggers = ref({
        buttonTrigger: false,
        timedTrigger: false,
      });
  
      const TogglePopup = (trigger) => {
        popupTriggers.value[trigger] = !popupTriggers.value[trigger];
      };
  
      setTimeout(() => {
        popupTriggers.value.timedTrigger = true;
      }, 3000);
  
      return {
        TogglePopup,
        popupTriggers,
      };
    },
    methods:{
        userInfo(){
            axios.get('/api/user')
                .then(response => {
                    // Successfully fetched user information
                    this.user = response.data;
                })
                .catch(error => {
                    // Handle error
                    console.error('Error fetching user information:', error);
                });

        },
        logout() {
            axios.post('/api/logout')
                .then(response => {
                    localStorage.removeItem('authToken');
                    console.log('Logout successful');
                    this.$router.push({ path: '/login' });
                })
                .catch(error => {
                    console.error('Logout error:', error);
                });
        }

    }

}

</script>

<style scoped>
/* Import the Istok Web font from Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Istok+Web&display=swap');

.menu-bar ul {
  list-style: none;
  display: flex;
}

.menu-bar ul li,.modal li {
  padding: 10px 30px;
  position: relative;
}

.menu-bar ul li a,.modal .modal-content a {
  font-size: 16px;
  text-decoration: none;
  transition: all 0.3s;
}
.menu-bar ul li a .main-menu
{
  color: black;
}
.menu-bar ul li a:hover{
  color: #0693E4;
}

.fas {
  float: right;
  margin-left: 10px;
  padding-top: 3px;
}

.dropdown-menu {
  display: none;
}

.menu-bar ul li:hover .dropdown-menu {
  display: block;
  position: absolute;
  left: 0;
  top: 100%;
  background-color: #212b38;
  color: white;
  border: 2px solid #0693E4;
}

.menu-bar ul li:hover .dropdown-menu ul {
  display: block;
  margin: 10px;
}

.menu-bar ul li:hover .dropdown-menu ul li {
  width: 150px;
  padding: 10px;
}

.dropdown-menu-1 {
  display: none;
}

.dropdown-menu ul li:hover .dropdown-menu-1 {
  display: block;
  position: absolute;
  left: 150px;
  top: 0;
  background-color: #212b38;
  color: white;
  border: 2px solid #0693E4;
}
.search-container {
  display: flex;
  align-items: center;
  border: 2px solid black; 
  overflow: hidden;
  /* background-color: #F6F6F6;  */
  /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
}
.search-input {
  flex: 1;
  border: none;
  padding: 10px;
  font-size: 16px;
  outline: none;
  width: 300px;
}
.modal{
  position: absolute;
  margin-top: 270px;
  margin-right: 100px;
  background-color: #212b38;
  color: white;
  border: 2px solid #0693E4;
  font-size: 16px;
  transform: translate(-50%, -50%);
  width: 100%;
  max-width: 150px;
}
.modal-item {
  padding: 10px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.flex_item {
  display: flex;
  align-items: center;
  text-decoration: none;
}

/* Additional styles for the icons */
.fa-user-circle,
.fa-sign-out-alt {
  color: #fff;
}

.modal-item:hover,.dropdown-menu-1:hover {
  background-color: #0693E4;
}
.header_fixed{
  position: fixed;
  width: 100%;
  top: 0;
}
/* Apply the imported font to a custom class */
.font-istok {
    font-family: 'Istok Web', sans-serif;
}

.text-custom-color {
    color: #7A999C;
}

.text-custom-color-small {
    color: #567281;
}
.normal-color {
    background-color: #5F7C8D;
}

.new-bg{
    background: #4B687A;
}
.backgroud_new{
    background-color: #F9F9F9;
}
.white{
    background: white;
}
.backgroud_group{
    background: #9DCE9D;
}
.content-container, .header-container {
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 20px 40px;
  }
.nav-link {
    /* margin-right: 20px; */
  text-decoration: none;
  /* transition: background-color 0.3s; */
  transition: border-bottom-color 0.3s, box-shadow 0.3s; 
  color: white;
  display: inline-block;
  padding: 20px; /* Adjust padding as needed */
  border: 2px solid transparent;
}

.nav-link:hover {
  background-color: #ddd;
  color: wbluehite;
}

/* Active link style */
.nav-link.active {
  background-color: #4CAF50;
  border-bottom-color: white;
}


</style>