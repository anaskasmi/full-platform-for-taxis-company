<template>
    <div>
        <NavbarDispatcher></NavbarDispatcher>
        <Drawer></Drawer>


            <v-btn color="grey" text tile  class="float-left" :to="driversRoute"> <v-icon class="mr-2">keyboard_return</v-icon>drivers </v-btn>
            <v-btn color="success" outlined  class="float-right mx-4" :to="{name : 'DispatcherDashboard_drivers_edit',params:{id:id}}" > <v-icon class="mr-2">edit</v-icon> edit </v-btn>
            <v-btn color="red " outlined  class="float-right" @click="deleteDriver(driver.PermitNumber,driver.Name)">  <v-icon class="mr-2">delete</v-icon> delete </v-btn>

<div class="table-responsive">
            <div class="row col-12">

             <div class="col-5">
                        </div>
                        <div class="col-2" v-if="profileImage !== false">
                              <a :href="profileImage">
                                    <img :src="profileImage" alt=""  class="img-fluid img-thumbnail rounded ">
                              </a>
                        </div>
                        <div class="col-5">
                        </div>
                        
                        <div class="col-5" >
                              </div>
                              <div class="col-2" v-if="profileImage === false">
                                    <a :href="BASE_URL()+'/storage/uploads/IMAGES/man.png'">
                                          <img :src="BASE_URL()+'/storage/uploads/IMAGES/man.png'" alt=""  class="img-fluid rounded ">
                                    </a>
                              </div>
                              <div class="col-5">
                  </div> 
                  
                  </div>
                  <br>
            <table class="table table-bordered table-hover">
                  <tbody>

                  <tr>
                        <td style="width: 30%;">Name</td>
                        <td>{{driver.Name}}</td>
                  </tr>
                  <tr>
                        
                        <td>First Name</td>
                        <td> {{driver.FirstName}}</td>
                  </tr>
                  <tr>
                        
                        <td>Last Name</td>
                        <td> {{driver.LastName}}</td>
                  </tr>


                  <tr>
                        <td>permit Number</td>
                        <td>{{driver.PermitNumber}}</td>
                  </tr>
                  <tr>

                        <td>License Number</td>
                        <td>{{driver.LicenseNumber}}</td>
                  </tr>
                  <tr>

                        <td>License Expiry</td>
                        <td>{{driver.LicenseExpiry}}</td>
                  </tr>
                  <tr>

                        <td>Permit Expiry</td>
                        <td>{{driver.PermitExpiry}}</td>
                  </tr>
                  <tr>

                        <td>Abstract Expiry</td>
                        <td>{{driver.AbstractExpiry}}</td>
                  </tr>
                  <tr>

                        <td>Home Phone</td>
                        <td>{{driver.HomePhone}}</td>
                  </tr>
                  <tr>

                        <td>TAXI HOST</td>
                        <td>{{driver.TAXI_HOST}}</td>
                  </tr>
                  <tr>

                        <td>ADDRESS</td>
                        <td>{{driver.ADDRESS}}</td>
                  </tr>
                  <tr>

                        <td>EMAIL</td>
                        <td>{{driver.email}}</td>
                  </tr>
                  <tr>

                        <td>START DATE</td>
                        <td>{{driver.START_DATE}}</td>
                  </tr>
                  <tr>

                        <td>END DATE</td>
                        <td>{{driver.END_DATE}}</td>
                  </tr>
                  <tr>

                        <td>DRIVER STATUS</td>
                        <td>{{driver.DRIVER_STATUS}}</td>
                  </tr>
                  <tr>

                        <td>LICENSE CLASS</td>
                        <td>{{driver.LICENSE_CLASS}}</td>
                  </tr>
                  <!-- start of papers -->
                  <tr>
                        <td class="text-uppercase">LICENSE FILE</td>
                        <td>                              
                              <a v-if="driver.LICENSE_PATH" :href="LICENSE_PATH" target="_blank">
                                    <img style="max-width:40%;" class="img-thumbnail rounded" :src="LICENSE_PATH">
                              </a>
                              <div v-if="LICENSE_PATH===false" class="text-muted"> - </div>
                        </td>
                  </tr>

                  <tr>
                        <td class="text-uppercase">PERMIT FILE</td>
                        <td>                              
                              <a v-if="driver.PERMIT_PATH" :href="PERMIT_PATH" target="_blank">
                                    <img style="max-width:40%;" class="img-thumbnail rounded" :src="PERMIT_PATH">
                              </a>
                              <div v-if="PERMIT_PATH===false" class="text-muted"> - </div>
                        </td>
                  </tr>

                  <tr>
                        <td class="text-uppercase">TAXIHOST FILE</td>
                        <td>                              
                              <a v-if="driver.TAXIHOST_PATH" :href="TAXIHOST_PATH" target="_blank">
                                    <img style="max-width:40%;" class="img-thumbnail rounded" :src="TAXIHOST_PATH">
                              </a>
                              <div v-if="TAXIHOST_PATH===false" class="text-muted"> - </div>
                        </td>
                  </tr>

                  <tr>
                        <td class="text-uppercase">ABSTRACT FILE</td>
                        <td >                              
                              <a v-if="driver.ABSTRACT_PATH" :href="ABSTRACT_PATH" target="_blank">
                                    <img style="max-width:40%;" class="img-thumbnail rounded" :src="ABSTRACT_PATH">
                              </a>
                              <div  v-if="ABSTRACT_PATH===false" class="text-muted"> - </div>
                        </td>
                  </tr>

                  <tr>
                        <td class="text-uppercase">SP FILE</td>
                        <td >
                              
                              <a v-if="driver.SP_FILE_PATH" :href="SP_FILE_PATH" target="_blank">
                                    <img style="max-width:40%;" class="img-thumbnail rounded" :src="SP_FILE_PATH">
                              </a>
                              <div v-if="SP_FILE_PATH===false" class="text-muted"> - </div>
                        </td>
                  </tr>
                  <!-- end of papers -->

                  <tr>

                        <td>created at</td>
                        <td>{{driver.created_at}}</td>
                  </tr>
                  

