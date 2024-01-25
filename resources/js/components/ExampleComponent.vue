<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an {{users}}.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

    export default {
        data(){
            return{
              users: []
            }
        },
        mounted() {
            this.showUser();
        },
        methods:{
            showUser(){
                axios.get('/api/user').then(
                    res=>{
                    this.users= res.data.data.user
                    console.log(this.users)
                    }
                )
                }
        }
    }
</script>
