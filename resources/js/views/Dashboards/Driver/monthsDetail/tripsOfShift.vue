<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">
            <div>
                <NavbarDriver></NavbarDriver>
                <Drawer></Drawer>

                <v-btn color="grey" text tile class="float-left" @click="$router.go(-1)">
                    <v-icon class="mr-2">keyboard_return</v-icon>
                    back to shifts
                </v-btn>
                <br>
                <br>
                <hr>
                <div>
                    <v-alert
                        border="left"
                        tile
                        dense
                        dark
                        color="primary "
                        elevation="2"
                        class="mt-12  text-uppercase"
                        align="center"
                    >
                        <v-icon class="mb-1" color="primary">description</v-icon>
                        Trips of the selected shift
                    </v-alert>
                </div>

                <!-- time of shift -->
                <v-alert
                    border="left"
                    tile
                    dense
                    color="#grey lighten-5 "
                    elevation="2"
                    class=" font-weight-black"
                    align="center"
                >
                    {{start_time}}
                    <v-icon class="mb-1" color="primary">keyboard_arrow_left</v-icon>
                    <v-icon class="mb-1" color="primary">keyboard_arrow_right</v-icon>
                    {{end_time}}
                </v-alert>
                <!-- progress bar -->
                <v-progress-linear
                    v-if="isLoading"
                    indeterminate
                    color="cyan"
                ></v-progress-linear>
                <!-- table -->
                <div class="table-responsive-lg mt-10" v-if="!isLoading">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th class="align-middle" scope="col">JOBID</th>
                            <th class="align-middle" scope="col">LOAD TIME</th>
                            <th class="align-middle" scope="col">UNLOAD TIME</th>
                            <th class="align-middle" scope="col">CUSTOMER</th>
                            <th class="align-middle" scope="col">PICK UP</th>
                            <th class="align-middle" scope="col">DROP OFF</th>
                            <th class="align-middle" scope="col" style="max-width:100%;white-space:nowrap;">FARE</th>
                            <th class="align-middle" scope="col" style="max-width:100%;white-space:nowrap;">TIP</th>
                            <th class="align-middle" scope="col" style="max-width:100%;white-space:nowrap;">TOTAL</th>
                            <th class="align-middle" scope="col">TRIP TYPE</th>
                            <th class="align-middle" scope="col">ACCOUNT NAME</th>
                            <th class="align-middle" scope="col">ACCOUNT NUMBER</th>
                            <th class="align-middle" scope="col">PO</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="trip in trips" v-bind:key="trip.trip_id">
                            <td>{{trip.job_id}}</td>
                            <td>{{trip.pickup_date}}</td>
                            <td>{{trip.dropoff__date}}</td>
                            <td>{{trip.customer_name}}</td>
                            <td>{{trip.pickup}}</td>
                            <td>{{trip.dropoff}}</td>
                            <td style="max-width:100%;white-space:nowrap;">$ {{trip.fare}}</td>
                            <td style="max-width:100%;white-space:nowrap;">$ {{trip.tip}}</td>
                            <td style="max-width:100%;white-space:nowrap;">$ {{trip.total}}</td>
                            <td>{{trip.account}}</td>
                            <td></td>
                            <td></td>
                            <td>{{trip.po}}</td>
                        </tr>
                        <tr class="table table-borderless">
                            <td colspan="6" class="table-success" style="font-size: 1.2em;">Totals</td>
                            <td
                                class="table-primary font-weight-bold"
                                style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;"
                            >$ {{shiftFare}}
                            </td>
                            <td
                                class="table-primary font-weight-bold"
                                style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;"
                            >$ {{shiftTip}}
                            </td>
                            <td
                                class="table-primary font-weight-bold"
                                style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;"
                            >$ {{shiftTotal}}
                            </td>
                            <td colspan="3" class="table-success" style="font-size: 1.2em;">Number of Trips</td>
                            <td class="table-primary font-weight-bold">{{nbr_trips}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- alert -->
                <v-alert
                    v-if="noTripsFound"
                    border="left"
                    tile
                    type="warning"
                    dense
                    dark
                    elevation="2"
                    class="mb-12"
                    align="center"
                >Sorry, No trips Found For this Month!
                </v-alert>
            </div>
        </div>
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <!----------------------------Mobile Version---------------------------->
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->

        <div class="pa-0 ma-0 " v-if="$mq=='mobile'">
            <!-- nav -->
            <NavbarDriver></NavbarDriver>

            <div class="container px-4">
                <div>

                    <v-btn color="grey" text tile class="float-left" @click="$router.go(-1)">
                        <v-icon class="mr-2">keyboard_return</v-icon>
                        back to shifts
                    </v-btn>
                    <br>
                    <br>
                    <hr>
                    <div>
                        <v-alert
                            border="left"
                            tile
                            dense
                            dark
                            color="primary "
                            elevation="2"
                            class="mt-12  text-uppercase"
                            align="center"
                        >
                            <v-icon class="mb-1" color="primary">description</v-icon>
                            Trips of the selected shift
                        </v-alert>
                    </div>

                    <!-- time of shift -->
                    <v-alert
                        border="left"
                        tile
                        dense
                        color="#grey lighten-5 "
                        elevation="2"
                        class=" font-weight-black"
                        align="center"
                    >
                        <div class="row justify-content-between mx-10">

                       <span >
                        From:
                       </span>
                            <span class="text-primary">
                        {{start_time}}
                        </span>
                        </div>
                    </v-alert>
                    <!-- time of shift -->
                    <v-alert
                        border="left"
                        tile
                        dense
                        color="#grey lighten-5 "
                        elevation="2"
                        class=" font-weight-black"
                        align="center"
                    >
                        <div class="row justify-content-between mx-10">
                            <span>
                                To:
                            </span>
                            <span class="text-primary">
                                {{end_time}}
                            </span>
                        </div>

                    </v-alert>
                    <!-- progress bar -->
                    <v-progress-linear
                        v-if="isLoading"
                        indeterminate
                        color="cyan"
                    ></v-progress-linear>
                    <!-- table -->
                    <div class="table-responsive-lg mt-10" v-if="!isLoading">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                            <tr>
                                <th class="align-middle" scope="col">JOBID</th>
                                <th class="align-middle" scope="col">LOAD TIME</th>
                                <th class="align-middle" scope="col">UNLOAD TIME</th>
                                <th class="align-middle" scope="col">CUSTOMER</th>
                                <th class="align-middle" scope="col">PICK UP</th>
                                <th class="align-middle" scope="col">DROP OFF</th>
                                <th class="align-middle" scope="col" style="max-width:100%;white-space:nowrap;">FARE
                                </th>
                                <th class="align-middle" scope="col" style="max-width:100%;white-space:nowrap;">TIP</th>
                                <th class="align-middle" scope="col" style="max-width:100%;white-space:nowrap;">TOTAL
                                </th>
                                <th class="align-middle" scope="col">TRIP TYPE</th>
                                <th class="align-middle" scope="col">ACCOUNT NAME</th>
                                <th class="align-middle" scope="col">ACCOUNT NUMBER</th>
                                <th class="align-middle" scope="col">PO</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="trip in trips" v-bind:key="trip.trip_id">
                                <td class="fitCell">{{trip.job_id}}</td>
                                <td class="fitCell">{{trip.pickup_date}}</td>
                                <td class="fitCell">{{trip.dropoff__date}}</td>
                                <td class="fitCell">{{trip.customer_name}}</td>
                                <td class="fitCell">{{trip.pickup}}</td>
                                <td class="fitCell">{{trip.dropoff}}</td>
                                <td class="fitCell">$ {{trip.fare}}</td>
                                <td class="fitCell">$ {{trip.tip}}</td>
                                <td class="fitCell">$ {{trip.total}}</td>
                                <td class="fitCell">{{trip.account}}</td>
                                <td></td>
                                <td></td>
                                <td class="fitCell">{{trip.po}}</td>
                            </tr>
                            <tr class="table table-borderless">
                                <td colspan="6" class="table-success" style="font-size: 1.2em;">Totals</td>
                                <td
                                    class="table-primary font-weight-bold fitCell"
                                    style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;"
                                >$ {{shiftFare}}
                                </td>
                                <td
                                    class="table-primary font-weight-bold fitCell"
                                    style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;"
                                >$ {{shiftTip}}
                                </td>
                                <td
                                    class="table-primary font-weight-bold fitCell"
                                    style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;"
                                >$ {{shiftTotal}}
                                </td>
                                <td colspan="3" class="table-success" style="font-size: 1.2em;">Number of Trips</td>
                                <td class="table-primary font-weight-bold fitCell">{{nbr_trips}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- alert -->
                    <v-alert
                        v-if="noTripsFound"
                        border="left"
                        tile
                        type="warning"
                        dense
                        dark
                        elevation="2"
                        class="mb-12"
                        align="center"
                    >Sorry, No trips Found For this Month!
                    </v-alert>
                </div>
            </div>
        </div>

    </div>

</template>
<script>
    import NavbarDriver from "@/js/components/navbars/Driver.vue";
    import Drawer from "@/js/components/drawers/Driver.vue";

    export default {
        components: {
            NavbarDriver,
            Drawer
        },
        created() {
            this.id = this.$route.params.id;
            axios.defaults.headers.common["Authorization"] =
                "Bearer " + this.$store.state.token_driver;
            axios
                .get(this.BASE_URL() + "/api/driver/shift/" + this.id)
                .then(res => {

                    this.trips = res.data.trips;
                    this.shiftFare = res.data.shiftFare;
                    this.shiftTip = res.data.shiftTip;
                    this.shiftTotal = res.data.shiftTotal;
                    this.start_time = res.data.start_time;
                    this.end_time = res.data.end_time;
                    this.isLoading = false;

                })
                .catch(error => {
                    console.log(error);
                });
        },
        data() {
            return {
                trips: {},
                id: "",
                shiftsRoute: {name: "DriverDashboard_reports_dailyLogSheet"},
                shiftFare: '',
                shiftTip: '',
                shiftTotal: '',
                start_time: '',
                end_time: '',
                isLoading: true,
            };
        },
        computed: {
            noTripsFound() {
                if (this.trips == null || this.trips.length == 0 || this.trips == '') {
                    return true;
                } else {
                    return false;
                }
            },
            nbr_trips() {
                return this.trips.length;
            }
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            }
        }
    };
</script>


<style scoped>
    a:hover {
        text-decoration: none;
    }

    .fitCell {
        max-width: 100%;
        white-space: nowrap;
    }
</style>
