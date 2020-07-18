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
                               <v-icon class="mb-1" color="primary">description</v-icon> DIRECTORS COMMENTS
                            </v-alert>
    </div>
  <v-row>


    <v-expansion-panels inset>
      <v-expansion-panel
        v-for="(director_comment,i) in director_comments"
        :key="i"
      >
        <v-expansion-panel-header>
                <div class=" my-auto col-12" >
                    <h6 class="text-dark font-weight-bold my-auto float-left " >{{director_comment.name}} ~</h6> 
                    <div class="text-muted text-right my-auto "> 
                        <small>
                            Created at : {{director_comment.created_at}}
                        </small>
                    </div>
                </div>
        </v-expansion-panel-header>
        <v-expansion-panel-content>
            <a  class="">
                                <p class="mb-1 font-italic">
                                    {{director_comment.comment}}
                                </p>
                                <br>
                                <p class="text-muted text-right py-0 my-0"> 
                                    <small>
                                            Date : {{director_comment.date}}
                                    </small>
                                </p>
            </a>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
  </v-row>
  </div>

</template>
<script>
export default {
    props:{
        director_comments:{},
    }
}
</script>