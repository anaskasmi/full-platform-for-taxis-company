<template>

    <div class="wrapper_box">
        <NavbarAdmin></NavbarAdmin>
        <Drawer></Drawer>

        <!-- header -->
     <!-- header -->
            <div class="wrapper_header mt-10">

                <div
                    class="title_header text-uppercase float-left" 
                >   
                    Weekly Taxi Shifts
                </div>
            </div>
            <div class="title_header text-uppercase float-left" >
                    <v-btn color="grey" text tile  class="float-left" :to="backRoute"> <v-icon class="mr-2">keyboard_return</v-icon>Reports </v-btn>
            </div>


                <hr>

                <div v-if="datePickerShow" >
                        <div class="sentence">
                            Pick a week !
                        </div>
                    <div v-if="datePickerShow">
                            <v-alert 
                                border="left"
                                tile
                                dense
                                color="#grey lighten-5 "
                                elevation="2"
                                class="mt-12 font-weight-black"
                                align="center"
                                
                            >
                                {{startOfWeek(date)}} <v-icon class="mb-1" color="primary">keyboard_arrow_left</v-icon>  <v-icon class="mb-1" color="primary">keyboard_arrow_right</v-icon>{{endOfWeek(date)}}
                            </v-alert>
                    </div>
                        <div>
                                <v-date-picker
                                v-model="date"
                                full-width
                                show-current='false'
                                :landscape="$vuetify.breakpoint.smAndUp"
                                class="mt-4"
                                ></v-date-picker>
                        </div>
                        <v-btn class="my-2" block color="primary" @click="fetchItems()">
                            Get repport
                        </v-btn>
                </div>
                      <div v-if="noShiftsFound">
                            <v-alert 
                                border="left"
                                tile
                                dense
                                type="warning"
                                elevation="2"
                                class="mt-0 font-weight-medium"
                                align="center"
                                
                            >
                                Sorry no shifts Found! try another Week!
                            </v-alert>
                    </div>
                <director_comments v-if="director_commentsShow" :director_comments="director_comments"></director_comments>
                <specialShifts v-if="specialShiftsShow" :specialShifts="specialShifts"></specialShifts>
                <shifts v-if="shiftsShow" :date="date"></shifts>



    </div>
</template>
<script>

import NavbarAdmin from '@/js/components/navbars/Admin.vue';
import Drawer from '@/js/components/drawers/Admin.vue';
import director_comments from '@/js/views/Dashboards/Admin/reports/weeklyTaxiShifts/director_comments.vue';
import specialShifts from '@/js/views/Dashboards/Admin/reports/weeklyTaxiShifts/specialShifts.vue';
import shifts from '@/js/views/Dashboards/Admin/reports/weeklyTaxiShifts/shifts.vue';


export default {
    created() {
        this.datePickerShow= true;

    },
    
    components:{
        NavbarAdmin,
        director_comments,
        specialShifts,
        shifts,
        Drawer,


    },

    data() {
            return {
                director_comments:null,
                specialShifts:null,
                shifts:null,
                dateTitle:'',
                datePickerShow:true,
                dateTitleShow:false,
                date: new Date().toISOString().substr(0, 10),
                noShiftsFound : false,
 

            };
    },
    computed:{
        backRoute(){
            return {name : 'AdminDashboard_reports'};
        },
        director_commentsShow(){
            if(this.director_comments)
            {
                if(this.director_comments.length != 0)
                {
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        },
        specialShiftsShow(){
            if(this.specialShifts)
            {
                if(this.specialShifts.length != 0)
                {
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        },
        shiftsShow(){
            if(this.shifts)
            {
                if(this.shifts.data.length != 0)
                {
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
    },
    methods: {
        BASE_URL()
        {
            return this.$store.state.BASE_URL;
        },

        fetchItems()
        {
            axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_admin;
axios.get(this.BASE_URL()+'/api/admin/reports/WeeklyTaxiShifts/list?date='+this.date)
            .then(res=>{
                console.log('response is :');
                console.log(res.data);

                if(res.data.director_comments.length != 0 ||res.data.specialShifts.length != 0 ||res.data.shifts.data.length != 0  )
                {
                    this.datePickerShow = false;
                    this.noShiftsFound = false;

                }
                else{
                     this.datePickerShow = true;
                     this.noShiftsFound = true;
                }
                this.director_comments=res.data.director_comments;
                this.specialShifts=res.data.specialShifts;
                this.shifts=res.data.shifts;
            }).catch(error=>{
                console.log('error in fetch items weekly taxishifts: ' +error);
            })
        },
        startOfWeek (d){
                return this.$moment(d).startOf('isoWeek').format('YYYY-MM-DD');//previous monday
        },
        endOfWeek (d){
                return this.$moment(d).day(7).format('YYYY-MM-DD');//next sunday
        },
        dateRange(d){
             return this.startOfWeek (d)+' - '+this.endOfWeek (d);
             }

    }
}
</script>
<style scoped>
    .wrapper_box{
        display: grid;
        grid-gap: 2em;
        grid-auto-rows: minmax(10px,auto);
    }
    .search_box{
        background-color: rgb(255, 51, 61);
    }
    .table_box{
        background-color: rgb(65, 35, 150);
    }
    .pagination_box{
        background-color: rgb(255, 153, 0);
    }
    a, a:hover{
        text-decoration: none;
    }
       .wrapper_header{
        display: grid;
        grid-template-columns: 10fr 2fr;
        grid-auto-rows: minmax(50px,auto);
        align-items: center;
        /* background-color: rgb(255, 153, 0); */

    }
    .title_header{
        justify-self: start;
        font-family: 'roboto';
        font-size: 3em;
        color:rgb(124, 124, 124);

    }
    .button_header{
        justify-self: end;
    }
    .sentence{
    margin-top: 2em;
    font-family: 'Quicksand', sans-serif;
    justify-self: center;
    font-size: 2.5em;
  }
</style>