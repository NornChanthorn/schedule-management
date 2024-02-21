<template>
    <div class="backgroud_new" >
      <div class="flex header_fixed items-center justify-between bg-white p-6 shadow-md z-10">
        <div class="flex items-center">
          <a href="/"><img src="/img/cadt_logo.png" alt="Logo" class="h-10 w-full ml-8"></a>
        </div>
        <!-- <div  v-if="user.role==='admin'">
          <button class="ml-auto border-blue-500 border text-blue-500 px-2 py-2 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" @click="showDialog">
            <span class="flex items-center">
                <i class="fa-solid fa-circle-plus mr-2"></i>
                Teacher
            </span>
          </button>
          <button class="ml-auto border-blue-500 border text-blue-500 px-2 py-2 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" @click="showDialog">
            <span class="flex items-center">
                <i class="fa-solid fa-circle-plus mr-2"></i>
                Student
            </span>
          </button>
        </div> -->
        <div>
          <div class="flex items-center">
            <div v-if="user.role==='admin'" class="mr-6">
              <div class="flex items-center mx-auto">
                <button class="flex items-center justify-center w-32 border-blue-500 border text-blue-500 px-2 py-2 hover:bg-blue-100 focus:outline-none mr-2" label="Add New" severity="secondary" @click="showDialog">
                  <span class="flex items-center mr-8"> List </span>
                  <i :class="{ 'fa-solid fa-chevron-down': !isDialogVisible, 'fa-solid fa-chevron-down rotated': isDialogVisible }"></i>
                </button>
              </div>
                  <!-- Modal or dropdown content -->
                  <transition name="fade" appear>
                    <div v-if="isDialogVisible" class="absolute bg-white mt-2 w-32 border-blue-500 border text-blue-500 modal-content">
                      <p @click="goToAnotherPages" class="modal-item-list hover:bg-blue-100">Teacher</p>
                      <p @click="goToAnotherPage" class="modal-item-list hover:bg-blue-100">Student</p>
                      <p @click="TermPage" class="modal-item-list hover:bg-blue-100">Term</p>
                      <p @click="goToAnotherPagesroom" class="modal-item-list hover:bg-blue-100">Room</p>
                    </div>
                  </transition>
            </div>
            <div v-if="user.role==='teacher'">
              <i class="fas fa-bell text-xl mr-4 "></i>
            </div>
            <div>
              <button class="button" @click="TogglePopup('buttonTrigger')">
                <img src="/img/profile.png" alt="User Avatar" class="h-8 w-8 rounded-full mr-2 border-2">
              </button>
              <transition name="slide" appear>
              <div class="modal_header" v-if="popupTriggers.buttonTrigger">
                <div class="modal-content">
                  <div class="modal-item">
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
            </div>
            <span class="text-black">{{user.name }}</span>
          </div>
        </div>
      </div>

      <div class="max-w-95 mx-auto p-8 mt-20">
        <!-- <div class="text-center border-2 mb-6 font-istok bg-white">
          <div  v-if="user.role==='admin'" class="flex p-4">
            <h1 class="text-4xl font-bold text-custom-color mr-5 ml-5">Welcome </h1><span class="mt-2 text-xl text-custom-color-small">{{user.name }} !</span>
          </div>
          <div  v-if="user.role==='teacher'"  class="flex p-4">
            <h1 class="text-4xl font-bold text-custom-color mr-5">Welcome </h1><span class="mt-2 text-xl text-custom-color-small">{{user.name }} !</span>
          </div>

        </div>

        <button class="button" @click="goToAnotherPage">Go to student Page</button>
        <button class="button" @click="goToAnotherPages">Go to teacher Page</button>
        <button class="button" @click="goToAnotherPagesroom">Go to room Page</button>

        </div> -->


        <div v-if="user.role =='admin'" class="content-container">
          <router-view></router-view>
        </div>
        <div v-else class="content-container">
          <TeacherPage></TeacherPage>
        </div>
      </div>

    </div>
  </template>
<script>
import { ref } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import TeacherPage from '../components/teacher/MainPage.vue'
export default{
    components:{
        TeacherPage
    },
    data(){
        return{
            user: [],
            isDialogVisible: false,
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
            axios.get('user')
                .then(response => {
                    // Successfully fetched user information
                    this.user = response.data;
                })
                .catch(error => {
                    // Handle error
                    if (error.response && error.response.status === 401) {
                        // Redirect to the login page
                        console.log('unauthenticated')
                    } else {
                    // Handle other error cases
                      console.error('Error fetching user information:', error);
                    }
                });

        },

        goToAnotherPage() {
          // Define the path for the page you want to navigate to
          const newPath = '/student';
          // Navigate to the new path
          this.$router.push(newPath);
        },
        goToAnotherPages() {
          // Define the path for the page you want to navigate to
          const newPath = '/teacher';
          // Navigate to the new path
          this.$router.push(newPath);
        },

        TermPage() {
          // Define the path for the page you want to navigate to
          const newPath = '/terms';
          // Navigate to the new path
          this.$router.push(newPath);
        },
        // logout() {
        //     localStorage.removeItem('authToken');
        //     this.$router.push({ path: '/login' });
        // },

        goToAnotherPagesroom() {
          // Define the path for the page you want to navigate to
          const newPath = '/room';
          // Navigate to the new path
          this.$router.push(newPath);
        },

        async logout() {
      // Use SweetAlert for logout confirmation
          const result = await Swal.fire({
            title: 'Are you sure you want to log out?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log out'
          });

          if (result.isConfirmed) {
            // Perform logout action here
            localStorage.removeItem('authToken');
            this.$router.push({ path: '/login' });
            // Optionally, show a success message
            Swal.fire('Logged Out!', 'You have been logged out.', 'success');
          }
        },
        checkRoute(){
        if(localStorage.getItem('authToken')==null){
        this.$router.push({path: '/login'})
        }else{
            console.log('Not loggin yet')
        }

  },
  showDialog() {
      this.isDialogVisible = !this.isDialogVisible;
    },
    selectOption(option) {
      // Handle the selected option
      console.log(`Selected option: ${option}`);
      // You can replace the above line with the actual code to handle the selected option
      this.isDialogVisible = false; // Close the dialog after selecting an option
    },

    },
    beforeRouteEnter(to, from, next) {
        // Check if the user is authenticated before entering the route
        if (!localStorage.getItem('authToken')) {
        // User is not authenticated, redirect to the login page
        next({ path: '/login' });
        } else {
        // User is authenticated, proceed to the route
        next();
        }
    },



}

</script>

<style>

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
.modal_header{
  position: absolute;
  margin-top: 60px;
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
.modal-item-list {
  padding: 10px 20px;
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
.content-container, .header-container {
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 20px 35px;
  }
  .rotated {
  transform: rotate(-180deg);
  transition: transform 0.3s ease-in-out;
}
.fa-solid.fa-chevron-down {
  transition: transform 0.3s ease-in-out;
}
/* Fade transition */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s, transform 0.3s ease-in-out;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
  transform: translateY(-10px); /* Adjust the translateY value based on your design */
}

/* Transform on click */
.modal-content.clicked {
  transform: translateY(0);
  transition: transform 0.3s ease-in-out;
}
</style>
