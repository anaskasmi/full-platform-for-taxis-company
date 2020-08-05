<template>
    <div style="width: 100%">
        <!-- progress -->
        <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
        <div class="table-responsive" v-if="!isLoading">
            <table class="table table-hover table-striped " style="table-layout:fixed">
                <thead class="thead-dark">
                <tr class="bigFont">
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Number</th>
                    <th scope="col">Password</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="(vehicle,i) in vehicles" :key="i" class="bigFont">
                    <th class="text-uppercase">{{vehicle.type}}-{{vehicle.number}}</th>
                    <th>{{vehicle.type}}</th>
                    <th>{{vehicle.number}}</th>
                    <th class="text-primary">{{vehicle.password}}</th>
                    <td class="text-right">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn v-if="!reseting" outlined color="teal darken-3" v-on="on"
                                       @click="resetPassword(vehicle)">
                                    <v-icon color="teal darken-3" class="mx-1">autorenew</v-icon>
                                </v-btn>
                                <v-btn v-else outlined disabled color="teal darken-3" v-on="on"
                                      >
                                    <v-icon color="teal darken-3" class="mx-1">autorenew</v-icon>
                                </v-btn>
                            </template>
                            <span>Click To Reset the Vehicle Password</span>
                        </v-tooltip>
                    </td>
                </tr>


                </tbody>
            </table>
        </div>
    </div>
</template>
<script>

    export default {
        components: {},
        created() {
            this.fetchItems();
        },

        data() {
            return {
                vehicles: {},
                isLoading: false,
                reseting:false,
            }
        },
        computed: {},
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            compare( a, b ) {
                if ( parseInt(a.number, 10) < parseInt(b.number, 10) ) {
                    return -1;
                }
                if (parseInt(a.number, 10) > parseInt(b.number, 10) ){
                    return 1;
                }
                return 0;
            },
            fetchItems() {
                this.isLoading = true;

                let url = this.BASE_URL() + "/api/dispatcher/vehicles";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;

                axios
                    .get(url)
                    .then(res => {
                        this.vehicles = res.data;
                        this.vehicles.sort( this.compare );
                        this.isLoading = false;
                        if (!this.vehicles) {
                            this.$swal("No vehicles found!", "", "warning");

                        }
                    })
                    .catch(error => {
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            resetPassword(v)
            {
                this.reseting=true;
                let url = this.BASE_URL() + "/api/dispatcher/vehicles/resetVehiclesPassword/"+v.id;
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;

                axios
                    .get(url)
                    .then(res => {
                        this.reseting=false;
                        this.fetchItems();
                    })
                    .catch(error => {
                        this.reseting=false;
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });

            }
        },


    }
</script>


<style scoped>
    a:hover {
        text-decoration: none;
    }

    .fitCell {
        max-width: 100%;
        white-space: nowrap;
    }
    .bigFont{
        font-size: 1.4em;
    }

</style>
