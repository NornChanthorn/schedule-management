<template>
    <div>Here is your code course in major:{{ majorID }},  gen: {{ genID }}, term:{{ termID }}</div>
    <div>
        
    </div>


</template>
<script>
import axios from 'axios';
export default{
    data(){
        return{
            termID: null,
            genID: null,
            majorID: null,
            courses: [],

        }
    },
    mounted(){
        this.termID = this.$route.params.termId;
        this.genID = this.$route.params.genid;
        this.majorID = this.$route.params.majorId;
        this.getCourse(this.majorID, this.genID, this.termID);

    },
    methods:{
        getCourse(majorId, genId, termId){
            axios.get(`course/${majorId}/${genId}/${termId}`).then(
                res=>{
                    this.courses= res.data
                    console.log(this.courses)
                }
            ).catch(er=>{
                console.error(er)
            })

        },
        // getCourses(){
        //     axios.get('courses').then(
        //         res=>{
        //             // this.courses= res.data
        //             // console.log(this.courses)
        //         }
        //     )

        // }

    }

}

</script>
