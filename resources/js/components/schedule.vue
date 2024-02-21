<template>

    <div class="flex items-center mb-4 ml-4">
        <h1 class="text-custom-color-small font-istok text-4xl font-bold">{{ majorName}}, Generation {{ GenName }}, Term {{ termName }}</h1>
        <!-- <button class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2" label="Add New" severity="secondary" > -->
            <!-- <span class="flex items-center">
            <i class="fa-solid fa-circle-plus mr-2"></i>
            Add Course
            </span> -->
        <!-- </button> -->
    </div>


      <div class="schedule-container p-4">
        <div class="schedule">
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th class="time-header">Time</th>
                  <th v-for="day in days" :colspan="groups.length" class="day-header" :key="day">{{ day }}</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>8:15</td>
                  <th :colspan="groups.length * days.length * 2" class="h-16 text-3xl">National Anthem</th>
                </tr>
                <tr>
                    <th rowspan="1" class="bg-green-200"></th>
                    <template v-for="day in days">
                        <template v-for="group in groups">
                          <td class="bg-green-200">{{ group }}
                          </td>
                        </template>
                      </template>
                  </tr>
                <template v-for="(timeSlot, index) in timeSlots" :key="index">
                  <tr>
                    <td style="width: 120px">{{ timeSlot }}</td>
                    <template v-for="day in days">
                      <template v-for="group in groups">
                        <td class="group-cell" @click="TogglePopup('buttonTrigger')">
                          <div v-if="hasMatchingSchedules(timeSlot, day, group)">
                            <template v-for="schedule in matchingSchedules(timeSlot, day, group)">
                              <div class="schedule-info">
                                <div class="flex">
                                  <button class="button"  @click="TogglePopup('buttonpoint')">&hellip;</button>
                                  <p class="theory">Theory</p>
                                </div>
                                <p class="course-name">{{ schedule.course.name }}</p>
                                <p class="text-sm">Kimlong</p>
                                <p class="room">Room: {{ schedule.room.name }}</p>
                              </div>
                            </template>
                          </div>
                          <div v-else>
                            <button class="button">
                              <i class="fa-solid fa-circle-plus mr-2"></i>Add
                            </button>
                          </div>
                        </td>
                      </template>
                    </template>
                  </tr>

                  <!-- Only show the "Break 15min" row for the first 3 time slots -->
                  <template v-if="index < 3">
                    <tr>
                      <td :colspan="groups.length * days.length * 2" class="break-row">Break 15min</td>
                    </tr>
                  </template>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <transition name="slide" appear>
        <div class="point" v-if="popupTriggers.buttonpoint">
          <div class="point-content">
            <div class="point-item bg-blue flex">
              <i class="fas fa-edit text-white text-xl mr-2"></i> Edit
            </div>
            <div class="point-item bg-red flex">
              <i class="fas fa-trash-alt text-white text-xl mr-2"></i> Remove
            </div>
          </div>
        </div>
      </transition>
      <transition name="fade" appear>
            <div class="modal-overlay" v-if="popupTriggers.buttonTrigger"></div>
      </transition>
      <div>
      <transition name="slide" appear>
        <div class="add_schedule" v-if="popupTriggers.buttonTrigger">
          <form class="">
            <h2 class="text-2xl font-bold mb-4 text-center border-b-2 pb-2">Filling Schedule</h2>
            <div class="form_flex_name">
              <div class="mb-4 mr-4 flex-1">
                <label for="firstName" class="block text-sm font-medium text-gray-600">First Name</label>
                <input type="text" id="firstName" name="firstName" class="mt-1 p-2 border rounded-md w-full">
              </div>
              <div class="mb-4 ml-4 flex-1">
                <label for="lastName" class="block text-sm font-medium text-gray-600 ml-6">Last Name</label>
                <input type="text" id="lastName" name="lastName" class="mt-1 p-2 border rounded-md w-full">
              </div>
            </div>
            <div class="mb-4">
              <label for="course" class="block text-sm font-medium text-gray-600">Choose a Course</label>
              <select id="course" name="course"
                class="mt-1 p-2 border rounded-md w-full">
                <option value="automta">Automta</option>
                <option value="NLP">NLP</option>
                <option value="AI">AI</option>
              </select>
            </div>
            <div class="mb-4">
              <label for="class" class="block text-sm font-medium text-gray-600">Choose a Class</label>
              <select id="class" name="class"
                class="mt-1 p-2 border rounded-md w-full">
                <option value="classA">Theory</option>
                <option value="classB">Lab</option>
              </select>
            </div>
            <div class="mb-4">
              <label for="course" class="block text-sm font-medium text-gray-600">Room</label>
              <select id="course" name="course"
                class="mt-1 p-2 border rounded-md w-full">
                <option value="room">A201</option>
                <option value="room">A202</option>
              </select>
            </div>
            <div class="flex justify-end">
              <button type="button" class="bg-white text-black p-2 rounded-md mr-6 hover:bg-gray-200 cancel" @click="TogglePopup('buttonTrigger')">Cancel</button>
              <button type="submit" class="bg-green-500 text-white p-2 rounded-md hover:bg-green-600">Submit</button>
            </div>
          </form>
        </div>
      </transition>
      </div>

