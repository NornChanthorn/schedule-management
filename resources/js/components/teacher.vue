<template>
  <Toast></Toast>
  <div class="lg:flex lg:justify-between mb-4 ml-4 md:inline sm:inline">
    <h1 class="text-custom-color-small font-istok text-4xl font-bold">
      Teacher List
    </h1>
    <div class="flex justify-between items-center w-auto">
      <div class="relative flex items-center mr-2">
        <input
          type="text"
          v-model="filters['global'].value"
          class="border border-blue-300 px-3 py-2 focus:outline-blue-300 focus:outline-2 w-full"
          placeholder="Search "
        />
        <button type="button" class="absolute right-3 top-3 disabled">
          <svg
            class="w-5 h-5 text-gray-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"
            ></path>
          </svg>
        </button>
      </div>
      <button
        class="ml-auto bg-blue-500 text-white px-2 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2"
        label="Add New"
        severity="secondary"
        @click="showModal = true"
      >
        <span class="flex items-center">
          <i class="fa-solid fa-circle-plus mr-2"></i>
          Add Teacher
        </span>
      </button>
      <button
        @click="openDialog"
        class="cursor-pointer bg-blue-500 text-white hover:bg-blue-700 focus:outline-none px-4 py-2 mr-2"
      >
        <span class="flex items-center">
          <i class="fa-solid fa-upload mr-2"></i>
          Import
        </span>
      </button>
      <Dialog
        v-model:visible="visible"
        modal
        :style="{ width: '35vw' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
      >
        <div class="w-full flex justify-center items-center"></div>
        <template #header>
          <div class="text-center border-b border-gray-300 w-full pb-4">
            <h2 class="text-lg font-bold">Import Teachers</h2>
          </div>
        </template>
        <div class="p-4">
          <div class="flex justify-center items-center space-x-6">
            <button
              @click="exportHeaderCSV"
              class="bg-teal-600 cursor-pointer text-white hover:bg-teal-700 focus:outline-none px-4 py-2"
            >
              <span class="flex items-center">Export Template</span>
            </button>
            <input
              type="file"
              ref="fileInput"
              style="display: none"
              @change="importTeachersFromCSV"
              accept=".csv"
            />
            <button
              @click="importCSV"
              class="cursor-pointer bg-blue-500 text-white hover:bg-blue-700 focus:outline-none px-4 py-2"
            >
              <span class="flex items-center"> Import Teachers </span>
            </button>
          </div>
        </div>
      </Dialog>

      <button
        @click="exportCSV"
        class="bg-teal-600 cursor-pointer text-white hover:bg-teal-700 focus:outline-none px-4 py-2 mr-2"
      >
        <span class="flex items-center">
          <i class="fa-solid fa-file-export mr-2"></i>
          Export
        </span>
      </button>
    </div>
  </div>
  <!-- Modal for Add -->
  <div
    v-if="showModal"
    class="fixed inset-0 z-10 overflow-y-auto bg-black bg-opacity-50"
  >
    <div class="flex items-center justify-center min-h-screen">
      <div class="bg-white w-full max-w-lg p-6 border-2">
        <div class="mb-4 text-center">
          <h1 class="text-2xl font-bold decoration-gray-400 border-b-2 pb-2">
            Teacher Information
          </h1>
        </div>
        <form @submit.prevent="createPost">
          <div class="flex mb-2">
            <div class="w-1/2 mr-2">
              <label
                for="firstName"
                class="block text-sm font-medium text-gray-700"
                >First Name</label
              >
              <input
                type="text"
                v-model="newPost.f_name"
                name="f_name"
                class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                placeholder="First name"
              />
            </div>

            <div class="w-1/2 ml-2">
              <label
                for="lastName"
                class="block text-sm font-medium text-gray-700"
                >Last Name</label
              >
              <input
                type="text"
                v-model="newPost.l_name"
                name="l_name"
                class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                placeholder="Last name"
              />
            </div>
          </div>
          <div class="flex mb-2">
            <div class="w-1/2 mr-2">
              <label
                class="block text-sm font-medium text-gray-700"
                for="gender"
                >Gender</label
              >
              <select
                v-model="newPost.gender"
                class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
              >
                <option value="" disabled>Select gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="w-1/2 ml-2">
              <label class="block text-sm font-medium text-gray-700" for="dob"
                >Date of Birth</label
              >
              <input
                v-model="newPost.dob"
                class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                type="date"
                name="dob"
                placeholder="Enter of birth"
              />
            </div>
          </div>
          <div class="mb-2">
            <label for="email" class="block text-sm font-medium text-gray-700"
              >Email</label
            >
            <input
              type="email"
              v-model="newPost.email"
              name="email"
              class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
              placeholder="Enter Email"
            />
          </div>
          <div class="mb-2">
            <label
              class="block text-sm font-medium text-gray-700"
              for="phone_num"
              >Phone Number</label
            >
            <input
              v-model="newPost.phone_num"
              class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
              type="tel"
              name="phone_num"
              placeholder="Enter phone number"
            />
          </div>
          <div class="mb-2">
            <label class="block text-sm font-medium text-gray-700" for="title"
              >Title</label
            >
            <input
              v-model="newPost.title"
              class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
              type="text"
              name="title"
              placeholder="Teacher's titile (Ex: professor, Dr.)"
            />
          </div>
          <div class="flex items-center justify-between mt-4 gap-x-2">
            <button
              @click="showModal = false"
              type="button"
              class="w-32 py-2 bg-red-500 text-white border-2 hover:bg-red-600"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="w-32 py-2 bg-blue-500 mr-2 text-white border-2 hover:bg-blue-600"
            >
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Modal for editing a post -->
  <div
    v-if="editModal"
    class="fixed inset-0 z-10 overflow-y-auto bg-black bg-opacity-50"
  >
    <div class="flex items-center justify-center min-h-screen">
      <div class="bg-white w-full max-w-lg p-6 border-2">
        <div class="mb-4 text-center">
          <h1 class="text-2xl font-bold decoration-gray-400 border-b-2 pb-2">
            Edit Teacher Information
          </h1>
        </div>
        <div class="w-full px-6 py-4 bg-white">
          <form @submit.prevent="updatePost">
            <div class="flex mb-2">
              <div class="w-1/2 mr-2">
                <label
                  for="firstName"
                  class="block text-sm font-medium text-gray-700"
                  >First Name</label
                >
                <input
                  type="text"
                  v-model="editedPost.f_name"
                  name="f_name"
                  class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                  placeholder="User first name"
                />
              </div>

              <div class="w-1/2 ml-2">
                <label
                  for="lastName"
                  class="block text-sm font-medium text-gray-700"
                  >Last Name</label
                >
                <input
                  type="text"
                  v-model="editedPost.l_name"
                  name="l_name"
                  class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                  placeholder="User last name"
                />
              </div>
            </div>
            <div class="flex mb-2">
              <div class="w-1/2 mr-2">
                <label
                  for="lastName"
                  class="block text-sm font-medium text-gray-700"
                  >Gender</label
                >
                <!-- <input type="text" v-model="editedPost.gender"  name="l_name" class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200" placeholder="Gender"> -->
                <select
                  v-model="editedPost.gender"
                  class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                >
                  <option value="" disabled>Select gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
              <div class="w-1/2 ml-2">
                <label class="block text-sm font-medium text-gray-700" for="dob"
                  >Date of Birth</label
                >
                <input
                  v-model="editedPost.dob"
                  class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                  type="text"
                  name="dob"
                  placeholder="Enter dob"
                />
              </div>
            </div>
            <div class="mb-2">
              <label for="email" class="block text-sm font-medium text-gray-700"
                >Email</label
              >
              <input
                type="email"
                v-model="editedPost.email"
                name="email"
                class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                placeholder="Enter Email"
              />
            </div>
            <div class="mb-2">
              <label
                class="block text-sm font-medium text-gray-700"
                for="phone_num"
                >Phone Number</label
              >
              <input
                v-model="editedPost.phone_num"
                class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                type="tel"
                name="phone_num"
                placeholder="Enter phone number"
              />
            </div>
            <div class="mb-2">
              <label class="block text-sm font-medium text-gray-700" for="title"
                >Title</label
              >
              <input
                v-model="editedPost.title"
                class="mt-1 p-2 w-full border rounded outline-none hover:outline-blue-200"
                type="text"
                name="title"
                placeholder="Enter title"
              />
            </div>
            <div class="flex items-center justify-between mt-4 gap-x-2">
              <button
                @click="editModal = false"
                type="button"
                class="w-32 py-2 bg-red-500 text-white border-2 hover:bg-red-600"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="w-32 py-2 bg-blue-500 mr-2 text-white border-2 hover:bg-blue-600"
              >
                Update
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- list teacher -->
  <DataTable
    :value="posts"
    v-if="posts"
    :filters="filters"
    dataKey="id"
    :resizableColumns="true"
    columnResizeMode="expand"
    :paginator="true"
    :rows="10"
    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
    :rowsPerPageOptions="[5, 10, 25, 50, 100]"
    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Teachers"
    responsiveLayout="scroll"
  >
    <Column
      field="sequenceNumber"
      header="ID"
      :headerStyle="{ 'text-align': 'center', 'font-size': '13px' }"
    ></Column>
    <Column
      field="fullName"
      header="NAME"
      :headerStyle="{ 'text-align': 'center', 'font-size': '13px' }"
    ></Column>
    <Column
      field="gender"
      header="GENDER"
      :headerStyle="{ 'text-align': 'center', 'font-size': '13px' }"
    ></Column>
    <Column
      field="phone_num"
      header="PHONE NUMBER"
      :headerStyle="{ 'text-align': 'center', 'font-size': '13px' }"
    ></Column>
    <Column
      field="email"
      header="EMAIL"
      :headerStyle="{ 'text-align': 'center', 'font-size': '13px' }"
    ></Column>
    <Column
      style="width: 15%; min-width: 8rem"
      header="ACTION"
      :headerStyle="{ 'text-align': 'center', 'font-size': '13px' }"
      :bodyStyle="{ 'text-align': 'start' }"
    >
      <template #body="slotProps">
        <div class="flex justify-between items-start w-[60%]">
          <button
            @click="editPost(slotProps.data)"
            class="text-green-600 hover:text-green-800"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-6 h-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
              />
            </svg>
          </button>
          <button
            @click="deletePostWithConfirmation(slotProps.data)"
            class="text-red-600 hover:text-red-800"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-6 h-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
              />
            </svg>
          </button>
        </div>
      </template>
    </Column>
  </DataTable>
