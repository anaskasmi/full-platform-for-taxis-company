<template>

    <div class="wrapper_box">
        <NavbarDirector></NavbarDirector>
        <Drawer></Drawer>

        <!-- header -->
            <div class="wrapper_header mt-10">

                <div
                    class="title_header text-uppercase" 
                >   
                    Comments
                </div>
                <v-btn 
                    link
                    tile                    
                    class="ma-2 button_header" 
                    outlined 
                    color="success "
                    @click="addComment"
                        
                >
                    <v-icon left>mdi-pencil</v-icon> 
                    <div>
                    New Comment
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
                                                <button  type="submit" class="btn btn-dark"  >
                                                    <!-- <i class="fa fa-search"></i> -->
                                                    <v-icon dark small>search</v-icon>
                                                </button>
                                        </div>
                                        <input type="search" name ="searchValue" v-model="searchValue"   placeholder="Search by comment content or date" aria-describedby="button-addon7" class="form-control">
                                    </div>                
                                </form>
                        </div>
                </div>
                <!-- search end -->


        <div class="table-responsive">
            <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr class="row col-12">
                            <th scope="col" class="col-md-9 col-sm-5 ">Comment</th>
                            <th scope="col" class="col-md-1 col-sm-3">Date</th>
                            <th class="text-center col-md-2 col-sm-4" scope="col"></th>
                        </tr>
                    </thead>
            <tbody>
                    <tr v-for="comment in comments" v-bind:key="comment.id" class="row col-12" >
                            <td class="col-md-9 col-sm-5">{{comment.comment}}</td>
                            <td class="col-md-1 col-sm-3">{{comment.date}}</td>
                            <td class="text-right col-md-2 col-sm-4">
                                <v-icon
                                    color="success"
                                    class="mx-1"
                                    @click="editComment(comment)"
                                >
                                    edit
                                </v-icon>
                                
                                <v-icon
                                    color="red lighten-1" 
                                    class="mx-1"
                                    @click="deleteComment(comment.id)"
                                >
                                    delete
                                </v-icon>
                                <v-icon
                                    color="info" 
                                    class="mx-1"
                                     @click="showComment(comment)"
                                >
                                    person
                                </v-icon>

                            </td>
                        </tr>    
            
            </tbody>
            </table>
                        <v-alert 
                        v-if="noCommentfound"
                        border="left"
                        tile
                        type="warning"
                        dense
                        dark
                        elevation="2"
                        class="mb-12"
                        align="center"
                        >
                        Sorry, No Comment Found !
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

import NavbarDirector from '@/js/components/navbars/Director.vue';
import Drawer from '@/js/components/drawers/Director.vue';

export default {
    beforeMount() {
        this.fetchItems();
    },
    components:{
        NavbarDirector,
        Drawer,
    },
     data() {
            return {
            comments: [],
            current_page:1,
            last_page :1,
            noCommentfound : false,
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
            this.noCommentfound = false;
            return ;
            
        }
        let vm = this;
            axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_director;
axios.get(this.BASE_URL()+'/api/director/comments/search?searchValue='+searchValue)
            .then(res => {
                console.log(res.data);
                    if(res.data.data.length==0)
                    {
                        this.noCommentfound = true;
                        this.comments = {};
                    }
                    else{
                        console.log("comment found meta =>");
                        console.log(res.data.meta);
                        this.noCommentfound = false;
                        this.comments = res.data.data;
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
    fetchItems(page_url) {
      let vm = this;
      page_url = page_url || this.BASE_URL()+'/api/director/comments';
        axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_director;
axios.get(page_url)
        .then(res => {
            this.comments = res.data.data;
            vm.makePagination(res.data.meta, res.data.links);

        })
    .catch(error => {
      console.log(error);
    })
    },


    makePagination(meta, links) {


      this.last_page = meta.last_page;
      this.current_page = meta.current_page;

    },



    next (page) {
        this.fetchItems(this.BASE_URL()+'/api/director/comments?page='+page);
    },



    deleteComment(id){
            this.$swal.fire({
                title: 'Are you sure?',
                text: "Comment of ID "+id+" will be deleted !",
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
        
                    axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_director;
axios.delete(this.BASE_URL()+'/api/director/comment/'+id)
                            .then(data => {
                                console.log(data);
                                this.fetchItems();
                            })
                            .catch(err => console.log(err));
                            this.$swal.fire(
                            'Deleted!',
                            'Comment has been deleted.',
                            'success'
                                )
                        }
                    })
    },



    addComment() {
        this.$router.push({name:"DirectorDashboard_comments_add"})
    },



    showComment:function(comment){
                this.$router.push({name:"DirectorDashboard_comments_show",params:{id:comment.id}})
    },


    editComment(comment){

            this.$router.push({name:"DirectorDashboard_comments_edit",params:{id:comment.id}})
            
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