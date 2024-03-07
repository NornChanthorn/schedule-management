<template>

<div class="flex header_fixed items-center justify-between bg-white p-6 shadow-md z-10">
        <div class="flex items-center">
          <a href="/"><img src="/img/cadt_logo.png" alt="Logo" class="h-10 w-full ml-8"></a>
        </div>
        <div>
          <div class="header_menu">
            <div v-if="user.role==='admin'" class="mr-6">
              <div class="flex items-center mx-auto">
                <button class="flex items-center justify-center w-32 border-blue-500 border text-blue-500 px-2 py-2 hover:bg-blue-100 focus:outline-none mr-2" label="Add New" severity="secondary" @click="showDialog" >
                  <span class="flex items-center mr-8"> Menu </span>
                  <i :class="{ 'fa-solid fa-chevron-down': !isDialogVisible, 'fa-solid fa-chevron-down rotated': isDialogVisible }"></i>
                </button>
              </div>
                  <!-- Modal or dropdown content -->
                  <transition name="fade" appear>
                    <div v-if="isDialogVisible" class="absolute bg-white mt-2 w-32 border-blue-500 border text-blue-500 modal-content">
                      <p @click="goToAnotherPages" class="modal-item-list hover:bg-blue-100">Teacher</p>
                      <p @click="goToAnotherPage" class="modal-item-list hover:bg-blue-100">Student</p>
                      <p @click="goToAnotherPagesroom" class="modal-item-list hover:bg-blue-100">Room</p>
                      <p @click="goToCourse" class="modal-item-list hover:bg-blue-100">Course</p>
                    </div>
                  </transition>
            </div>
            <!-- <div @mouseover="isHovered = true" @mouseout="isHovered = false" @click="NotificationtogglePopup"
                :class="{ 'text-xl mr-4 text-black hover:text-blue-500 transition-all': true, 'hover:text-blue-500': isHovered }">
              <i class="fas fa-bell"></i>
            </div> -->
            <div v-if="user.role=='teacher'" class="relative mr-2 text-center cursor-pointer flex items-center justify-center flex-col" @click="togglePopup">
              <div class="notification bg-white absolute top-10 text-black text-lg font-semibold opacity-0 pointer-events-none transition-transform transform -translate-y-full" :class="{ 'opacity-100 translate-y-0': showPopup }">
                <div v-if="showPopup" class="notification-list">
                  <!-- Loop through your notifications -->
                  <div class="h-2 w-full bg-blue-500"></div>
                  <div class="px-4 py-2">
                    <div v-for="(notification, index) in notifications" :key="index" class="notification-item text-sm text-left">
                      {{ notification }}
                    </div>
                  </div>
                </div>
                <div class="w-3 h-3 bg-blue-500 absolute top-[-6px] left-1/2 transform -translate-x-1/2 rotate-45 z-[-199]"></div>
              </div>
              <div @mouseover="isHovered = true" @mouseout="isHovered = false" :class="{ 'text-xl mr-4 text-black hover:text-blue-500 transition-all': true, 'hover:text-blue-500': isHovered }">
                <i class="fas fa-bell"></i>
              </div>
            </div>
            <div class="h-10 w-10 mr-2">
              <button class="button" @click="TogglePopup('buttonTrigger')">
                <img src="/img/profile.png" alt="User Avatar" class=" rounded-full transition-all border-2 hover:border-blue-500">
              </button>
              <transition name="slide" appear>
              <div class="modal_header" v-if="popupTriggers.buttonTrigger">
                <div class="modal-content">
                  <div class="modal-item">
                    <router-link to="/profiles" @click="popupTriggers.buttonTrigger = false" class="flex_item">
                      <i class="fas fa-user-circle text-white text-xl mr-2"></i> Profile
                    </router-link>
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
<div class="welcome-content ml-8 mr-8 mt-28 font-istok bg-white p-5">
        <h1 class="text-4xl font-bold text-blue-400 ml-8">Welcome to our schedule system </h1>
        <span class="mt-2 text-xl text-custom-color-small"></span>
</div>
<div  class="max-w-95 mx-auto pt-4 px-8 pb-8">
        <div class="content-container">
          <router-view></router-view>
        </div>
</div>

</template>

<script>
import { ref } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
  data() {
    return {
      courseteacher: [],
      user: [],
      teacher: {},
      isHovered: false,
      showPopup: false,
    };
  },
  mounted() {
    // this.CourseTeacherInfo();
    this.userInfo();
    this.fetchTeacherInfo();
    this.checkRoute();
  },
  methods: {

    navigateToSchedule() {
      this.$router.push('/Schedules');
    },
    togglePopup() {
          this.showPopup = !this.showPopup;
    },

    toggleMajorDetails(major) {
      major.showDetails = !major.showDetails;
    },
    // CourseTeacherInfo(teacherId) {
    //   axios.get(`http://139.59.224.162/api/course_teacher/${teacherId}`)
    //     .then(response => {
    //       this.courseteacher = response.data.data; // Update the property with the API response
    //     })
    //     .catch(error => {
    //       console.error('Error fetching course teacher information:', error);
    //     });
    // },
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
        userInfo() {
        axios.get('user')
            .then(response => {
            // Successfully fetched user information
            this.user = response.data;
            this.fetchTeacherInfo();
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
        fetchTeacherInfo() {
        if (this.user && this.user.id) {
            axios.get(`http://139.59.224.162/api/teacher_user/${this.user.id}`)
            .then(response => {
                this.teacher = response.data.data[0]; // Update the teacher data with the API response4
                // this.CourseTeacherInfo(this.teacher.id); // Call CourseTeacherInfo with user ID
            })
            .catch(error => {
                console.error('Error fetching teacher information:', error);
            });
        }
        },
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
};
</script>


<style scoped>
@import '../../../../public/css/style.css';

.details-container {
  padding: 12px;
  border: 1px solid #ccc;
  background-color: #D9D9D9;
  margin-bottom: 10px;
  display: flex;
}

.info-container {
  display: flex;
  align-items: center;
}
</style>
