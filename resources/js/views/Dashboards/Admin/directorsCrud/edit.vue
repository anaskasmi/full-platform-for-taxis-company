<template>
    <div>
         <NavbarAdmin></NavbarAdmin>
        <Drawer></Drawer>

        <!-- back button -->
       <div class="row col-12">
            <v-btn color="grey" text tile  class="float-left" :to="directorsRoute"> <v-icon class="mr-2">keyboard_return</v-icon>directors </v-btn>
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
    <form id="form" @submit.prevent="editDirector">    
      
      <v-alert 
      border="left"
      tile
      dense
      color="#1F7087"
      dark
      elevation="2"
      class="mt-12"
      align="center"
    >
      Director Informations
    </v-alert>
     <div class="form-group">
        <v-text-field :rules="rules" name="name" v-model="director.name" label="Name"></v-text-field>
     </div>

    
     <div class="form-group">
        <v-text-field :rules="rules" name="email"  v-model="director.email" label="email"></v-text-field>
     </div>
     




<div class="form-group">
        <v-card
            flat
            outlined
            max-width="100%"
            class="mx-auto"
        >
              <v-app-bar dark color="blue lighten-3" dense flat>
                          <v-toolbar-title dense>Director image</v-toolbar-title>
              </v-app-bar>

        <div class="row col-12">
              <v-card-text > 
                    <img style="max-width:20%;cursor:pointer;" class=" rounded float-left mr-10" :src="image" @click="openImage(image)">
                  <v-file-input
                      accept="image/png, image/jpeg, image/bmp"
                      placeholder="New Director image"
                      prepend-icon="mdi-camera"
                      name="image"
                      class=""
                  ></v-file-input>
              </v-card-text>
        </div>
        </v-card>
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
                v-model="director.password"
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
                v-model="director.password_confirmation"
            >
            </v-text-field>

            
        </div>
     </transition>
     <!-- submit btn -->
       <div class="row col-12 mt-4 ">
            <v-btn color="success" tile class="float-left" type="submit">submit</v-btn>
            <v-btn color="grey lighten-1"  dark tile  class="float-left mx-4" @click="$router.go(-1)">cancel</v-btn>
       </div>
       <input type="hidden" name="_method" value="patch">
    </form>

    </div>
</template>
<script>
import NavbarAdmin from '@/js/components/navbars/Admin.vue';
import Drawer from '@/js/components/drawers/Admin.vue';

export default {
    created:function(){
        this.id=this.$route.params.id;
        axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_admin;
axios.get(this.BASE_URL()+'/api/admin/director/'+this.id)
        .then(res => {
            this.director = res.data.data;
            this.PermitExpiry =res.data.data.PermitExpiry;
            this.LicenseExpiry =res.data.data.LicenseExpiry;
            this.modal_AbstractExpiry = res.data.data.modal_AbstractExpiry;
            this.AbstractExpiry =res.data.data.AbstractExpiry;
            this.START_DATE =res.data.data.START_DATE;
            this.END_DATE =res.data.data.END_DATE;
        })
        .catch(error => {
        console.log(error);
        })
        
    },
    components:{
        NavbarAdmin,
        Drawer,
    },
    data() {
        return {
            inputErrors:[],
            // PermitExpiry: new Date().toISOString().substr(0, 10),
            modal_PermitExpiry: false,
            // LicenseExpiry: new Date().toISOString().substr(0, 10),
            modal_LicenseExpiry: false,
            // AbstractExpiry: new Date().toISOString().substr(0, 10),
            modal_AbstractExpiry: false,
            // START_DATE: new Date().toISOString().substr(0, 10),
            modal_START_DATE: false,
            // END_DATE: new Date().toISOString().substr(0, 10),
            modal_END_DATE: false,
            show_password_assigne : false,
            showPassword:false,
            directorsRoute:{name : 'AdminDashboard_directors'},
            director:{}
            

        }
    },
    methods: {
        BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },
        openImage(url){
          console.log('clicked');
          var win = window.open(url, '_blank');
          win.focus();
        },
        editDirector(){
            var form = document.getElementById('form');
            var formData = new FormData(form);
            console.log('formdata ====> ');
            for (var pair of formData.entries()) {
                console.log(pair[0]+ ', ' + pair[1]); 
            }
            console.log(formData);
            axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_admin;
axios.post(this.BASE_URL()+'/api/admin/director/'+this.director.id,formData)
            .then(response => { 
                console.log("the response is :" +response);
                console.log("the id is " +response.data.data.id);
                this.$router.push({name:'AdminDashboard_directors_show',params:{id:response.data.data.id}})
                
            })
            .catch(error => {
                console.log(error.response.data.errors);
                this.inputErrors = error.response.data.errors;
                 $('html, body').animate({scrollTop:0},'200');

            });
            
        },

    },
    computed:{

              validationErrors(){
                  let errors = Object.values(this.inputErrors);
                  errors = errors.flat();
                  return errors;
              },
              image(){
                    if(this.director.image)
                    return this.BASE_URL()+'/storage/'+this.director.image;
                    else
                    return false;
              },
              
            
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