</template>


<script>
import { ref } from 'vue';
import axios from 'axios';

export default {
  data() {
    return {
      schedules: [],
      days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
      groups: ['G1'],
      timeSlots: ['08:30:00', '10:15:00', '12:15:00', '13:15:00'],
      dataFromDatabase: {},
      termID: null,
      genID: null,
      majorID: null,
      termName: null,
      GenName: null,
      majorName: null,
      courses: [],

    }

  },
  mounted(){
        this.termID = this.$route.params.termId;
        this.genID = this.$route.params.genid;
        this.majorID = this.$route.params.majorId;
        this.majorName = this.$route.params.name;
        this.getTermID(this.termID);
        this.getGenerationID(this.genID);
  },
  created() {
    const term_id = 5;
    this.getSchedulesByTerm(term_id);
    // this.termID = this.$route.params.termId;
    // this.genID = this.$route.params.genid;
    // this.majorID = this.$route.params.majorId;
    // this.majorName = this.$route.params.name;
    // this.getCourse(this.majorID, this.genID, this.termID);
    // this.getGenerationID(this.genID);
    // this.getTermID(this.termID);
  },
  methods: {
    getSchedulesByTerm(term_id) {
      axios.get(`/api/schedule/${term_id}`).then(
        response => {
          const firstKey = Object.keys(response.data)[0];
          this.schedules = response.data[firstKey];
          this.updateDataFromDatabase();
        }
      ).catch(err => {
        console.error(err);
      });
    },
    updateDataFromDatabase(newData) {
      // Assuming newData has the same structure as dataFromDatabase
      Object.keys(this.dataFromDatabase).forEach((day) => {
        Object.keys(this.dataFromDatabase[day]).forEach((group) => {
          Object.keys(this.dataFromDatabase[day][group]).forEach((timeSlot) => {
            // Update the corresponding data in dataFromDatabase
            this.$set(
              this.dataFromDatabase[day][group],
              timeSlot,
              newData[day][group][timeSlot]
            );
          });
        });
      });
    },
    hasMatchingSchedules(timeSlot, day, group) {
      return this.schedules.some(schedule =>
        schedule.time_start === timeSlot &&
        schedule.day.day === day &&
        schedule.group.group_name === group
      );
    },
    matchingSchedules(timeSlot, day, group) {
      return this.schedules.filter(schedule =>
        schedule.time_start === timeSlot &&
        schedule.day.day === day &&
        schedule.group.group_name === group
      );
    },
    // getCourse(majorId, genId, termId){
    //     axios.get(`course/${majorId}/${genId}/${termId}`).then(
    //         res=>{
    //             this.courses= res.data.data

    //         }
    //     ).catch(er=>{
    //             console.error(er)
    //     })

    // },
    getGenerationID(id){
        axios.get(`generations/${id}`).then(
            res=>{
                this.GenName = res.data.gen
            }
        )

    },
    getTermID(id){
        axios.get(`terms/${id}`).then(
            res=>{
                this.termName = res.data.name
            }
        )

    },

  },
  setup() {
    const popupTriggers = ref({
      buttonTrigger: false,
      timedTrigger: false,
      buttonpoint:false,
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
.schedule-container {
  width: 100%;
  overflow-x: auto;
}

.table-container {
  overflow-x: auto;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  border: 1px solid black;
  padding: 10px;
  text-align: center;
  min-width: 100px;
}

th {
  font-weight: bold;
}

.time-header, .day-header, .sub-header, .time-slot {
  background-color: #4B687A;
  color: white;
}

.group-cell {
  width: 50%;
}

.cell {
  background-color: #E2E8F0;
}

th.time-header {
  width: 180px;
}

th.day-header, td.group-cell {
  min-width: 250px;
}
td.group-cell {
  height: 80px;
}
td.break-row {
  height: 10px;
  background: #FFA500;
  color: white;
}
.course-name {
  font-weight: bold;
}
.theory, .room {
  margin: 0;
  font-size: 14px;
  margin-left: auto;
  text-align: right;
}
.theory{
  margin-bottom: 8px;
}
.room{
  margin-top: 8px;
}
.schedule-info {
  display: flex;
  flex-direction: column;
}
body.modal-open {
  overflow: hidden;
}
.add_schedule {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 100;
  width: 100%; /* Adjust the width as needed */
  max-width: 600px;
  background-color: #FFF;
  padding: 25px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Optional: Add a shadow effect */
}

 .modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 98;
  background-color: rgba(0, 0, 0, 0.3);
  width: 100%;
  height: 100%;
}
.form_flex_name{
  justify-content: space-between;
  align-items: center;
  display: flex;
  width: 100%;
}
.cancel{
  border: 1px solid #4B687A;
}

.point{
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #212b38;
  color: white;
  border: 2px solid #0693E4;
  font-size: 16px;
  transform: translate(-50%, -50%);
  width: 100%;
  max-width: 150px;
  margin-left: 120px;
  top: 500px;
}
.point-item {
  padding: 10px;
  cursor: pointer;
  transition: background-color 0.3s;
}
.point-item:hover {
  background-color: #0693E4;
}
.content-container, .header-container {
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 20px 40px;
  }
</style>