</tbody>
</table>
</div>
    </div>
</template>
<script>
import NavbarDispatcher from '@/js/components/navbars/Dispatcher.vue';
import Drawer from '@/js/components/drawers/Dispatcher.vue';

export default {
    components: {
        NavbarDispatcher,
        Drawer,
    },
    created(){
        this.id=this.$route.params.id;
        axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_dispatcher;
axios.get(this.BASE_URL()+'/api/dispatcher/driver/'+this.id)
        .then(res => {
            this.driver = res.data.data;
        })
        .catch(error => {
        console.log(error);
        })
    },
        beforeDestroy()
    {
        this.$store.commit('showNavbarDispatcher_off');
    },
    data() {
        return {
            driver:{},
            id:'',
            driversRoute:{name : 'DispatcherDashboard_drivers'},

        }
    },
    computed:{
      
      profileImage(){
            if(this.driver.image)
            return this.BASE_URL()+'/storage/'+this.driver.image;
            else
            return false;
      },
      LICENSE_PATH(){
            if(this.driver.LICENSE_PATH)
            return this.BASE_URL()+'/storage/'+this.driver.LICENSE_PATH;
            else
            return false;
      },
      PERMIT_PATH(){
            if(this.driver.PERMIT_PATH)
            return this.BASE_URL()+'/storage/'+this.driver.PERMIT_PATH;
            else
            return false;
      },
      TAXIHOST_PATH(){
            if(this.driver.TAXIHOST_PATH)
            return this.BASE_URL()+'/storage/'+this.driver.TAXIHOST_PATH;
            else
            return false;
      },
      ABSTRACT_PATH(){
            if(this.driver.ABSTRACT_PATH)
            return this.BASE_URL()+'/storage/'+this.driver.ABSTRACT_PATH;
            else
            return false;
      },
      SP_FILE_PATH(){
            if(this.driver.SP_FILE_PATH)
            return this.BASE_URL()+'/storage/'+this.driver.SP_FILE_PATH;
            else
            return false;
      },
    },
    methods: {
          BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },
      deleteDriver(id,name){
            this.$swal.fire({
                title: 'Are you sure?',
                text: "Driver "+name+" will be deleted !",
                type: 'question',
                animation:false,
                focusConfirm:false,
                padding:"2rem",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#424242',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.value) {
                    axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_dispatcher;
axios.delete(this.BASE_URL()+'/api/dispatcher/driver/'+id)
                            .then(data => {
                                this.$router.push(this.driversRoute);
                            })
                            .catch(err => console.log(err));
                            this.$swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                                )
                        }
                    })
        }
    },
    
    
            
            
            
            
            



}
</script>


<style scoped>
    a:hover{
        text-decoration: none;
    }
    





</style>