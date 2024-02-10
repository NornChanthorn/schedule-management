<template>
    <div class="backgroud_new" >
      <div class="flex items-center justify-between bg-white p-4 shadow-md">
        <div class="flex items-center">
          <img src="/img/cadt_logo.png" alt="Logo" class="h-10 w-full ml-8">
        </div>
        <div class="menu-bar">
        <ul>
          <li><a href="#" class="main-menu">Home</a></li>
          <li v-if="user.role==='admin'"><a href="#" class="main-menu">Student <i class="fas fa-caret-down"></i></a>
              <div class="dropdown-menu">
                  <ul>
                    <li> <a href="#">Team <i class="fas fa-caret-right"></i></a>
                    <div class="dropdown-menu-1">
                        <ul>
                          <li><a href="#">Team-1</a></li>
                          <li><a href="#">Team-2</a></li>
                        </ul>
                      </div>
                    </li>
                  </ul>
              </div>
          </li>
          <li><a href="#" class="main-menu">Student <i class="fas fa-caret-down"></i></a>
              <div class="dropdown-menu">
                  <ul>
                    <li> <a href="#">Department <i class="fas fa-caret-right"></i></a>
                    <div class="dropdown-menu-1">
                        <ul>
                          <li><a href="#">Gen</a></li>
                        </ul>
                      </div>
                    </li>
                  </ul>
              </div>
          </li>
        </ul>
      </div>
      <div class="search-container">
        <div class="search-icon">
          <i class="fas fa-search"></i>
        </div>
        <input type="text" class="search-input" placeholder="Search...">
      </div>
      <div class="flex items-center">
          <i class="fas fa-bell text-white text-xl mr-4"></i>
          <button class="button" @click="TogglePopup('buttonTrigger')">
            <img src="/img/user-avatar.jpg" alt="User Avatar" class="h-8 w-8 rounded-full mr-2 border-2">
          </button>
          <transition name="slide" appear>
            <div class="modal" v-if="popupTriggers.buttonTrigger">
              <div class="modal-content">
                <div class="modal-item bg-blue flex">
                  <a href="#" class="flex_item">
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
      <div class="text-center border-2 ml-10 mr-10 mt-5 font-istok bg-white">
        <div  v-if="user.role==='admin'" class="flex p-4">
          <h1 class="text-4xl font-bold text-custom-color mr-5">Welcome </h1><span class="mt-2 text-xl text-custom-color-small">{{user.name }} !</span>
        </div>
        <div  v-if="user.role==='teacher'"  class="flex p-4">
          <h1 class="text-4xl font-bold text-custom-color mr-5">Welcome </h1><span class="mt-2 text-xl text-custom-color-small">{{user.name }} !</span>
        </div>
      </div>
      <div v-if="user.role =='admin'" class="text-center border-2 ml-10 mr-10 mt-5 font-istok bg-white rounded-md">
        <router-view></router-view>
      </div>
      <div v-else class="text-center border-2 ml-10 mr-10 mt-5 font-istok bg-white rounded-md">
         <TeacherPage></TeacherPage>
      </div>
    </div>
  </template>
<script>
import { ref } from 'vue';
import axios from 'axios';
import TeacherPage from '../components/teacher/MainPage.vue'
export default{
    components:{
        TeacherPage
    },
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
        logout() {
            localStorage.removeItem('authToken');
            this.$router.push({ path: '/login' });
            // axios.post('logout')
            //     .then(response => {
            //         localStorage.removeItem('authToken');
            //         console.log('Logout successful');
            //         this.$router.push({ path: '/login' });
            //     })
            //     .catch(error => {
            //         console.error('Logout error:', error);
            //     });
        },
        checkRoute(){
        if(localStorage.getItem('authToken')==null){
        this.$router.push({path: '/login'})
        }else{
            console.log('Not loggin yet')
        }
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
</style>
