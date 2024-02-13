<template>
  <Header>
    <div class="header-container mb-6">
      <div class="font-istok">
        <h1 class="text-4xl font-bold text-custom-color">Welcome</h1>
        <span class="mt-2 text-xl text-custom-color-small"></span>
      </div>
    </div>
    <div class="content-container font-istok">
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
  </Header>
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
      axios.get('/api/majors')
        .then(response => {
          this.majors = response.data.map(major => ({ ...major, showDetails: true }));
        })
        .catch(error => {
          console.error('Error fetching major information:', error);
        });
    },
    GenInfo() {
      axios.get('/api/generations')
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
