<template>
  <Toast />

  <div v-if="user.role =='admin'" class="flex items-center mb-4 ml-4 gap-2">
       <h1 class="text-custom-color-small font-istok text-4xl font-bold hover:underline" @click="backRoute1">{{ majorName.name}}, </h1>
       <h1 class="text-custom-color-small font-istok text-4xl font-bold hover:underline" @click="backRoute2"> Generation {{ GenName }},</h1>
       <h1 class="text-custom-color-small font-istok text-4xl font-bold hover:underline" @click="backRoute3"> Term {{ termName }}</h1>
  </div>

  <div v-else>
    <h1 class="text-custom-color-small font-istok text-4xl font-bold">{{ majorName.name}}, Generation {{ GenName }},
      Term {{
      termName }}</h1>
  </div>



  <div v-if="user.role=='admin'" class="flex justify-between">
    <TabMenu :model="groupTabs" @tabChange="handleTabChange" class="w-full"/>
    <div class="flex justify-end py-2">
            <button @click="printPDF"  class="absolute bg-teal-600 cursor-pointer text-white hover:bg-teal-700 focus:outline-none p-3" >
        <span class="flex items-center">
          <i class="fa-solid fa-file-export mr-2"></i>
          Export PDF
        </span>
    </button>
    </div>
  </div>
  <div v-else class="flex ">
    <!-- <button @click="printPDF" >Print PDF</button> -->

    <TabMenu :model="groupTabs" @tabChange="handleTabChange" class=" w-[80%] inline bg-gray-600"/>

  </div>

  <div class="schedule-container py-4">
    <div class="schedule">
      <div class="table-container">
        <table>
          <thead>
            <tr v-if="selectedTabId === null">
              <th class="time-header">Time</th>
              <th v-for="day in days" :colspan="groups.length" class="day-header" :key="day">{{ day.day }}</th>
            </tr>
            <tr v-else>
              <th class="time-header">Time</th>
              <th v-for="day in days" colspan="1" class="day-header" :key="day">{{ day.day }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="selectedTabId === null">
              <td>8:15</td>
              <th :colspan="groups.length * days.length * 2" class="h-16 text-3xl">National Anthem</th>
            </tr>
            <tr v-else>
              <td>8:15</td>
              <th :colspan="days.length" class="h-16 text-3xl">National Anthem</th>
            </tr>
            <tr v-if="selectedTabId === null">
              <th rowspan="1" class="bg-green-200"></th>
              <template v-for="day in days" :key="day.id">
                <template v-for="group in groups" :key="group.id">
                  <td class="bg-green-200">{{ group.group_name }}
                  </td>
                </template>
              </template>
            </tr>
            <tr v-else>
              <th rowspan="1" class="bg-green-200"></th>
              <template v-for="day in days" :key="day.id">
                <!-- <template > -->
                <td class="bg-green-200">{{ groups.group_name }}
                </td>
                <!-- </template> -->
              </template>
            </tr>

            <template v-for="(timeSlot, index) in timeSlots_start" :key="index">
              <tr v-if="selectedTabId === null">
                <td style="width: 120px">{{ timeSlot.start }} - {{ timeSlot.end }}</td>
                <template v-for="day in days" :key="day.id">
                  <template v-for="group in groups" :key="group.id">
                    <td class="group-cell relative">
                      <div v-if="hasMatchingSchedules(timeSlot.start, timeSlot.end, day.day, group.group_name)">
                        <template
                          v-for="schedule in matchingSchedules(timeSlot.start, timeSlot.end, day.day, group.group_name)"
                          :key="schedule.id">
                          <div class="schedule-info">
                            <div class="flex">
                              <button @click="showPopup(schedule)"
                                class="button_point relative rounded-full hover:bg-gray-200 transition-all duration-300 focus:outline-none w-6 h-6">
                                <i class="fa-solid fa-ellipsis text-sm"></i>
                              </button>
                              <transition name="slide" appear>
                                <div class="point" v-if="schedule.showPopup">
                                  <div>
                                    <div v-if="user.role === 'admin'" class="point-item bg-blue flex text-sm"
                                      @click="showEditPopup(schedule)">
                                      <i class="fas fa-edit text-white text-sm mr-2"></i> Edit
                                    </div>
                                    <div v-if="user.role === 'admin'">
                                      <div class="point-item bg-red flex text-sm" @click="deleteSchedule(schedule.id)">
                                        <i class="fas fa-trash-alt text-white text-sm mr-2"></i> Remove
                                      </div>
                                    </div>
                                    <div v-else>
                                      <div v-if="schedule.course.teacher.id == courseteacher.teacher.id"
                                        class="point-item bg-red flex text-sm" @click="deleteSchedule(schedule.id)">
                                        <i class="fas fa-trash-alt text-white text-sm mr-2"></i> Remove
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </transition>
                              <!-- <p class="theory">{{ schedule.room.type }}</p> -->
                              <p class="theory">{{ schedule.room?.type }}</p>
                            </div>
                            <p class="text-sm">{{ schedule.course.name }}</p>
                            <p class="text-sm">{{ schedule.course.teacher.title }}. {{ schedule.course.teacher.f_name }}
                              {{ schedule.course.teacher.l_name
                              }}
                            </p>
                            <p class="room"> {{ schedule.room?.name }}</p>
                          </div>
                        </template>
                      </div>
                      <div v-else @click="popupforadd(day.id, group.id, timeSlot.start, timeSlot.end)">
                        <button class="button">
                          <i class="fa-solid fa-circle-plus mr-2"></i>Add
                        </button>
                      </div>
                    </td>
                  </template>
                </template>
              </tr>
              <tr v-else>
                <td style="width: 120px">{{ timeSlot.start }} - {{ timeSlot.end }}</td>
                <template v-for="day in days" :key="day.id">
                  <td class="group-cell relative">
                    <div v-if="hasMatchingSchedules(timeSlot.start, timeSlot.end, day.day, groups.group_name)">
                      <template
                        v-for="schedule in matchingSchedules(timeSlot.start, timeSlot.end, day.day, groups.group_name)"
                        :key="schedule.id">
                        <div class="schedule-info">
                          <div class="flex">
                            <button @click="showPopup(schedule)"
                              class="button_point relative rounded-full hover:bg-gray-200 transition-all duration-300 focus:outline-none w-6 h-6">
                              <i class="fa-solid fa-ellipsis text-sm"></i>
                            </button>
                            <transition name="slide" appear>
                              <div class="point" v-if="schedule.showPopup">
                                <div>
                                  <div v-if="user.role === 'admin'" class="point-item bg-blue flex text-sm"
                                    @click="showEditPopup(schedule)">
                                    <i class="fas fa-edit text-white text-sm mr-2"></i> Edit
                                  </div>
                                  <div v-if="user.role === 'admin'">
                                    <div class="point-item bg-red flex text-sm" @click="deleteSchedule(schedule.id)">
                                      <i class="fas fa-trash-alt text-white text-sm mr-2"></i> Remove
                                    </div>
                                  </div>
                                  <div v-else>
                                    <div v-if="schedule.course.teacher.id == courseteacher.teacher.id"
                                      class="point-item bg-red flex text-sm" @click="deleteSchedule(schedule.id)">
                                      <i class="fas fa-trash-alt text-white text-sm mr-2"></i> Remove
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </transition>
                            <!-- <p class="theory">{{ schedule.room.type }}</p> -->
                            <p class="theory">{{ schedule.room?.type }}</p>
                          </div>
                          <p class="text-sm">{{ schedule.course.name }}</p>
                          <p class="text-sm">{{ schedule.course.teacher.title }}. {{ schedule.course.teacher.f_name }}
                            {{ schedule.course.teacher.l_name
                            }}
                          </p>
                          <p class="room"> {{ schedule.room?.name }}</p>
                        </div>
                      </template>
                    </div>
                    <div v-else @click="popupforadd(day.id, groups.id, timeSlot.start, timeSlot.end)">
                      <button class="button">
                        <i class="fa-solid fa-circle-plus mr-2"></i>Add
                      </button>
                    </div>
                  </td>

                </template>
              </tr>

              <template v-if="index == 0">
                <tr v-if="selectedTabId === null">
                  <td :colspan="groups.length * days.length * 2" class="break-row">Break 15min</td>
                </tr>
                <tr v-else>
                  <td :colspan="days.length + 1" class="break-row">Break 15min</td>
                </tr>
              </template>

              <template v-if="index == 1">
                <tr v-if="selectedTabId === null">
                  <td :colspan="groups.length * days.length * 2" class="break-row">Lunch Break 1h 15mns</td>
                </tr>
                <tr v-else>
                  <td :colspan="days.length + 1" class="break-row">Lunch Break 1h 15mns</td>
                </tr>
              </template>

              <template v-if="index == 2">
                <tr v-if="selectedTabId === null">
                  <td :colspan="groups.length * days.length * 2" class="break-row">Break 15min</td>
                </tr>
                <tr v-else>
                  <td :colspan="days.length + 1" class="break-row">Break 15min</td>
                </tr>
              </template>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <transition name="fade" appear>
    <div class="modal-overlay" v-if="popupTriggers.buttonTrigger"></div>
  </transition>


  <div>
    <!-- Add -->
    <transition name="slide" appear>
      <div class="add_schedule" v-if="popupTriggers.buttonTrigger">
        <form @submit.prevent="addNewSchedule">
          <h2 class="text-2xl font-bold mb-4 text-center border-b-2 pb-2">Filling Schedule</h2>
          <div class="form_flex_name">
            <div class="mb-4 flex-1 mr-2">
              <label for="time_start" class="block text-sm font-medium text-gray-600">Start Time</label>
              <input type="text" id="time_start" v-model="selectedTimeStart" disabled
                class="mt-1 p-2 border w-full">
            </div>
            <div class="mb-4 flex-1 ml-2">
              <label for="time_end" class="block text-sm font-medium text-gray-600">End Time</label>
              <input type="text" id="time_end" v-model="selectedTimeEnd" disabled
                class="mt-1 p-2 border w-full">
            </div>
          </div>
          <div>
            <div v-if="user.role === 'admin'" class="mb-4">
              <label for="course_id" class="block text-sm font-medium text-gray-600">Choose Course</label>
              <select v-model="newSchedule.course_id"
                class="mt-1 p-2 border w-full outline-none focus:outline-blue-300"
                placeholder="Choose course">
                <option value="" disabled>Choose Course </option>
                <option v-for="course in courses" :key="course.id" :value="course.id">{{ course.name }}</option>
              </select>
            </div>
            <div v-else class="mb-4">
              <label for="course_id" class="block text-sm font-medium text-gray-600">Course</label>
              <select v-model="newSchedule.course_id"
                class="mt-1 p-2 border w-full  outline-none focus:outline-blue-300">
                <option v-for="course in courseteachers" :value="course.id" :key="course.id">{{ course.name }}</option>
              </select>
            </div>
          </div>
          <div v-if="user.role === 'admin'" class="mb-4">
            <label for="room_id" class="block text-sm font-medium text-gray-600">Choose Room</label>
            <select v-model="newSchedule.room_id" @change="checkAvailability" class="mt-1 p-2 border w-full  outline-none focus:outline-blue-300">
                <option value="" disabled >Choose Room  </option>
              <!-- <option v-for="room in rooms" :value="room.id" :key="room.id">{{ room.name }}</option> -->
                <option v-for="room in rooms" :value="room.id" :key="room.id">{{ room.name }} ({{ room.type }})</option>
                <template v-if="isRoomUnavailable">
                  <p class="text-red-500">Selected room is unavailable for this time and day.</p>
                </template>

            </select>
          </div>
          <div class="flex justify-end">
            <button type="button" class="bg-white text-black px-4 py-2 mr-6 hover:bg-gray-200 cancel"
              @click="TogglePopup('buttonTrigger')">Cancel</button>
            <button type="submit" class="text-white px-4 py-2 bg-blue-500 hover:bg-blue-700">Submit</button>
          </div>
        </form>
      </div>
    </transition>

    <!-- Edit -->
    <transition name="slide" appear>
      <div class="add_schedule" v-if="popupTriggers.editschedule">
        <form @submit.prevent="updateSchedule">
          <h2 class="text-2xl font-bold mb-4 text-center border-b-2 pb-2">Edit Schedule</h2>
          <div class="form_flex_name">
            <div class="mb-4 flex-1 mr-2">
              <label for="time_start" class="block text-sm font-medium text-gray-600">Start Time</label>
              <input type="text" id="time_start" v-model="editschedule.time_start" disabled
                class="mt-1 p-2 border w-full">
            </div>
            <div class="mb-4 flex-1 ml-2">
              <label for="time_end" class="block text-sm font-medium text-gray-600">End Time</label>
              <input type="text" id="time_end" v-model="editschedule.time_end" disabled
                class="mt-1 p-2 border w-full">
            </div>
          </div>
          <div class="mb-4">
            <label for="course_id" class="block text-sm font-medium text-gray-600">Choose course</label>
            <select v-model="editschedule.course_id" class="mt-1 p-2 border w-full">
              <option v-for="course in courses" :value="course.id" :key="course.id">{{ course.name }}</option>
            </select>
          </div>
          <div class="mb-4">
            <label for="room_id" class="block text-sm font-medium text-gray-600">Choose a room</label>
            <select v-model="editschedule.room_id" class="mt-1 p-2 border w-full">
              <option v-for="room in rooms" :value="room.id" :key="room.id">{{ room.name }}</option>
            </select>
          </div>
          <div class="flex justify-end">
            <button type="button" class="bg-white text-black px-4 py-2 mr-6 hover:bg-gray-200 cancel"
              @click="cancelEdit">Cancel</button>
            <button type="submit" class="text-white px-4 py-2 bg-blue-500 hover:bg-blue-700">Update</button>
          </div>
        </form>
      </div>
    </transition>
  </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';
import room from './room.vue';
import jsPDF from 'jspdf'

export default {
  name: 'PdfComponent',
  data() {
    return {
      timeSlots_start: [
        { start: '08:30', end: '10:00' },
        { start: '10:15', end: '11:45' },
        { start: '13:00', end: '14:30' },
        { start: '14:45', end: '16:15' }
      ],
      schedules: [],
      days: [],
      groups: [],
      dataFromDatabase: {},
      termID: null,
      genID: null,
      majorID: null,
      termName: null,
      GenName: null,
      majorName: [],
      newSchedule: {
        time_start: '',
        time_end: '',
        day_id: '',
        term_id: '',
        course_id: '',
        room_id: '',
        group_id: '',
        major_id: '',
        gen_id: '',
      },
      editschedule: {
        id: null,
        time_start: '',
        time_end: '',
        day_id: '',
        term_id: '',
        course_id: '',
        room_id: '',
        group_id: '',
        major_id: '',
        gen_id: '',
      },
      courses: [],
      rooms: [],
      selectedScheduleId: null,
      selectedDayId: null,
      selectedGroupId: 0,
      groupSelected: [],
      selectedTimeStart: null,
      selectedTimeEnd: null,
      user: [],
      courseteacher: [],
      courseteachers: [],
      teacher: {},
      groupTabs: [
        { label: 'All Group', icon: 'pi pi-book', groupID: null, group: null },
      ],
      selectedTabId: null,
      allSchedule: []

    }
  },
  mounted() {
    this.termID = this.$route.params.termId;
    this.genID = this.$route.params.genid;
    this.majorID = this.$route.params.majorId;
    // this.MajorName = this.$route.params.name;
    this.getTermID(this.termID);
    this.getMajorID(this.majorID);
    this.getGenerationID(this.genID);
    this.getDaysOfWeek(); // Fetch days of the week
    this.getGroups(this.majorID, this.genID); // Fetch groups
    // this.handleTabChange();
    this.getSchedulesByTerm(this.termID, this.genID, this.majorID); // Fetch schedules
    this.getCourses(this.termID, this.genID, this.majorID);
    this.getRooms();
    this.userInfo();
    this.CourseTeacherInfo();
    this.fetchTeacherInfo();
    this.getGroupName();
    this.getAllSchedule();
  },
  computed: {
    // filteredAvailableRooms() {
    //     console.log('selectedDayId:', this.selectedDayId); // Log for debugging
    //     console.log('selectedTimeStart:', this.selectedTimeStart);
    //     if (!this.selectedDayId || !this.selectedTimeStart) return this.rooms; // Return all rooms initially or if data is missing
    //     return this.rooms.filter(room => {
    //     return !this.allSchedule.some(schedule => {
    //         return schedule.day_id === this.selectedDayId &&
    //             schedule.time_start === this.selectedTimeStart &&
    //             schedule.room_id === room.id;
    //     });
    //     });
    // },
    isRoomUnavailable() {
    if (!this.newSchedule.room_id || !this.selectedDayId || !this.selectedTimeStart) return false; // Handle cases where data is missing
        return this.allSchedule.some(schedule => {
        return schedule.day_id === this.selectedDayId &&
                schedule.time_start === this.selectedTimeStart &&
                schedule.room_id === this.newSchedule.room_id;
        });
    }

  },
  created() {
    this.getDaysOfWeek(); // Fetch days of the week
  },
  methods: {
    printPDF() {
      let content = `
    <div class="schedule-container p-4" style="width: 100%">
      <div class="schedule">
        <div class="table-container">
          <table style="border-collapse: collapse; width: 100%;">
            <thead>
              <tr>
                <th style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; font-weight: bold; background-color: #4B687A; color: white;" class="time-header">Time</th>
                ${this.selectedTabId === null
                ? this.days.map(day => `<th style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; font-weight: bold; background-color: #4B687A; color: white;" colspan="${this.groups.length}" :key="day.id" class="day-header">${day.day}</th>`).join('')
                : this.days.map(day => `<th style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; font-weight: bold; background-color: #4B687A; color: white;" colspan="1" class="day-header" :key="day.id">${day.day}</th>`).join('')
                }
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px;" class="time-slot">8:15</td>
                ${this.selectedTabId === null
                ? `<th style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; font-weight: bold;" colspan="${this.groups.length * this.days.length * 2}" class="h-16 text-3xl">National Anthem</th>`
                : `<th tyle="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; font-weight: bold;" colspan="${this.days.length}" class="h-16 text-3xl">National Anthem
                </th>`
                }
              </tr>
              <tr>
                ${this.selectedTabId === null
                ? `<th style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px;" rowspan="1" class="bg-green-200"></th>
                ${this.days.map(day => `
                  ${this.groups.map(group => `
                    <td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px;" class="bg-green-200">${group.group_name}</td>
                  `).join('')}
                `).join('')}`
                : `<th style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px;" rowspan="1" class="bg-green-200"></th>
                ${this.days.map(day => `
                  <td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px;" class="bg-green-200">${ this.groups.group_name }
                  </td>
                `).join('')}`
                }
              </tr>
              ${this.timeSlots_start.map((timeSlot, index) => `
                <tr>
                  ${this.selectedTabId === null
                  ? `<td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; width: 120px;">${timeSlot.start} - ${timeSlot.end}</td>
                  ${this.days.map(day => `
                    ${this.groups.map(group => `
                      <td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 250px; height: 80px;" class="group-cell relative">
                        ${this.schedules.filter(schedule =>
                            schedule.time_start <= timeSlot.end &&
                            schedule.time_end >= timeSlot.start &&
                            schedule.day.day === day.day &&
                            schedule.group.group_name === group.group_name
                          ).map(schedule => `
                          <div class="schedule-info" style="display: flex; flex-direction: column">
                            <div class="flex">
                              <p style="margin: 0; font-size: 14px; margin-left: auto; text-align: right;" class="theory">${schedule.room.type}</p>
                            </div>
                            <p class="text-sm">${schedule.course.name}</p>
                            <p class="text-sm">${schedule.course.teacher.title}. ${schedule.course.teacher.f_name} ${schedule.course.teacher.l_name}</p>
                            <p style="margin: 0; font-size: 14px; margin-left: auto; text-align: right; margin-top: 8px;" class="room">${schedule.room.name}</p>
                          </div>
                        `).join('')}
                      </td>
                    `).join('')}
                  `).join('')}`
                  : `<td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; width: 120px;">${timeSlot.start} - ${timeSlot.end}</td>
                  ${this.days.map(day => `
                      <td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 250px; height: 80px;" class="group-cell relative">
                        ${this.schedules.filter(schedule =>
                            schedule.time_start <= timeSlot.end &&
                            schedule.time_end >= timeSlot.start &&
                            schedule.day.day === day.day &&
                            schedule.group.group_name === this.groups.group_name
                          ).map(schedule => `
                          <div class="schedule-info" style="display: flex; flex-direction: column">
                            <div class="flex">
                              <p style="margin: 0; font-size: 14px; margin-left: auto; text-align: right;" class="theory">${schedule.room.type}</p>
                            </div>
                            <p class="text-sm">${schedule.course.name}</p>
                            <p class="text-sm">${schedule.course.teacher.title}. ${schedule.course.teacher.f_name} ${schedule.course.teacher.l_name}</p>
                            <p style="margin: 0; font-size: 14px; margin-left: auto; text-align: right; margin-top: 8px;" class="room">${schedule.room.name}</p>
                          </div>
                        `).join('')}
                      </td>
                  `).join('')}`
                  }
                </tr>
                ${index === 0 ? `
                  <tr>
                    ${this.selectedTabId === null
                    ? `<td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; height:10px; background:#FFA500; color:white;" colspan="${this.groups.length * this.days.length * 2}" class="break-row">Break 15min</td>`
                    : `<td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; height:10px; background:#FFA500; color:white;" colspan="${this.days.length + 1}" class="break-row">Break 15min</td>`
                    }
                  </tr>
                ` : ''}
                ${index === 1 ? `
                  <tr>
                    ${this.selectedTabId === null
                    ? `<td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; height:10px; background:#FFA500; color:white;" colspan="${this.groups.length * this.days.length * 2}" class="break-row">Lunch Break 1h 15mns</td>`
                    : `<td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; height:10px; background:#FFA500; color:white;" colspan="${this.days.length + 1}" class="break-row">Lunch Break 1h 15mns</td>`
                    }
                  </tr>
                ` : ''}
                ${index === 2 ? `
                  <tr>
                    ${this.selectedTabId === null
                    ? `<td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; height:10px; background:#FFA500; color:white;" colspan="${this.groups.length * this.days.length * 2}" class="break-row">Break 15min</td>`
                    : `<td style="border: 1px solid black; padding: 10px; text-align: center; min-width: 100px; height:10px; background:#FFA500; color:white;" colspan="${this.days.length + 1}" class="break-row">Break 15min</td>`
                    }
                  </tr>
                ` : ''}
              `).join('')}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  `;

      // Calculate the width of the table in pixels
      const tempContainer = document.createElement('div');
      tempContainer.innerHTML = content;
      document.body.appendChild(tempContainer);
      const tableWidth = tempContainer.querySelector('table').offsetWidth;
      document.body.removeChild(tempContainer);

      // Set the A1 paper size based on the table width
      const pdf = new jsPDF('l', 'pt', [1000, tableWidth+35]);
      pdf.html(content, {
        callback: function () {
          pdf.save('schedule.pdf');
        }
      });
    },
    getSchedulesByTerm(termId, genId, majorId) {
      axios.get(`schedule/${majorId}/${genId}/${termId}`)
        .then(response => {
          this.schedules = response.data.data;
          this.updateDataFromDatabase();
        })
        .catch(error => {
          console.error(error);
        });
    },
    getGroupName(){
        axios.get(`groups/${this.genID}/${this.majorID}`).then(
            res=>{
                const groups = res.data.data
                console.log(groups)
                this.groupTabs.push(...groups.map((group) => ({
                label: group.group_name,
                icon: 'pi pi-book',
                groupID: group.id,
                group,
                })));
            }
        ).catch(e=>{
            console.error(e);
        })

    },
    getAllSchedule(){
      axios.get('schedules').then(
        res=>{
          this.allSchedule = res.data.data
          console.log(this.allSchedule);
        }
      )

    },
    popupforadd(dayID, groupID, time_start, time_end) {
      this.selectedDayId = dayID;
      this.selectedGroupId = groupID;
      this.selectedTimeStart = time_start;
      this.selectedTimeEnd = time_end;
      this.popupTriggers.buttonTrigger = true;
    },
    addNewSchedule() {
      this.newSchedule.term_id = this.termID;
      this.newSchedule.major_id = this.majorID;
      this.newSchedule.gen_id = this.genID;
      this.newSchedule.day_id = this.selectedDayId;
      this.newSchedule.group_id = this.selectedGroupId;
      this.newSchedule.time_start = this.selectedTimeStart;
      this.newSchedule.time_end = this.selectedTimeEnd;

      if (this.isRoomUnavailable) {
            // Handle conflict: Display a warning message or other appropriate action
            this.$toast.add({
            severity: 'error',
            summary: 'Conflict',
            detail: 'Selected room is unavailable for the chosen time and day.',
            life: 3000
            });
            return; // Prevent scheduling a conflicting event
        }
      axios.post('schedule', this.newSchedule)
        .then(response => {
          console.log(response.data);
          this.$toast.add({ severity: 'success', summary: 'Add Successfully', detail: 'Add Successfully', life: 3000 });
          this.popupTriggers.buttonTrigger = false;
          this.getSchedulesByTerm(this.termID, this.genID, this.majorID);
        })
        .catch(error => {
          console.error(error);
        });
    },
    async handleTabChange(newTab){
        const groupDataTab =  this.groupTabs[newTab.index]
        this.selectedTabId= groupDataTab.groupID;
        if(groupDataTab.groupID==null){
            console.log('Çlicked All group')
            this.groupSelected = null
            console.log(this.groupSelected)
            axios.get(`groups/${this.genID}/${this.majorID}`).then(
                res=>{
                    this.groups = res.data.data
                }
            )
        }else{
            this.groupSelected = groupDataTab.group
            this.groups = this.groupSelected
        }
        console.log('group after select', this.groups)
        console.log('here is group id', this.selectedTabId);
        this.schedules =[];
        try{
            const endpoint = this.selectedTabId === null
            ? `schedule/${this.majorID}/${this.genID}/${this.termID}` // All schedules
            : `schedule_group/${this.majorID}/${this.genID}/${this.termID}/${this.selectedTabId}`; // Schedules for selected group

        const response = await axios.get(endpoint);
        this.schedules = response.data.data;

      } catch (error) {
        console.error(error)

      }
    },


    showEditPopup(schedule) {
      this.editschedule.id = schedule.id;
      this.editschedule.time_start = schedule.time_start;
      this.editschedule.time_end = schedule.time_end;
      this.editschedule.day_id = schedule.day_id;
      this.editschedule.term_id = schedule.term_id;
      this.editschedule.course_id = schedule.course_id;
      this.editschedule.room_id = schedule.room_id;
      this.editschedule.group_id = schedule.group_id;
      this.editschedule.major_id = schedule.major_id;
      this.editschedule.gen_id = schedule.gen_id;

      this.popupTriggers.buttonTrigger = true;
      this.popupTriggers.editschedule = true; // Add this line to show the edit form
      this.popupTriggers.buttonpoint = false; // Close the buttonpoint popup
    },
    updateSchedule() {
      axios.put(`schedule/${this.editschedule.id}`, {
        time_start: this.editschedule.time_start,
        time_end: this.editschedule.time_end,
        day_id: this.editschedule.day_id,
        term_id: this.editschedule.term_id,
        course_id: this.editschedule.course_id,
        room_id: this.editschedule.room_id,
        group_id: this.editschedule.group_id,
        gen_id: this.editschedule.gen_id,
        major_id: this.editschedule.major_id,
      })
        .then(response => {
          console.log(response.data);
          this.$toast.add({ severity: 'success', summary: 'Edit Successfully', detail: 'Edit Successfully', life: 3000 });
          this.popupTriggers.editschedule = false;
          this.popupTriggers.buttonTrigger = false;
          this.getSchedulesByTerm(this.termID, this.genID, this.majorID);
        })
        .catch(error => {
          console.error(error);
        });
    },

    showPopup(schedule) {
      schedule.showPopup = !schedule.showPopup;
      this.schedules.forEach(s => {
        if (s.id !== schedule.id) {
          s.showPopup = false;
        }
      });
    },
    deleteSchedule(scheduleId) {
      if (scheduleId !== null) {
        axios.delete(`schedule/${scheduleId}`)
          .then(response => {
            console.log(`Schedule ${scheduleId} deleted`);
            this.$toast.add({ severity: 'success', summary: 'Delete Successfully', detail: 'Schedule deleted Successfully', life: 3000 });
            this.getSchedulesByTerm(this.termID, this.genID, this.majorID);
          })
          .catch(error => {
            console.error(error);
          });
      } else {
        console.error('Invalid schedule ID');
      }
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

    cancelEdit() {
      this.popupTriggers.editschedule = false;
      this.popupTriggers.buttonTrigger = false;
    },
    hasMatchingSchedules(timeSlots_start, timeSlots_end, day, group) {
      return this.schedules.some(schedule =>
        schedule.time_start <= timeSlots_end &&
        schedule.time_end >= timeSlots_start &&
        schedule.day.day === day &&
        schedule.group.group_name === group
      );
    },
    matchingSchedules(timeSlots_start, timeSlots_end, day, group) {
      return this.schedules.filter(schedule =>
        schedule.time_start <= timeSlots_end &&
        schedule.time_end >= timeSlots_start &&
        schedule.day.day === day &&
        schedule.group.group_name === group
      );
    },

    getGenerationID(id) {
      axios.get(`generations/${id}`).then(
        res => {
          this.GenName = res.data.gen
        }
      )
    },
    getTermID(id) {
      axios.get(`terms/${id}`).then(
        res => {
          this.termName = res.data.name
        }
      )
    },
    getMajorID(id) {
      axios.get(`majors/${id}`).then(
        res => {
          this.majorName = res.data.data[0];
        }
      )
    },

    getDaysOfWeek() {
      axios.get('days-of-week').then(
        response => {
          this.days = response.data;
          // this.day = response.data;
        }
      ).catch(err => {
        console.error(err);
      });
    },
    getGroups(majorID, genID) {
      axios.get(`groups/${genID}/${majorID}`).then(
        response => {
          this.groups = response.data.data;
          // this.group = response.data;
        }
      ).catch(err => {
        console.error(err);
      });
    },
    getCourses(termId, genId, majorId) {
      axios.get(`course/${majorId}/${genId}/${termId}`)
        .then(response => {
          this.courses = response.data.data;
        })
        .catch(error => {
          console.error('Error fetching courses:', error);
        });
    },
    getRooms() {
      axios.get('rooms')
        .then(response => {
          this.rooms = response.data;
        })
        .catch(error => {
          console.error('Error fetching rooms:', error);
        });
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
    CourseTeacherInfo(teacherId, majorId, genId, termId) {
      axios.get(`course/${teacherId}/${majorId}/${genId}/${termId}`)
        .then(response => {
          this.courseteacher = response.data.data[0];
          this.courseteachers = response.data.data;
        })
        .catch(error => {
          console.error('Error fetching course teacher information:', error);
        });
    },
    fetchTeacherInfo() {
      if (this.user && this.user.id) {
        axios.get(`teacher_user/${this.user.id}`)
          .then(response => {
            this.teacher = response.data.data[0]; // Update the teacher data with the API response
            this.CourseTeacherInfo(this.teacher.id, this.majorID, this.genID, this.termID);
          })
          .catch(error => {
            console.error('Error fetching teacher information:', error);
          });
      }
    },
    backRoute1(){
        this.$router.push({path: '/'})
    },
    backRoute2(){
        this.$router.back(-2);
    },
    backRoute3(){
        this.$router.back(-1);
    }
  },
  setup() {
    const popupTriggers = ref({
      buttonTrigger: false,
      timedTrigger: false,
      buttonpoint: false,
      button_cancel: false,
      editschedule: false,
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

th,
td {
  border: 1px solid black;
  padding: 10px;
  text-align: center;
  min-width: 120px;
}

th {
  font-weight: bold;
}

.time-header,
.day-header,
.sub-header,
.time-slot {
  background-color: #4B687A;
  color: white;
}

.group-cell {
  width: 50%;
  /* position: relative; */
}

.cell {
  background-color: #E2E8F0;
}

th.time-header {
  width: 180px;
}

th.day-header,
td.group-cell {
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

.theory,
.room {
  margin: 0;
  font-size: 14px;
  margin-left: auto;
  text-align: right;
}

.room {
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
  width: 100%;
  /* Adjust the width as needed */
  max-width: 600px;
  background-color: #FFF;
  padding: 25px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
  /* Optional: Add a shadow effect */
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

.form_flex_name {
  justify-content: space-between;
  align-items: center;
  display: flex;
  width: 100%;
}

.cancel {
  border: 1px solid #4B687A;
}

.point {
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #212b38;
  color: white;
  border: 2px solid #0693E4;
  transform: translate(-50%, -50%);
  top: 70px;
  left: 74px;
}

.point-item {
  padding: 8px;
  width: 120px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.point-item:hover {
  background-color: #0693E4;
}

.content-container,
.header-container {
  background-color: #fff;
  border: 1px solid #ccc;
  padding: 20px 40px;
}

.button_point {
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
