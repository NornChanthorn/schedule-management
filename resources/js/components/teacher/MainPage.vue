<template >
    <div class="content-container font-istok " >
        <a href="/schedule">
          <div v-for="major in majors" :key="major.id">
            <div @click="toggleMajorDetails(major)" class="text-xl font-bold mb-4 mt-4">
              <span v-if="major.showDetails"><i class="fa-solid fa-chevron-up mr-4"></i></span>
              <span v-else><i class="fa-solid fa-chevron-down mr-4"></i></span> {{ major.name }}
            </div>
            <div v-if="major.showDetails" class="details-container">
              <div class="icon-container">
                <div class="w-10 h-10 bg-white mr-3 flex items-center justify-center">
                  <i class="fa-solid fa-business-time"></i>
                </div>
              </div>
              <div class="info-container">
                <!-- <div v-for="gen in gens" :key="gen.id"> -->
                  <!-- <p class="text-lg mr-24">Generation: {{gen.gen}}</p> -->
                <!-- </div> -->
                <p class="text-lg mr-24">Generation: 10</p>
                <p class="text-lg">Term 10</p>
              </div>
            </div>
          </div>
        </a>
    </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';


  export default {
    data() {
      return {
        majors: [],
        gens: [],
      };
    },
    mounted() {
      this.MajorInfo();
      this.GenInfo();
    },
    methods: {
      toggleMajorDetails(major) {
        major.showDetails = !major.showDetails;
      },
      MajorInfo() {
        axios.get('majors')
          .then(response => {
            this.majors = response.data.map(major => ({ ...major, showDetails: true }));
          })
          .catch(error => {
            console.error('Error fetching major information:', error);
          });
      },
      GenInfo() {
        axios.get('generations')
          .then(response => {
            this.gens = response.data;
          })
          .catch(error => {
            console.error('Error fetching Gen information:', error);
          });
      },
    },
  };
</script>

<style scoped>
@import  '../../../../public/css/style.css';
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
