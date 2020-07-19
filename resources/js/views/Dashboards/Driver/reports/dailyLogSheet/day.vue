<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div>
                <!-- date picker -->
                <!-- button to toggle the the date picker -->
                <div class="row col-12">
                    <!-- pdf download button -->
                    <v-tooltip top v-if="!noShiftsFound">
                        <template v-slot:activator="{ on }">
                            <a

                                :href="BASE_URL() +
          '/api/driver/reports/DailyLogSheet/' +
          idEncrypted +
          '/printPDF/'+date"
                                target="_blank"
                            >
                                <v-btn
                                    v-on="on"
                                    depressed
                                    fab
                                    small
                                    color="success"
                                    class="mr-2 my-auto"
                                    @click="load()"
                                >
                                    <v-icon small color="white lighten-4">save_alt</v-icon>
                                </v-btn>
                            </a>
                        </template>
                        <span>download this report as PDF</span>
                    </v-tooltip>
                    <!-- date picker button -->
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                v-on="on"
                                depressed
                                fab
                                small
                                color="light-blue darken-4"
                                class="my-auto"
                                @click="dateInput_show = !dateInput_show"
                            >
                                <v-icon small color="white lighten-4">event</v-icon>
                            </v-btn>
                        </template>
                        <span>Pick a day to get the report !</span>
                    </v-tooltip>
                    <!-- the date picker -->
                    <v-col cols class="my-auto" v-if="dateInput_show">
                        <v-dialog ref="dialog" v-model="modal" :return-value.sync="date" persistent width="290px">
                            <template v-slot:activator="{ on }">
                                <v-text-field v-model="date" readonly v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date" scrollable>
                                <div class="flex-grow-1"></div>
                                <v-btn text color="primary" @click="modal = false">Cancel</v-btn>
                                <v-btn
                                    text
                                    color="primary"
                                    @click="$refs.dialog.save(date); getReport(date);"
                                >Get Report
                                </v-btn>
                            </v-date-picker>
                        </v-dialog>
                    </v-col>
                </div>
                <!-- progress bar -->
                <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                <!-- table -->
                <div v-if="table_show && !isLoading" class="table-responsive-lg">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th class="align-middle" scope="col">JOBID</th>
                            <th class="align-middle" scope="col">LOAD TIME</th>
                            <th class="align-middle" scope="col">UNLOAD TIME</th>
                            <th class="align-middle" scope="col">CUSTOMER</th>
                            <th class="align-middle" scope="col">PICK UP</th>
                            <th class="align-middle" scope="col">DROP OFF</th>
                            <th class="align-middle" scope="col">FARE</th>
                            <th class="align-middle" scope="col">TIP</th>
                            <th class="align-middle" scope="col">TOTAL</th>
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
                            <td>{{trip.fare}}</td>
                            <td>{{trip.tip}}</td>
                            <td>{{trip.total}}</td>
                            <td>{{trip.account}}</td>
                            <td></td>
                            <td></td>
                            <td>{{trip.po}}</td>
                        </tr>
                        <tr class="table table-borderless">
                            <td colspan="6" class="table-success" style="font-size: 1.2em;">Total</td>
                            <td class="table-primary font-weight-bold"
                                style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;">
                                ${{total_fare}}
                            </td>
                            <td class="table-primary font-weight-bold"
                                style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;">
                                ${{total_tip}}
                            </td>
                            <td class="table-primary font-weight-bold"
                                style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;">
                                ${{total_total}}
                            </td>
                            <td colspan="3" class="table-success" style="font-size: 1.2em;">Trips</td>
                            <td class="table-primary font-weight-bold">{{nbr_trips}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- alert -->
                <v-alert
                    v-if="noShiftsFound"
                    border="left"
                    tile
                    type="warning"
                    dense
                    dark
                    elevation="2"
                    class="mb-12"
                    align="center"
                >Sorry, No shifts Found For this day! chose another day
                </v-alert>
            </div>
        </div>
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <!----------------------------Mobile Version---------------------------->
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <div class="pa-0 ma-0 " v-if="$mq=='mobile'">

            <div>
                <!-- date picker -->
                <!-- button to toggle the the date picker -->
                <div class="row col-12 mx-auto text-center">
                    <!-- pdf download button -->
                    <v-tooltip top v-if="!noShiftsFound">
                        <template v-slot:activator="{ on }">
                            <a
                                class="mx-auto"

                                :href="BASE_URL() +
          '/api/driver/reports/DailyLogSheet/' +
          idEncrypted +
          '/printPDF/'+date"
                                target="_blank"
                            >
                                <v-btn
                                    v-on="on"
                                    depressed
                                    fab
                                    small
                                    color="success"
                                    class="mr-2 my-auto "
                                    @click="load()"
                                >
                                    <v-icon small color="white lighten-4">save_alt</v-icon>
                                </v-btn>
                            </a>
                        </template>
                        <span>download this report as PDF</span>
                    </v-tooltip>
                    <!-- date picker button -->
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                v-on="on"
                                depressed
                                fab
                                small
                                color="light-blue darken-4"
                                class="my-auto mx-auto"
                                @click="dateInput_show = !dateInput_show"
                            >
                                <v-icon small color="white lighten-4">event</v-icon>
                            </v-btn>
                        </template>
                        <span>Pick a day to get the report !</span>
                    </v-tooltip>
                    <!-- the date picker -->
                    <v-col cols class="my-auto" v-if="dateInput_show">
                        <v-dialog ref="dialog" v-model="modal" :return-value.sync="date" persistent width="290px">
                            <template v-slot:activator="{ on }">
                                <v-text-field v-model="date" readonly v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date" scrollable>
                                <div class="flex-grow-1"></div>
                                <v-btn text color="primary" @click="modal = false">Cancel</v-btn>
                                <v-btn
                                    text
                                    color="primary"
                                    @click="$refs.dialog.save(date); getReport(date);"
                                >Get Report
                                </v-btn>
                            </v-date-picker>
                        </v-dialog>
                    </v-col>
                </div>
                <!-- progress bar -->
                <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                <!-- table -->
                <div v-if="table_show && !isLoading" class="table-responsive-lg">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th class="align-middle " scope="col">JOBID</th>
                            <th class="align-middle" scope="col">LOAD TIME</th>
                            <th class="align-middle" scope="col">UNLOAD TIME</th>
                            <th class="align-middle" scope="col">CUSTOMER</th>
                            <th class="align-middle" scope="col">PICK UP</th>
                            <th class="align-middle" scope="col">DROP OFF</th>
                            <th class="align-middle" scope="col">FARE</th>
                            <th class="align-middle" scope="col">TIP</th>
                            <th class="align-middle" scope="col">TOTAL</th>
                            <th class="align-middle" scope="col">TRIP TYPE</th>
                            <th class="align-middle" scope="col">ACCOUNT NAME</th>
                            <th class="align-middle" scope="col">ACCOUNT NUMBER</th>
                            <th class="align-middle" scope="col">PO</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="trip in trips" v-bind:key="trip.trip_id">
                            <td class="align-middle">{{trip.job_id}}</td>
                            <td class="align-middle">{{trip.pickup_date}}</td>
                            <td class="align-middle">{{trip.dropoff__date}}</td>
                            <td class="align-middle">{{trip.customer_name}}</td>
                            <td class="align-middle">{{trip.pickup}}</td>
                            <td class="align-middle">{{trip.dropoff}}</td>
                            <td class="align-middle">{{trip.fare}}</td>
                            <td class="align-middle">{{trip.tip}}</td>
                            <td class="align-middle">{{trip.total}}</td>
                            <td class="align-middle">{{trip.account}}</td>
                            <td class="align-middle"></td>
                            <td class="align-middle"></td>
                            <td class="align-middle">{{trip.po}}</td>
                        </tr>
                        <tr class="table table-borderless">
                            <td colspan="6" class="table-success" style="font-size: 1.2em;">Total</td>
                            <td class="table-primary font-weight-bold"
                                style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;">
                                ${{total_fare}}
                            </td>
                            <td class="table-primary font-weight-bold"
                                style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;">
                                ${{total_tip}}
                            </td>
                            <td class="table-primary font-weight-bold"
                                style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;">
                                ${{total_total}}
                            </td>
                            <td colspan="3" class="table-success" style="font-size: 1.2em;">Trips</td>
                            <td class="table-primary font-weight-bold">{{nbr_trips}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- alert -->
                <v-alert
                    v-if="noShiftsFound"
                    border="left"
                    tile
                    type="warning"
                    dense
                    dark
                    elevation="2"
                    class="mb-12"
                    align="center"
                >Sorry, No shifts Found For this day! chose another day
                </v-alert>
            </div>
        </div>
    </div>

</template>
<script>
    export default {
        beforeMount() {
            this.noShiftsFound = true;
            this.dateInput_show = true;
            this.table_show = false;
            this.getReport(this.date);
        },
        computed: {
            nbr_trips() {
                return this.trips.length;
            },
        },
        data() {
            return {
                isLoading: true,
                date: new Date().toISOString().substr(0, 10),
                modal: false,
                noShiftsFound: false,
                table_show: false,
                trips: {},
                total_fare: 0,
                total_tip: 0,
                total_total: 0,
                cpt: 0,
                dateInput_show: false,

            };
        },

        props: {
            idEncrypted: ""
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            load() {
                this.$swal.fire("", "Your PDF was generated successfully !", "success");
            },
            pdf() {
                this.$swal.fire("", "loading..", "success");
                axios
                    .get(
                        this.BASE_URL() +
                        "/api/driver/reports/DailyLogSheet/" +
                        this.id +
                        "/printPDF/" +
                        this.date
                    )
                    .then(res => {
                        this.$swal.fire("", "Your PDF is ready !", "success");
                    })
                    .catch(error => {
                        console.log("error pdf :");
                        console.log(error);
                        this.$swal.fire("", "Somthing wrong happend !", "warning");
                    });
            },
            getReport(date) {
                this.noShiftsFound = false;
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .post(this.BASE_URL() + "/api/driver/reports/DailyLogSheet/day", {
                        date: this.date
                    })
                    .then(res => {
                        if (res.data.data.trips.length == 0) {
                            this.noShiftsFound = true;
                            this.table_show = false;
                        } else {
                            this.dateInput_show = false;
                            this.table_show = true;
                            this.trips = res.data.data.trips;
                            this.total_fare = res.data.data.total_fare;
                            this.total_tip = res.data.data.total_tip;
                            this.total_total = res.data.data.total_total;
                        }
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.log("error :");
                        console.log(error);
                    });
            }
        }
    };
</script>
<style scoped>
    .wrapper_box {
        display: grid;
        grid-gap: 1em;
        grid-auto-rows: minmax(10px, auto);
    }

    .search_box {
        background-color: rgb(255, 51, 61);
    }

    .table_box {
        background-color: rgb(65, 35, 150);
    }

    .pagination_box {
        background-color: rgb(255, 153, 0);
    }

    a,
    a:hover {
        text-decoration: none;
    }

    .wrapper_header {
        display: grid;
        grid-auto-rows: minmax(50px, auto);
        align-items: center;
        /* background-color: rgb(255, 153, 0); */
    }

    .title_header {
        justify-self: start;
        font-family: "roboto";
        font-size: 3em;
        color: rgb(124, 124, 124);
    }

    .button_header {
        justify-self: end;
    }

    .sentence {
        margin-top: 4em;
        font-family: "Quicksand", sans-serif;
        justify-self: center;
        font-size: 3em;
        justify-self: center;
    }
</style>
