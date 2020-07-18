<template>

    <div class="wrapper_box">
        <NavbarAdmin></NavbarAdmin>
        <Drawer></Drawer>

        <!-- header -->
            <div class="wrapper_header mt-10">

                <div
                    class="title_header text-uppercase" 
                >   
                    MLDs
                </div>
                <v-btn 
                    link
                    tile                    
                    class="ma-2 button_header" 
                    outlined 
                    color="success "
                    @click="addMld"
                        
                >
                    <v-icon left>mdi-pencil</v-icon> 
                    <div>
                    New Mld
                    </div> 
                </v-btn>
        </div>
                <hr>
<!-- search start -->
<div class="row">
        <div class="col-md-12 md-form active-cyan-2 mb-3" style="margin-bottom: -15px !important;">
                
                <form @submit.prevent='search(searchValue)'>
                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                                <button id="button-addon7" type="submit" class="btn btn-dark">
                                    <!-- <i class="fa fa-search"></i> -->
                                    <v-icon dark small>search</v-icon>
                                </button>
                        </div>
                        <input type="search" name ="searchValue" v-model="searchValue" placeholder="Search by name" aria-describedby="button-addon7" class="form-control">
                    </div>                
                </form>
        </div>
</div>
<!-- search end -->

        <!-- table -->
        <div class="table-responsive">
            <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr class="">
                            <th scope="col">Name</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Badge ID</th>
                            <th scope="col">Car</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
            <tbody>
                        
                    <tr v-for="mld in mlds" v-bind:key="mld.BadgeID" class="">
                            <td>{{mld.Name}}</td>
                            <td>{{mld.FirstName}}</td>
                            <td>{{mld.LastName}}</td>
                            <td>{{mld.BadgeID}}</td>
                            <td>{{mld.car}}</td>
                            <td class="text-right">
                                <v-icon
                                    color="success"
                                    class="mx-1"
                                    @click="editMld(mld)"
                                >
                                    edit
                                </v-icon>
                                
                                <v-icon
                                    color="red lighten-1" 
                                    class="mx-1"
                                    @click="deleteMld(mld.BadgeID,mld.Name)"
                                >
                                    delete
                                </v-icon>
                                <v-icon
                                    color="info" 
                                    class="mx-1"
                                     @click="showMld(mld)"
                                >
                                    person
                                </v-icon>

                            </td>
                        </tr>    
            
            </tbody>
            </table>
                        <v-alert 
                        v-if="noMldfound"
                        border="left"
                        tile
                        type="warning"
                        dense
                        dark
                        elevation="2"
                        class="mb-12"
                        align="center"
                        >
                        Sorry, No MLD Found !
                        </v-alert>
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

import NavbarAdmin from '@/js/components/navbars/Admin.vue';
import Drawer from '@/js/components/drawers/Admin.vue';

export default {
    beforeMount() {
        this.noMldfound = false;
        this.fetchItems();
    },
    components:{
        NavbarAdmin,
        Drawer,
    },
     data() {
            return {
            mlds: [],
            current_page:1,
            last_page :1,
            noMldfound : false,
            searchValue:'',
            };
  },
  methods: {
      BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },
    search(searchValue)
    {
            if(searchValue == '')
            {
                this.fetchItems();
                this.noMldfound = false;
                return ;
                
            }
            let vm = this;
            axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_admin;
axios.get(this.BASE_URL()+'/api/admin/mlds/search?searchValue='+searchValue)
            .then(res => {
                console.log(res.data);
                    if(res.data.data.length==0)
                    {
                        console.log('no drii');
                        this.noMldfound = true;
                        this.mlds = {};
                    }
                    else{
                        console.log("mld found meta =>");
                        console.log(res.data.meta);
                        this.noMldfound = false;
                        this.mlds = res.data.data;
                    }
                        vm.makePagination(res.data.meta, res.data.links);

            })
            .catch(error => {
                console.log('search errors : ');
                console.log(error);
                this.inputErrors = error.response.data.errors;
                 $('html, body').animate({scrollTop:0},'200');
            });
    },
    fetchItems(page_url)
    {
      let vm = this;
      page_url = page_url || this.BASE_URL()+'/api/admin/mlds';
        axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_admin;
axios.get(page_url)
        .then(res => {
            this.mlds = res.data.data;
            vm.makePagination(res.data.meta, res.data.links);

        })
    .catch(error => {
      console.log(error);
    })
    },


    makePagination(meta, links)
    {


      this.last_page = meta.last_page;
      this.current_page = meta.current_page;

    },



    next (page)
    {
        this.fetchItems(this.BASE_URL()+'/api/admin/mlds?page='+page);
    },

    BASE_URL()
    {
            return this.$store.state.BASE_URL;
    },


    deleteMld(id,name)
    {
            this.$swal.fire({
                title: 'Are you sure?',
                text: "Mld "+name+" will be deleted !",
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
        
                    axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_admin;
axios.delete(this.BASE_URL()+'/api/admin/mld/'+id)
                            .then(data => {
                                this.fetchItems();
                            })
                            .catch(err => console.log(err));
                            this.$swal.fire(
                            'Deleted!',
                            'Mld has been deleted.',
                            'success'
                                )
                        }
                    })
    },
 


    addMld() {
        this.$router.push({name:"AdminDashboard_mlds_add"})
    },



    showMld:function(mld){
                this.$router.push({name:"AdminDashboard_mlds_show",params:{id:mld.BadgeID}})
    },


    editMld(mld){

            this.$router.push({name:"AdminDashboard_mlds_edit",params:{id:mld.BadgeID}})
            
    },
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
</style>