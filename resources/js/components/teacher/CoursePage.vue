<template>
    <div class="font-istok">
      <div v-for="major in courseteacher" :key="major.major.id">
        <div @click="toggleMajorDetails(major)" class="text-xl font-bold mb-4 mt-4">
          <i :style="{ transform: major.showDetails ? 'rotate(0deg)' : 'rotate(-90deg)', transition: 'transform 0.3s ease-in-out' }"
            class="fa-solid fa-chevron-down mr-4"></i>
          {{ major.major.name }}
        </div>
        <div v-if="major.showDetails">
          <router-link
            v-for="course in major.courses"
            :key="course.id"
            :to="{ name: 'Schedules', params: { majorId: major.major.id, genid: course.generation.id, termId: course.term.id } }"
            class="details-container"
          >
            <div class="icon-container">
              <div class="w-10 h-10 bg-white mr-3 flex items-center justify-center">
                <i class="fa-solid fa-business-time"></i>
              </div>
            </div>
            <div class="info-container">
              <p class="text-lg mr-24">Generation: {{ course.generation.gen }}</p>
              <p class="text-lg mr-24">Term {{ course.term.name }}</p>
              <p class="text-lg">{{ course.name }}</p>
            </div>
          </router-link>
        </div>
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
              const courses = response.data.data;
              const groupedCourses = {};
  
              courses.forEach(course => {
                if (!groupedCourses[course.major_id]) {
                  groupedCourses[course.major_id] = {
                    major: course.major,
                    courses: [course]
                  };
                } else {
                  groupedCourses[course.major_id].courses.push(course);
                }
              });
  
              this.courseteacher = Object.values(groupedCourses);
            })
            .catch(error => {
              console.error('Error fetching course teacher information:', error);
            });
        }
      },
      userInfo() {
        axios.get('user')
          .then(response => {
            this.user = response.data;
            this.fetchTeacherInfo();
          })
          .catch(error => {
            if (error.response && error.response.status === 401) {
              console.log('unauthenticated');
            } else {
              console.error('Error fetching user information:', error);
            }
          });
      },
      fetchTeacherInfo() {
        if (this.user && this.user.id) {
          axios.get(`http://139.59.224.162/api/teacher_user/${this.user.id}`)
            .then(response => {
              this.teacher = response.data.data[0];
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