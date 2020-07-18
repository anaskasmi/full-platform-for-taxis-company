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
                               <v-icon class="mb-1" color="primary">description</v-icon> SPECIAL SHIFTS
                            </v-alert>
    </div>
  <v-row>


    <v-expansion-panels inset>
      <v-expansion-panel
        v-for="(specialShift,i) in specialShifts"
        :key="i"
      >
      <!-- openshift -->
      <div  v-if="specialShift.ShiftType == 'openshift' "> 
          <!-- title -->
          <v-expansion-panel-header>
                  <div class=" my-auto col-12" >
                      <h6 class="text-dark font-weight-bold my-auto float-left " > {{specialShift.FirstName}} {{specialShift.LastName}}~</h6> 
                      <div class="text-muted text-right my-auto "> 
                          <small>
                              Open shift
                          </small>
                      </div>
                  </div>
          </v-expansion-panel-header>        
          
          


          <!-- body -->
          <v-expansion-panel-content>

                                <p class="mb-1">Badge ID : {{specialShift.PermitNumber}}</p>
                                <p class="mb-1">Driver Sing in time : {{specialShift.SingInTime}}</p>
                                <p class="mb-1">Vehicle ID : {{specialShift.Car}}</p>
         
          </v-expansion-panel-content>
      </div>



















      <!-- coveringshift -->
      <div  v-if="specialShift.ShiftType == 'coveringshift' ">
        <!-- title -->
        <v-expansion-panel-header>
                <div class=" my-auto col-12" >
                    <h6 class="text-dark font-weight-bold my-auto float-left " >{{specialShift.FirstName}} {{specialShift.LastName}} ~</h6> 
                    <div class="text-muted text-right my-auto "> 
                        <small>
                            Covering shift
                        </small>
                    </div>
                </div>
        </v-expansion-panel-header>


        <!-- body -->
        <v-expansion-panel-content>

                                <p class="mb-1">Badge ID : {{specialShift.PermitNumber}}</p>
                                <p class="mb-1">Driver Sing in time : {{specialShift.SingInTime}}</p>
                                <p class="mb-1">Vehicle covered : {{specialShift.Car}}</p>
                                <p class="mb-1">Vehicle covered by : {{specialShift.CarCoveredBy}}</p>


        </v-expansion-panel-content>
      </div>

      </v-expansion-panel>
    </v-expansion-panels>
  </v-row>
  </div>

</template>
<script>
export default {
    props:{
        specialShifts:{},
    }
}
</script>