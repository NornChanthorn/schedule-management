<template>
  <div class="flex flex-col">
    <div
      class="mx-auto flex flex-nowrap border-2 ml-10 mr-10 mt-5 font-istok bg-white"
    >
      <h1 class="m-6 text-center text-4xl font-bold text-custom-color">Gen</h1>

      <h3
        @click="showAllStudents"
        :class="{ underline: activeTab === 'allStudents' }"
        class="mt-4 text-center text-gray-500 text-xl p-4 cursor-pointer"
      >
        All Students
      </h3>
      <h3
        @click="showGroupA"
        :class="{ underline: activeTab === 'groupA' }"
        class="mt-4 text-center text-gray-500 text-xl p-4 cursor-pointer"
      >
        Group A
      </h3>
      <h3
        @click="showGroupB"
        :class="{ underline: activeTab === 'groupB' }"
        class="mt-4 text-center text-gray-500 text-xl p-4 cursor-pointer"
      >
        Group B
      </h3>

      <div class="flex mt-4 sm:mt-0 ml-auto items-center">
        <button
          @click="importCSV"
          class="bg-blue-500 text-white px-4 py-2 mr-2"
        >
          Import
        </button>
        <button
          @click="exportCSV"
          class="bg-green-500 text-white px-4 py-2 mr-2"
        >
          Export
        </button>
      </div>
    </div>
    <div class="mx-auto mt-5 border-2 ml-10 mr-10 font-istok bg-white">
      <DataTable
        :value="students"
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
        currentPageReportTemplate="{first} to {last} of {totalRecords}"
      >
        <!-- Common columns ... -->
        <Column field="id" header="No" :sortable="true"></Column>
        <Column field="student_id" header="Student ID"></Column>
        <Column header="Student Name">
          <template #body="slotProps">
            {{ slotProps.data.l_name }} {{ slotProps.data.f_name }}
          </template>
        </Column>
        <Column field="major.name" header="Major"></Column>
        <Column header="Action">
          <template #body="slotProps">
            <div class="flex items-center">
              <button @click="editStudent(slotProps.data.id)">
                <i class="fas fa-edit" style="color: blue"></i>
                <!-- Replace with your preferred icon library or SVG -->
              </button>
              <button @click="deleteStudent(slotProps.data.id)">
                <i class="fas fa-trash-alt" style="color: red"></i>
                <!-- Replace with your preferred icon library or SVG -->
              </button>
            </div>
          </template>
        </Column>
      </DataTable>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Papa from "papaparse";

export default {
  data() {
    return {
      activeTab: "",
      students: [], // Ensure you have the data for DataTable
    };
  },
  mounted() {
    this.getStudent();
  },
  methods: {
    showAllStudents() {
      // Set activeTab to 'allStudents' when clicking on "All Student"
      this.activeTab = "allStudents";
      this.getStudent();
    },
    showGroupA() {
      // Set activeTab to 'allStudents' when clicking on "All Student"
      this.activeTab = "groupA";
      this.students = this.filterStudentsByGroup("GroupA");
    },
    showGroupB() {
      // Set activeTab to 'allStudents' when clicking on "All Student"
      this.activeTab = "groupB";
      this.students = this.filterStudentsByGroup("GroupB");
    },
    getStudent() {
      axios
        .get("students") // Update the URL to match your API endpoint
        .then((response) => {
          // Successfully fetched student information
          this.students = response.data;
          // console.log(this.students);
        })
        .catch((error) => {
          // Handle error
          if (error.response && error.response.status === 401) {
            // Redirect to the login page
            console.log("Unauthenticated");
          } else {
            // Handle other error cases
            console.error("Error fetching student information:", error);
          }
        });
    },
    editStudent(student) {
      // Implement the logic to handle editing a student
      console.log("Editing student:", student);
    },

    filterStudentsByGroup(group) {
      // Filter students based on the 'group' property
      return this.students.filter((student) => student.group === group);
    },
    exportCSV() {
      // Convert data to CSV format
      const csv = Papa.unparse(this.students);
      // Create a Blob containing the CSV data
      const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
      // Create a download link and trigger a click
      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.download = "student_data.csv";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
    importCSV() {
      const input = document.createElement("input");
      input.type = "file";
      input.accept = ".csv";

      input.addEventListener("change", (event) => {
        const file = event.target.files[0];

        if (file) {
          Papa.parse(file, {
            complete: (result) => {
              // Assuming the CSV has a header row
              const header = result.data[0];
              const data = result.data.slice(1);

              // Handle the data as needed, e.g., update the DataTable
              this.students = data.map((row) => {
                const student = {};
                header.forEach((key, index) => {
                  student[key] = row[index];
                });
                return student;
              });
            },
            header: true,
          });
        }
      });

      input.click();
    },
  },
};
</script>
