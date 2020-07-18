<template>
<div>
      <div>
                            <v-alert 
                                border="left"
                                tile
                                dense
                                dark
                                color="primary "
                                elevation="2"
                                class="mt-12"
                                align="center"
                                
                            >
                               <v-icon class="mb-1" color="primary">description</v-icon> Weekly Shifts
                            </v-alert>
    </div>

                
                
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                   <tr>
                    <th scope="col">Driver</th>
                    <th scope="col">Taxi Number</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">End Time</th>
                    <th scope="col">Total Time <small class="text-light">(min)</small></th>
                    <th scope="col">Break Time <small class="text-light">(min)</small></th>
                    <th scope="col">Work Time  <small class="text-light">(min)</small></th>
                </tr>
                </thead>


                 <tbody>
                     <tr
                        v-for="(shift,i) in shifts"
                        :key="i"
                        >
                        <td v-if="shift.is_total_row=='0'">{{shift.FirstName}} {{shift.LastName}}</td>
                        <td v-if="shift.is_total_row=='0'">{{shift.vehicle_id}}</td>
                        <td v-if="shift.is_total_row=='0'">{{shift.shift_start_time}}</td>
                        <td v-if="shift.is_total_row=='0'">{{shift.shift_end_time}}</td>
                        <td v-if="shift.is_total_row=='0'">{{shift.total_time}}</td>
                        <td v-if="shift.is_total_row=='0'">{{shift.break_time}}</td>
                        <td v-if="shift.is_total_row=='0'">{{shift.work_time}}</td>
                        <!-- is total row 1 -->
                        <td class="table-primary text-uppercase" v-if="shift.is_total_row=='1'"  colspan="4" >{{shift.FirstName}} {{shift.LastName}} TOTAL </td>
                        <td class="table-primary text-uppercase" v-if="shift.is_total_row=='1' && shift.total_time_hours==0 && shift.total_time_min==0" >-</td>
                        <td class="table-primary " v-else-if="shift.is_total_row=='1'">{{shift.total_time_hours}}H: {{shift.total_time_min}} Min</td>
                        <td class="table-primary text-uppercase" v-if="shift.is_total_row=='1' && shift.break_time_hours==0 && shift.break_time_min ==0">-</td>
                        <td class="table-primary " v-else-if="shift.is_total_row=='1'">{{shift.break_time_hours}}H: {{shift.break_time_min}}Min</td>
                        <td class="table-primary text-uppercase" v-if="shift.is_total_row=='1' && shift.work_time_hours==0 && shift.work_time_min ==0">-</td>
                        <td class="table-primary " v-else-if="shift.is_total_row=='1'">{{shift.work_time_hours}}H: {{shift.work_time_min}}Min</td>
                        </tr>


    </tbody>
    </table>
    
    
    </div>
        <v-pagination 
            :total-visible="5"
            v-model="current_page"
            :length="last_page"
            @input="next"
        >
        </v-pagination>

  </div>
</template>
<script>
export default {
    created(){
        this.fetchItems()
    },
    props:{
        date:'',
    },
    data() {
        return {
            last_page:'',
            current_page:'',
            shifts:{},

        }
    },
    methods: {
        BASE_URL()
        {
            return this.$store.state.BASE_URL;
        },
        next (page)
        {
            this.fetchItems(this.BASE_URL()+'/api/admin/reports/WeeklyDriverHours/list?date='+this.date+'&page='+page);

        },
        fetchItems(URL)
        {
            axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_admin;
axios.get(URL || this.BASE_URL()+'/api/admin/reports/WeeklyDriverHours/list?date='+this.date)
            .then(res=>{
                this.shifts=res.data.shifts.data;
                this.last_page=res.data.shifts.last_page;
                this.current_page=res.data.shifts.current_page;
            }).catch(error=>{
                console.log('error in fetch shifts child drovers hours: ' +error);
            })
        },
    },
}
</script>