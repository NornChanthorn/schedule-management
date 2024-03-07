<template>
    <div class="font-istok ">
        <div v-for="majors in courseteacher" :key="majors.major.id">
            <div @click="toggleMajorDetails(majors)" class="text-xl font-bold mb-4 mt-4">
                <i :style="{ transform: majors.showDetails ? 'rotate(0deg)' : 'rotate(-90deg)', transition: 'transform 0.3s ease-in-out' }"
                    class="fa-solid fa-chevron-down mr-4"></i>
                {{ majors.major.name }}
            </div>
            <router-link
      v-if="majors.showDetails"
      :to="{ name: 'Schedules', params: { majorId: majors.major.id, genid: majors.generation.id, termId: majors.term.id } }"
      class="details-container"
    >
      <div class="icon-container">
        <div class="w-10 h-10 bg-white mr-3 flex items-center justify-center">
          <i class="fa-solid fa-business-time"></i>
        </div>
      </div>
      <div class="info-container">
        <p class="text-lg mr-24">Generation: {{ majors.generation.gen }}</p>
        <p class="text-lg">Term {{ majors.term.name }}</p>
      </div>
    </router-link>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';

export default {
    data() {
        return {
            courseteacher: [],
            user: [],
            teacher: {},
        };
    },
    mounted() {
        this.CourseTeacherInfo();
        this.userInfo();
        this.fetchTeacherInfo();
    },
    methods: {

        navigateToSchedule() {
            this.$router.push('/Schedules');
        },

        toggleMajorDetails(major) {
            major.showDetails = !major.showDetails;
        },
        CourseTeacherInfo() {
      if (this.teacher && this.teacher.id) {
        axios.get(`http://139.59.224.162/api/course_teacher/${this.teacher.id}`)
          .then(response => {
            this.courseteacher = response.data.data; // Update the property with the API response
          })
          .catch(error => {
            console.error('Error fetching course teacher information:', error);
          });
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
                        this.CourseTeacherInfo();
                    })
                    .catch(error => {
                        console.error('Error fetching teacher information:', error);
                    });
            }
        },
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
