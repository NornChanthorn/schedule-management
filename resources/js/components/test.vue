<template>
    <p>This is a Fake Home Page</p>
    <h1 v-if="user.role==='admin'">Here is my admin {{user.name }} </h1>
    <h1 v-if="user.role==='teacher'">Hello, sir {{ user.name }}</h1>
    <Button @click="logout">
        Logout
    </Button>

</template>
<script>
import axios from 'axios';
export default{
    data(){
        return{
            user: []
        }
    },

    mounted(){
        this.userInfo();
    },
    methods:{
        userInfo(){
            axios.get('/api/user')
                .then(response => {
                    // Successfully fetched user information
                    this.user = response.data;
                })
                .catch(error => {
                    // Handle error
                    console.error('Error fetching user information:', error);
                });

        },
        logout() {
            axios.post('/api/logout')
                .then(response => {
                    localStorage.removeItem('authToken');
                    console.log('Logout successful');
                    this.$router.push({ path: '/login' });
                })
                .catch(error => {
                    console.error('Logout error:', error);
                });
        }

    }

}

</script>
