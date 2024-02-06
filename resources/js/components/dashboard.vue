<template>
  <Header>
    <div class="header-container mb-6">
      <div class="font-istok">
        <h1 class="text-4xl font-bold text-custom-color">Welcome</h1>
        <span class="mt-2 text-xl text-custom-color-small"></span>
      </div>
    </div>
    <div class="content-container font-istok">
      <div>
        <div @click="toggleCSDetails" class="text-2xl font-bold mb-4">
          <span v-if="showCSDetails"><i class="fa-solid fa-chevron-up mr-4"></i></span>
            <span v-else><i class="fa-solid fa-chevron-down mr-4"></i></span> Computer Science
          </div>
          <div v-if="showCSDetails">
            <div class="details-container">
              <div class="icon-container">
                <div class="w-10 h-10 bg-white mr-3 flex items-center justify-center">
                  <i class="fa-solid fa-business-time"></i>
                </div>
              </div>
              <div class="info-container">
                <p class="text-lg mr-24">Generation</p>
                <p class="text-lg">Term 10</p>
              </div>
            </div>
          </div>
        </div>
        <div>
          <div @click="toggleECDetails" class="text-2xl font-bold mb-4">
            <span v-if="showECDetails"><i class="fa-solid fa-chevron-up mr-4"></i></span>
            <span v-else><i class="fa-solid fa-chevron-down mr-4"></i></span> Ecommerce
          </div>
          <div v-if="showECDetails">
            <div class="details-container">
              <div class="icon-container">
                <div class="w-10 h-10 bg-white mr-3 flex items-center justify-center">
                  <i class="fa-solid fa-business-time"></i>
                </div>
              </div>
              <div class="info-container">
                <p class="text-lg mr-24">Generation</p>
                <p class="text-lg">Term 10</p>
              </div>
            </div>
          </div>
        </div>
        <div>
          <div @click="toggleTNDetails" class="text-2xl font-bold mb-4">
            <span v-if="showTNDetails"><i class="fa-solid fa-chevron-up mr-4"></i></span>
            <span v-else><i class="fa-solid fa-chevron-down mr-4"></i></span> Computer Science
          </div>
          <div v-if="showTNDetails">
            <div class="details-container">
              <div class="icon-container">
                <div class="w-10 h-10 bg-white mr-3 flex items-center justify-center">
                  <i class="fa-solid fa-business-time"></i>
                </div>
              </div>
              <div class="info-container">
                <p class="text-lg mr-24">Generation</p>
                <p class="text-lg">Term 10</p>
              </div>
            </div>
          </div>
        </div>
      </div>
  </Header>
</template>

<script>
export default {
  data() {
    return {
      showCSDetails: true,
      showECDetails: true,
      showTNDetails: true,
    };
  },
  methods: {
    toggleCSDetails() {
      this.showCSDetails = !this.showCSDetails;
    },
    toggleECDetails() {
      this.showECDetails = !this.showECDetails;
    },
    toggleTNDetails() {
      this.showTNDetails = !this.showTNDetails;
    },
  },
};
</script>

<style scoped>

.details-container {
  padding: 15px;
  border: 1px solid #ccc;
  background-color: #D9D9D9;
  margin-bottom: 10px;
  display: flex;
}

.info-container {
  display: flex;
  align-items: center; /* Align items vertically */
}
</style>