</template>

<script>
import axios from "axios";
import Papa from "papaparse";
import { FilterMatchMode } from "primevue/api";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      visible: false,
      showModal: false,
      newPost: {
        title: "",
        f_name: "",
        l_name: "",
        email: "",
        gender: "",
        dob: "",
        phone_num: "",
      },
      editModal: false,
      editedPost: {
        id: null,
        title: "",
        f_name: "",
        l_name: "",
        gender: "",
        dob: "",
        phone_num: "",
        user_id: "",
        f_name: "",
      },
      posts: [],
      filters: {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      },
    };
  },
  mounted() {
    this.fetchPosts();
  },
  methods: {
    fetchPosts() {
      axios
        .get("teachers")
        .then((response) => {
        //   this.posts = response.data;
          const teacher = response.data.map((teacher, index) => {
             const email = teacher.user.email
             const fullName = teacher.f_name + ' ' + teacher.l_name
             return{
                ...teacher,
                email,
                fullName,
                sequenceNumber: index +1,
                index
             }
          })
          this.posts = teacher
          console.log(email);
        })
        .catch((error) => {
          console.error("Error fetching posts:", error);
        });
    },
    createPost() {
      axios
        .post("teachers", this.newPost)
        .then((response) => {
          console.log("Post created:", response.data);
          this.showModal = false;
          this.newPost.title = "";
          this.newPost.f_name = "";
          this.newPost.l_name = "";
          this.newPost.email = "";
          this.newPost.gender = "";
          this.newPost.dob = "";
          this.newPost.phone_num = "";
          this.fetchPosts();
          this.$toast.add({
            severity: "success",
            summary: "Add Successfully",
            detail: "New Teacher added",
            life: 3000,
          });
        })
        .catch((error) => {
          console.error("Error creating post:", error);
          // Handle error, maybe show an error message
        });
    },
    editPost(post) {
      this.editedPost.id = post.id;
      this.editedPost.title = post.title;
      this.editPost.f_name = post.f_name;
      this.editedPost.l_name = post.l_name;
      this.editedPost.gender = post.gender;
      this.editedPost.email = post.user.email;
      this.editedPost.dob = post.dob;
      this.editedPost.phone_num = post.phone_num;
      this.editedPost.user_id = post.user_id;
      this.editedPost.f_name = post.f_name;
      this.editModal = true;
    },
    updatePost() {
      axios
        .put(`teachers/${this.editedPost.id}`, this.editedPost)
        .then((response) => {
          console.log("Post updated:", response.data);
          this.editModal = false;
          this.fetchPosts();
          this.$toast.add({
            severity: "success",
            summary: "Update Successfully",
            detail: "Teacher updated Successfully",
            life: 3000,
          });
        })
        .catch((error) => {
          console.error("Error updating post:", error);
          this.$toast.add({
            severity: "error",
            summary: "Fail to edit",
            detail: "Failed",
            life: 3000,
          });
        });
    },
    async deletePostWithConfirmation(prod) {
      const result = await Swal.fire({
        title: "Are you sure?",
        text: ` ${prod.f_name} ${prod.l_name} will not be delete from system!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        reverseButtons: true,
      });

      if (result.isConfirmed) {
        this.deletePost(prod.id);
      }
    },
    async deletePost(postId) {
      try {
        const response = await axios.delete(`teachers/${postId}`);
        console.log("Post deleted:", response.data);
        this.fetchPosts();
        this.$toast.add({
          severity: "success",
          summary: "Delete Successfully",
          detail: "Teacher deleted Successfully",
          life: 3000,
        });
      } catch (error) {
        console.error("Error deleting post:", error);
        this.$toast.add({
          severity: "error",
          summary: "Fail delete",
          detail: "Failed",
          life: 3000,
        });
      }
    },
    openDialog() {
      this.visible = true;
    },
    exportHeaderCSV() {
      const csvHeaders = [
        "FirstName",
        "LastName",
        "Email",
        "Title",
        "Gender",
        "DateOfBirth",
        "Phone_Number",
      ];
      const csv = Papa.unparse([csvHeaders]);
      const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.download = "empty_template_teacher.csv";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      this.visible = false;
    },
    exportCSV() {
      const teacherData = this.posts.map((teacher) => ({
        NO: teacher.id,
        Title: teacher.title,
        FirstName: teacher.f_name,
        LastName: teacher.l_name,
        Gender: teacher.gender,
        DateOfBith: teacher.dob,
        Phone_Number: teacher.phone_num,
      }));
      this.posts = teacherData;
      const csv = Papa.unparse(this.posts);
      const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
      // Create a download link and trigger a click
      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.download = "Teacher Data.csv";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      this.fetchPosts();
    },
    importCSV() {
      // Trigger the file input click event
      this.$refs.fileInput.click();
    },

    async importTeachersFromCSV() {
      try {
        // Access the file input element using refs
        const fileInput = this.$refs.fileInput;

        // Check if a file is selected
        if (!fileInput.files.length) {
          // Handle case where no file is selected
          console.error("No file selected");
          return;
        }

        // Get the first selected file
        const file = fileInput.files[0];

        // Create a FormData object to send the file
        const formData = new FormData();
        formData.append("file", file);

        // Make a POST request to the backend endpoint
        const response = await axios.post("teachers_import", formData, {
          headers: {
            "Content-Type": "multipart/form-data", // Ensure proper content type for file uploads
          },
        });
        this.visible = false
        this.fetchPosts();

        // Handle successful response
        console.log("Import response:", response.data);

        // Show success message
        this.$toast.add({
          severity: "success",
          summary: "Import Successful",
          detail: "Teachers imported successfully",
          life: 3000,
        });
        this.visible = false;
      } catch (error) {
        // Handle errors, display error message, etc.
        console.error("Error importing CSV:", error);

        // Show error message
        this.$toast.add({
          severity: "error",
          summary: "Import Failed",
          detail: "Failed to import teachers",
          life: 3000,
        });
        this.visible = false
      }
    },

    async parseCSV(file) {
      return new Promise((resolve, reject) => {
        Papa.parse(file, {
          complete: (results) => resolve(results.data),
          error: (error) => reject(error),
        });
      });
    },

    // async parseCSV(file) {
    //   return new Promise((resolve, reject) => {
    //     Papa.parse(file, {
    //       header: true,
    //       complete: (results) => {
    //         resolve(results.data);
    //       },
    //       error: (error) => {
    //         reject(error);
    //       },
    //     });
    //   });
    // },
  },
};
</script>

<style scoped>
.nav-link {
  text-align: center;
  transition: background-color 0.3s;
}
.active-link {
  border-bottom: 2px solid #4299e1;
  color: #4299e1;
}
</style>


