<template>
    <div>
        <NavbarAdmin></NavbarAdmin>
        <Drawer></Drawer>

        <!-- back button -->
       <div class="row col-12">
            <v-btn color="grey" text tile  class="float-left" :to="dispatchersRoute"> <v-icon class="mr-2">keyboard_return</v-icon>dispatchers </v-btn>
       </div>
        <hr>

        <div v-if="validationErrors.length">

            <v-alert dense
                outlined
                type="warning" 
                border="left"
                v-for="error in validationErrors" 
                v-bind:key="error">
                   {{error}}
            </v-alert>
        <hr>
        </div>

        <!-- form -->
    <form id="form" @submit.prevent="storeDispatcher">
    <v-app-bar dark color="#1F7087" dense  flat class="mt-12 mb-5">
                <v-toolbar-title>Dispatcher Information</v-toolbar-title>
    </v-app-bar>
     <div class="form-group">
        <v-text-field :rules="rules" name="name" v-model="dispatcher.FirstName" label="Name"></v-text-field>
     </div>

    
     <div class="form-group">
        <v-text-field :rules="rules" name="email"  v-model="dispatcher.LastName" label="Email"></v-text-field>
     </div>

    <!-- files -->
    <div class="form-group">
        <!-- <h6><label for="Dispatcher_IMAGE">Dispatcher image</label></h6> -->
        <v-file-input
            accept="image/png, image/jpeg, image/bmp"
            placeholder="Dispatcher image"
            prepend-icon="mdi-camera"
            name="image"
        ></v-file-input>
    </div>


        

    <hr>
     <!-- assigne new password -->
     <v-btn
        class="pl-0"
        color="info"
        text
        @click="show_password_assigne = !show_password_assigne"
     >
                <!-- icon -->
                <v-icon>
                    arrow_drop_down
                </v-icon>
                <!-- text -->
                Assigne Password
     </v-btn>

     <!-- hint -->
        <v-chip
        color="warning"
        outlined
        class="text-muted text-small ma-2 " style="font-size:0.8em;"
        >
        <v-icon small left>warning</v-icon>
        If no password assigned, 'taxi-office' will be the password by default
        </v-chip>
     <!-- @if ($show_password_assigne) -->
     <transition name="component-fade" mode="out-in">

        <div v-if="show_password_assigne">
            <v-text-field
                v-model="dispatcher.password"
                :append-icon="showPassword ? 'visibility' : 'visibility_off'"
                :type="showPassword ? 'text' : 'password'"
                name="password"
                label="Password"
                hint="At least 8 characters"
                counter
                @click:append="showPassword = !showPassword"
            >
            </v-text-field>


            <v-text-field
                :type="showPassword ? 'text' : 'password'"
                name="password_confirmation"
                label="Confirm Password"
                v-model="dispatcher.password_confirmation"
            >
            </v-text-field>

            
        </div>
     </transition>
     <!-- @else
         
     @endif -->
     <!-- submit btn -->
       <div class="row col-12 mt-4 ">
            <v-btn color="success" tile class="float-left" type="submit">submit</v-btn>
            <v-btn color="grey lighten-1"  dark tile  class="float-left mx-4" @click="$router.go(-1)">cancel</v-btn>
       </div>
    </form>

    </div>
</template>
<script>
import NavbarAdmin from '@/js/components/navbars/Admin.vue';
import Drawer from '@/js/components/drawers/Admin.vue';

export default {
    components:{
        NavbarAdmin,
        Drawer,
    },
    data() {
        return {
            inputErrors:[],
            PermitExpiry: new Date().toISOString().substr(0, 10),
            modal_PermitExpiry: false,
            LicenseExpiry: new Date().toISOString().substr(0, 10),
            modal_LicenseExpiry: false,
            AbstractExpiry: new Date().toISOString().substr(0, 10),
            modal_AbstractExpiry: false,
            START_DATE: new Date().toISOString().substr(0, 10),
            modal_START_DATE: false,
            END_DATE: new Date().toISOString().substr(0, 10),
            modal_END_DATE: false,
            show_password_assigne : false,
            showPassword:false,
            dispatchersRoute:{name : 'AdminDashboard_dispatchers'},
            dispatcher:{
            FirstName:'',
            LastName:'',
            PermitNumber:'',
            LicenseNumber:'',
            PermitExpiry:'',
            LicenseExpiry:'',
            AbstractExpiry:'',
            HomePhone:'',
            TAXI_HOST:'',
            ADDRESS:'',
            email:'',
            START_DATE:'',
            END_DATE:'',
            Dispatcher_STATUS:'',
            LICENSE_CLASS:'',
            password:'',
            password_confirmation:'',
            }
            

        }
    },
    methods: {
        BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },
        storeDispatcher(){
            var form = document.getElementById('form');
            var formData = new FormData(form);
            for (var pair of formData.entries()) {
                console.log(pair[0]+ ', ' + pair[1]); 
            }
            axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_admin;
axios.post(this.BASE_URL()+'/api/admin/dispatcher',formData)
            .then(response => { 
                this.$router.push({name:'AdminDashboard_dispatchers_show',params:{id:response.data.data.id}})
                
            })
            .catch(error => {
                this.inputErrors = error.response.data.errors;
                // window.scrollTo(0,0);
                 $('html, body').animate({scrollTop:0},'200');

            });
            
        },

    },
    computed:{
                validationErrors(){
                    let errors = Object.values(this.inputErrors);
                    errors = errors.flat();
                    return errors;
                }
            
    }
}
</script>


<style scoped>
    .component-fade-enter-active, .component-fade-leave-active {
    transition: opacity .4s ease;
    }
    .component-fade-enter, .component-fade-leave-to
    /* .component-fade-leave-active below version 2.1.8 */ {
    opacity: 0;
    }
    a:hover{
      text-decoration: none;
    }
</style>