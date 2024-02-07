<template>
  <Header>
    <div class="content-container">
      <div class="font-istok bg-white">
        <div class="flex p-4 space-x-20">
          <div class="flex text-4xl font-bold text-custom-color">
            <h1 class="mr-5">Gen 7, </h1><span>CS</span>
          </div>
          <div class="flex items-ceter justify-center space-x-10">
              <router-link to="/studentlist" class="text-black hover:underline hover:text-blue-500">All Students</router-link>
              <router-link to="/ga" class="text-black hover:underline hover:text-blue-500">GA</router-link>
              <router-link to="/gb" class="text-black hover:underline hover:text-blue-500">GB</router-link>
          </div>
        </div>
      </div>
      <div class="max-h-96 overflow-y-auto">
        <table class="min-w-full bg-white border-gray-300 text-left">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b">No</th>
              <th class="py-2 px-4 border-b">ID</th>
              <th class="py-2 px-4 border-b">Student Name</th>
              <th class="py-2 px-4 border-b">Date of Birth</th>
              <th class="py-2 px-4 border-b">Email</th>
              <th class="py-2 px-4 border-b">Phone Number</th>
              <th class="py-2 px-4 border-b">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="py-2 px-4">1</td>
              <td class="py-2 px-4">123456</td>
              <td class="py-2 px-4">John Doe</td>
              <td class="py-2 px-4">1990-01-01</td>
              <td class="py-2 px-4">john.doe@example.com</td>
              <td class="py-2 px-4">123-456-7890</td>
              <td class="py-2 px-4">
                <span class="cursor-pointer text-blue-500 hover:text-blue-700 mr-2" @click="editStudent">
                  <i class="fas fa-edit"></i>
                </span>
                <span class="cursor-pointer text-red-500 hover:text-red-700" @click="removeStudent">
                  <i class="fas fa-trash-alt"></i>
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </Header>
</template>


<script>
export default {
  methods: {
    editStudent() {
      console.log('Edit student clicked');
    },
    removeStudent() {
      console.log('Remove student clicked');
    },
  }
}
</script>