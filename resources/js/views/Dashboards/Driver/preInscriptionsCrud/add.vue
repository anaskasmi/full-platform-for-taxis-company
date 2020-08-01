<template>
    <!--contain all html page-->
    <div class="ma-0 pa-0">
        <div v-if="$mq=='desktop'" class="ma-0 pa-0">
            <div>
                <NavbarDriver></NavbarDriver>
                <Drawer></Drawer>

                <!-- back button -->
                <div class="row col-12">
                    <v-btn color="grey" text tile class="float-left" :to="tripsRoute">
                        <v-icon class="mr-2">keyboard_return</v-icon>
                        trips
                    </v-btn>
                </div>
                <hr/>

                <div v-if="validationErrors.length">
                    <v-alert
                        dense
                        outlined
                        type="warning"
                        border="left"
                        v-for="error in validationErrors"
                        v-bind:key="error"
                    >{{error}}
                    </v-alert>
                    <hr/>
                </div>

                <!-- form -->
                <form id="form" @submit.prevent="storeTrip">
                    <v-alert
                        border="left"
                        tile
                        dense
                        color="#1F7087"
                        dark
                        elevation="2"
                        class="mt-12"
                        align="center"
                    >TRIP INFO
                    </v-alert>

                    <div class="form-group">
                        <v-text-field label="JOB ID" v-model="trip.job_id" type="text" name="job_id"></v-text-field>
                    </div>

                    <!-- start date time of trip  -->
                    <div class="form-group row col-12 px-0 mx-0">
                        <!--<v-text-field label="DATE AND TIME" v-model="trip.date"  type="datetime-local"  min="1000-01-01T00:00" max="3000-01-01T00:00" name="date">
                        </v-text-field>-->

                        <!-- date -->
                        <v-col cols="6" class="pl-0 float-left">
                            <v-dialog
                                ref="dateDate"
                                v-model="modal_dateDate"
                                :return-value.sync="trip.dateDate"
                                persistent
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="trip.dateDate"
                                        label="Date"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="trip.dateDate" scrollable>
                                    <div class="flex-grow-1"></div>
                                    <v-btn text color="primary" @click="modal_dateDate = false">Cancel</v-btn>
                                    <v-btn text color="primary" @click="$refs.dateDate.save(trip.dateDate)">OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-col>

                        <!-- time -->
                        <v-col cols="6" class="pr-0 mr-0 float-right">
                            <v-dialog
                                ref="dateTime"
                                v-model="modal_dateTime"
                                :return-value.sync="trip.dateTime"
                                persistent
                                width="350px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="trip.dateTime"
                                        label="Time"
                                        prepend-icon="access_time"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-time-picker v-if="modal_dateTime" v-model="trip.dateTime" format="24hr">
                                    <div class="flex-grow-1"></div>
                                    <v-btn text color="primary" @click="modal_dateTime = false">Cancel</v-btn>
                                    <v-btn text color="primary" @click="$refs.dateTime.save(trip.dateTime)">OK</v-btn>
                                </v-time-picker>
                            </v-dialog>
                        </v-col>
                    </div>
                    <!-- end date time of trip  -->

                    <v-alert
                        border="left"
                        tile
                        dense
                        color="#1F7087"
                        dark
                        elevation="2"
                        class="mt-12"
                        align="center"
                    >DRIVER INFO
                    </v-alert>

                    <div class="form-group">
                        <v-text-field label="vehicle" v-model="trip.vehicle" type="text" name="vehicle"></v-text-field>
                    </div>

                    <v-alert
                        border="left"
                        tile
                        dense
                        color="#1F7087"
                        dark
                        elevation="2"
                        class="mt-12"
                        align="center"
                    >CUSTOMER INFO
                    </v-alert>

                    <div class="form-group">
                        <v-text-field
                            label="CUSTOMER NAME"
                            v-model="trip.customer_name"
                            type="text"
                            name="customer_name"
                        ></v-text-field>
                    </div>

                    <div class="form-group">
                        <v-text-field label="FARE (cent)" v-model="trip.fare" type="text" name="fare"></v-text-field>
                    </div>

                    <div class="form-group">
                        <v-text-field label="TIP (cent)" v-model="trip.tip" type="text" name="tip"></v-text-field>
                    </div>

                    <div class="form-group">
                        <v-text-field label="ACCOUNT" v-model="trip.account" type="text" name="account"></v-text-field>
                    </div>

                    <div class="form-group">
                        <v-text-field label="PO" v-model="trip.po" type="text" name="po"></v-text-field>
                    </div>

                    <v-alert
                        border="left"
                        tile
                        dense
                        color="#1F7087"
                        dark
                        elevation="2"
                        class="mt-12"
                        align="center"
                    >TIME AND LOCALISATION INFO
                    </v-alert>

                    <div class="form-group">
                        <v-text-field label="PICKUP ADDRESS" v-model="trip.pickup" type="text"
                                      name="pickup"></v-text-field>
                    </div>

                    <div class="form-group">
                        <v-text-field label="DROP OFF ADDRESS" v-model="trip.dropoff" type="text"
                                      name="dropoff"></v-text-field>
                    </div>

                    <!-- start pickup time of trip  -->

                    <div class="form-group row col-12 px-0 mx-0">
                        <!-- <div class="form-group">
                                            <v-text-field label="PICKUP DATE" v-model="trip.pickup_date"   type="datetime-local"  min="1000-01-01T00:00" max="3000-01-01T00:00"  name="pickup_date" >
                                            </v-text-field>
                        </div>-->

                        <!-- date -->
                        <v-col cols="6" class="pl-0 float-left">
                            <v-dialog
                                ref="pickup_dateDate"
                                v-model="modal_pickup_dateDate"
                                :return-value.sync="trip.pickup_dateDate"
                                persistent
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="trip.pickup_dateDate"
                                        label="PICKUP DATE"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="trip.pickup_dateDate" scrollable>
                                    <div class="flex-grow-1"></div>
                                    <v-btn text color="primary" @click="modal_pickup_dateDate = false">Cancel</v-btn>
                                    <v-btn
                                        text
                                        color="primary"
                                        @click="$refs.pickup_dateDate.save(trip.pickup_dateDate)"
                                    >OK
                                    </v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-col>

                        <!-- time -->
                        <v-col cols="6" class="pr-0 mr-0 float-right">
                            <v-dialog
                                ref="pickup_dateTime"
                                v-model="modal_pickup_dateTime"
                                :return-value.sync="trip.pickup_dateTime"
                                persistent
                                width="350px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="trip.pickup_dateTime"
                                        label="PICKUP Time"
                                        prepend-icon="access_time"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-time-picker
                                    v-if="modal_pickup_dateTime"
                                    v-model="trip.pickup_dateTime"
                                    format="24hr"
                                >
                                    <div class="flex-grow-1"></div>
                                    <v-btn text color="primary" @click="modal_pickup_dateTime = false">Cancel</v-btn>
                                    <v-btn
                                        text
                                        color="primary"
                                        @click="$refs.pickup_dateTime.save(trip.pickup_dateTime)"
                                    >OK
                                    </v-btn>
                                </v-time-picker>
                            </v-dialog>
                        </v-col>
                    </div>
                    <!-- end pickup time of trip  -->

                    <!-- start dropof time of trip  -->

                    <div class="form-group row col-12 px-0 mx-0">
                        <!-- <div class="form-group">
                                                    <v-text-field label="DROPOFF DATE" v-model="trip.dropoff__date"   type="datetime-local"  min="1000-01-01T00:00" max="3000-01-01T00:00"  name="dropoff__date" >
                                                    </v-text-field>
                        </div>-->

                        <!-- date -->
                        <v-col cols="6" class="pl-0 float-left">
                            <v-dialog
                                ref="dropoff__dateDate"
                                v-model="modal_dropoff__dateDate"
                                :return-value.sync="trip.dropoff__dateDate"
                                persistent
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="trip.dropoff__dateDate"
                                        label="DROPOFF DATE"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="trip.dropoff__dateDate" scrollable>
                                    <div class="flex-grow-1"></div>
                                    <v-btn text color="primary" @click="modal_dropoff__dateDate = false">Cancel</v-btn>
                                    <v-btn
                                        text
                                        color="primary"
                                        @click="$refs.dropoff__dateDate.save(trip.dropoff__dateDate)"
                                    >OK
                                    </v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-col>

                        <!-- time -->
                        <v-col cols="6" class="pr-0 mr-0 float-right">
                            <v-dialog
                                ref="dropoff__dateTime"
                                v-model="modal_dropoff__dateTime"
                                :return-value.sync="trip.dropoff__dateTime"
                                persistent
                                width="350px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="trip.dropoff__dateTime"
                                        label="DROPOFF Time"
                                        prepend-icon="access_time"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-time-picker
                                    v-if="modal_dropoff__dateTime"
                                    v-model="trip.dropoff__dateTime"
                                    format="24hr"
                                >
                                    <div class="flex-grow-1"></div>
                                    <v-btn text color="primary" @click="modal_dropoff__dateTime = false">Cancel</v-btn>
                                    <v-btn
                                        text
                                        color="primary"
                                        @click="$refs.dropoff__dateTime.save(trip.dropoff__dateTime)"
                                    >OK
                                    </v-btn>
                                </v-time-picker>
                            </v-dialog>
                        </v-col>
                    </div>
                    <!-- end dropoff__date time of trip  -->

                    <!-- submit btn -->
                    <div class="row col-12 mt-4">
                        <v-btn color="success" tile class="float-left" type="submit">submit</v-btn>
                        <v-btn
                            color="grey lighten-1"
                            dark
                            tile
                            class="float-left mx-4"
                            @click="$router.go(-1)"
                        >cancel
                        </v-btn>
                    </div>
                </form>
            </div>
        </div>
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <!----------------------------Mobile Version---------------------------->
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <div v-if="$mq=='mobile'" class="ma-0 pa-0">
            <div>
                <NavbarDriver></NavbarDriver>
                <div class="container">


                    <!-- back button -->
                    <div class="row col-12">
                        <v-btn color="grey" text tile class="float-left" :to="tripsRoute">
                            <v-icon class="mr-2">keyboard_return</v-icon>
                            trips
                        </v-btn>
                    </div>
                    <hr/>

                    <div v-if="validationErrors.length">
                        <v-alert
                            dense
                            outlined
                            type="warning"
                            border="left"
                            v-for="error in validationErrors"
                            v-bind:key="error"
                        >{{error}}
                        </v-alert>
                        <hr/>
                    </div>

                    <!-- form -->
                    <form id="form" @submit.prevent="storeTrip">
                        <v-alert
                            border="left"
                            tile
                            dense
                            color="#1F7087"
                            dark
                            elevation="2"
                            class="mt-12"
                            align="center"
                        >TRIP INFO
                        </v-alert>

                        <div class="form-group">
                            <v-text-field label="JOB ID" v-model="trip.job_id" type="text" name="job_id"></v-text-field>
                        </div>

                        <!-- start date time of trip  -->
                        <div class="form-group row col-12 px-0 mx-0">
                            <!--<v-text-field label="DATE AND TIME" v-model="trip.date"  type="datetime-local"  min="1000-01-01T00:00" max="3000-01-01T00:00" name="date">
                            </v-text-field>-->

                            <!-- date -->
                            <v-col cols="6" class="pl-0 float-left">
                                <v-dialog
                                    ref="dateDate"
                                    v-model="modal_dateDate"
                                    :return-value.sync="trip.dateDate"
                                    persistent
                                    width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="trip.dateDate"
                                            label="Date"
                                            prepend-icon="event"
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker v-model="trip.dateDate" scrollable>
                                        <div class="flex-grow-1"></div>
                                        <v-btn text color="primary" @click="modal_dateDate = false">Cancel</v-btn>
                                        <v-btn text color="primary" @click="$refs.dateDate.save(trip.dateDate)">OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-dialog>
                            </v-col>

                            <!-- time -->
                            <v-col cols="6" class="pr-0 mr-0 float-right">
                                <v-dialog
                                    ref="dateTime"
                                    v-model="modal_dateTime"
                                    :return-value.sync="trip.dateTime"
                                    persistent
                                    width="350px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="trip.dateTime"
                                            label="Time"
                                            prepend-icon="access_time"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker v-if="modal_dateTime" v-model="trip.dateTime" format="24hr">
                                        <div class="flex-grow-1"></div>
                                        <v-btn text color="primary" @click="modal_dateTime = false">Cancel</v-btn>
                                        <v-btn text color="primary" @click="$refs.dateTime.save(trip.dateTime)">OK
                                        </v-btn>
                                    </v-time-picker>
                                </v-dialog>
                            </v-col>
                        </div>
                        <!-- end date time of trip  -->

                        <v-alert
                            border="left"
                            tile
                            dense
                            color="#1F7087"
                            dark
                            elevation="2"
                            class="mt-12"
                            align="center"
                        >DRIVER INFO
                        </v-alert>

                        <div class="form-group">
                            <v-text-field label="vehicle" v-model="trip.vehicle" type="text"
                                          name="vehicle"></v-text-field>
                        </div>

                        <v-alert
                            border="left"
                            tile
                            dense
                            color="#1F7087"
                            dark
                            elevation="2"
                            class="mt-12"
                            align="center"
                        >CUSTOMER INFO
                        </v-alert>

                        <div class="form-group">
                            <v-text-field
                                label="CUSTOMER NAME"
                                v-model="trip.customer_name"
                                type="text"
                                name="customer_name"
                            ></v-text-field>
                        </div>

                        <div class="form-group">
                            <v-text-field label="FARE (cent)" v-model="trip.fare" type="text"
                                          name="fare"></v-text-field>
                        </div>

                        <div class="form-group">
                            <v-text-field label="TIP (cent)" v-model="trip.tip" type="text" name="tip"></v-text-field>
                        </div>

                        <div class="form-group">
                            <v-text-field label="ACCOUNT" v-model="trip.account" type="text"
                                          name="account"></v-text-field>
                        </div>

                        <div class="form-group">
                            <v-text-field label="PO" v-model="trip.po" type="text" name="po"></v-text-field>
                        </div>

                        <v-alert
                            border="left"
                            tile
                            dense
                            color="#1F7087"
                            dark
                            elevation="2"
                            class="mt-12"
                            align="center"
                        >TIME AND LOCALISATION INFO
                        </v-alert>

                        <div class="form-group">
                            <v-text-field label="PICKUP ADDRESS" v-model="trip.pickup" type="text"
                                          name="pickup"></v-text-field>
                        </div>

                        <div class="form-group">
                            <v-text-field label="DROP OFF ADDRESS" v-model="trip.dropoff" type="text"
                                          name="dropoff"></v-text-field>
                        </div>

                        <!-- start pickup time of trip  -->

                        <div class="form-group row col-12 px-0 mx-0">
                            <!-- <div class="form-group">
                                                <v-text-field label="PICKUP DATE" v-model="trip.pickup_date"   type="datetime-local"  min="1000-01-01T00:00" max="3000-01-01T00:00"  name="pickup_date" >
                                                </v-text-field>
                            </div>-->

                            <!-- date -->
                            <v-col cols="6" class="pl-0 float-left">
                                <v-dialog
                                    ref="pickup_dateDate"
                                    v-model="modal_pickup_dateDate"
                                    :return-value.sync="trip.pickup_dateDate"
                                    persistent
                                    width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="trip.pickup_dateDate"
                                            label="PICKUP DATE"
                                            prepend-icon="event"
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker v-model="trip.pickup_dateDate" scrollable>
                                        <div class="flex-grow-1"></div>
                                        <v-btn text color="primary" @click="modal_pickup_dateDate = false">Cancel
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.pickup_dateDate.save(trip.pickup_dateDate)"
                                        >OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-dialog>
                            </v-col>

                            <!-- time -->
                            <v-col cols="6" class="pr-0 mr-0 float-right">
                                <v-dialog
                                    ref="pickup_dateTime"
                                    v-model="modal_pickup_dateTime"
                                    :return-value.sync="trip.pickup_dateTime"
                                    persistent
                                    width="350px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="trip.pickup_dateTime"
                                            label="PICKUP Time"
                                            prepend-icon="access_time"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="modal_pickup_dateTime"
                                        v-model="trip.pickup_dateTime"
                                        format="24hr"
                                    >
                                        <div class="flex-grow-1"></div>
                                        <v-btn text color="primary" @click="modal_pickup_dateTime = false">Cancel
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.pickup_dateTime.save(trip.pickup_dateTime)"
                                        >OK
                                        </v-btn>
                                    </v-time-picker>
                                </v-dialog>
                            </v-col>
                        </div>
                        <!-- end pickup time of trip  -->

                        <!-- start dropof time of trip  -->

                        <div class="form-group row col-12 px-0 mx-0">
                            <!-- <div class="form-group">
                                                        <v-text-field label="DROPOFF DATE" v-model="trip.dropoff__date"   type="datetime-local"  min="1000-01-01T00:00" max="3000-01-01T00:00"  name="dropoff__date" >
                                                        </v-text-field>
                            </div>-->

                            <!-- date -->
                            <v-col cols="6" class="pl-0 float-left">
                                <v-dialog
                                    ref="dropoff__dateDate"
                                    v-model="modal_dropoff__dateDate"
                                    :return-value.sync="trip.dropoff__dateDate"
                                    persistent
                                    width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="trip.dropoff__dateDate"
                                            label="DROPOFF DATE"
                                            prepend-icon="event"
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker v-model="trip.dropoff__dateDate" scrollable>
                                        <div class="flex-grow-1"></div>
                                        <v-btn text color="primary" @click="modal_dropoff__dateDate = false">Cancel
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.dropoff__dateDate.save(trip.dropoff__dateDate)"
                                        >OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-dialog>
                            </v-col>

                            <!-- time -->
                            <v-col cols="6" class="pr-0 mr-0 float-right">
                                <v-dialog
                                    ref="dropoff__dateTime"
                                    v-model="modal_dropoff__dateTime"
                                    :return-value.sync="trip.dropoff__dateTime"
                                    persistent
                                    width="350px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="trip.dropoff__dateTime"
                                            label="DROPOFF Time"
                                            prepend-icon="access_time"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="modal_dropoff__dateTime"
                                        v-model="trip.dropoff__dateTime"
                                        format="24hr"
                                    >
                                        <div class="flex-grow-1"></div>
                                        <v-btn text color="primary" @click="modal_dropoff__dateTime = false">Cancel
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.dropoff__dateTime.save(trip.dropoff__dateTime)"
                                        >OK
                                        </v-btn>
                                    </v-time-picker>
                                </v-dialog>
                            </v-col>
                        </div>
                        <!-- end dropoff__date time of trip  -->

                        <!-- submit btn -->
                            <v-btn color="success" tile block class="" type="submit">submit</v-btn>
                            <v-btn
                                color="grey lighten-1"
                                dark
                                tile
                                block
                                class="my-4"
                                @click="$router.go(-1)"
                            >cancel
                            </v-btn>
                    </form>
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
        data() {
            return {
                inputErrors: [],
                tripsRoute: {name: "DriverDashboard_trips"},
                trip: {
                    dateDate: null,
                    dateTime: null,

                    pickup_dateDate: null,
                    pickup_dateTime: null,

                    dropoff__dateDate: null,
                    dropoff__dateTime: null
                },
                date: null,
                pickup_date: null,
                dropoff__date: null,

                modal_dateDate: false,
                modal_dateTime: false,

                modal_pickup_dateDate: false,
                modal_pickup_dateTime: false,

                modal_dropoff__dateDate: false,
                modal_dropoff__dateTime: false
            };
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            pad(n) {
                return n < 10 ? "0" + n : n;
            },
            constructDates() {
                if (this.trip.dateDate && this.trip.dateTime) {
                    var date = new Date(this.trip.dateDate);
                    var day = date.getDate();
                    var month = date.getMonth(); //Be careful! January is 0 not 1
                    var year = date.getFullYear();
                    var date = year + "-" + this.pad(month + 1) + "-" + this.pad(day);
                    this.date = date + " " + this.trip.dateTime + ":00";
                }
                if (this.trip.pickup_dateDate && this.trip.pickup_dateTime) {
                    var date = new Date(this.trip.pickup_dateDate);
                    var day = date.getDate();
                    var month = date.getMonth(); //Be careful! January is 0 not 1
                    var year = date.getFullYear();
                    var date = year + "-" + this.pad(month + 1) + "-" + this.pad(day);
                    this.pickup_date = date + " " + this.trip.pickup_dateTime + ":00";
                }
                if (this.trip.dropoff__dateDate && this.trip.dropoff__dateTime) {
                    var date = new Date(this.trip.dropoff__dateDate);
                    var day = date.getDate();
                    var month = date.getMonth(); //Be careful! January is 0 not 1
                    var year = date.getFullYear();
                    var date = year + "-" + this.pad(month + 1) + "-" + this.pad(day);
                    this.dropoff__date = date + " " + this.trip.dropoff__dateTime + ":00";
                }
            },
            separateDates() {
                // date
                if (this.trip.date) {
                    var date = new Date(this.trip.date);
                    var day = date.getDate();
                    var month = date.getMonth(); //Be careful! January is 0 not 1
                    var year = date.getFullYear();
                    var hour = date.getHours();
                    var minute = date.getMinutes();
                    this.trip.dateDate =
                        year + "-" + this.pad(month + 1) + "-" + this.pad(day);
                    this.trip.dateTime = this.pad(hour) + ":" + this.pad(minute);
                }
                // pickup
                if (this.trip.pickup_date) {
                    var date = new Date(this.trip.pickup_date);
                    var day = date.getDate();
                    var month = date.getMonth(); //Be careful! January is 0 not 1
                    var year = date.getFullYear();
                    var hour = date.getHours();
                    var minute = date.getMinutes();
                    this.trip.pickup_dateDate =
                        year + "-" + this.pad(month + 1) + "-" + this.pad(day);
                    this.trip.pickup_dateTime = this.pad(hour) + ":" + this.pad(minute);
                }

                //dropoff
                if (this.trip.dropoff__date) {
                    var date = new Date(this.trip.dropoff__date);
                    var day = date.getDate();
                    var month = date.getMonth(); //Be careful! January is 0 not 1
                    var year = date.getFullYear();
                    var hour = date.getHours();
                    var minute = date.getMinutes();
                    this.trip.dropoff__dateDate =
                        year + "-" + this.pad(month + 1) + "-" + this.pad(day);
                    this.trip.dropoff__dateTime = this.pad(hour) + ":" + this.pad(minute);
                }
            },
            storeTrip() {
                var form = document.getElementById("form");
                this.constructDates();
                var formData = new FormData(form);
                if (this.date) {
                    formData.append("date", this.date);
                }
                if (this.pickup_date) {
                    formData.append("pickup_date", this.pickup_date);
                }
                if (this.dropoff__date) {
                    formData.append("dropoff__date", this.dropoff__date);
                }
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ", " + pair[1]);
                }
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .post(this.BASE_URL() + "/api/driver/trip", formData)
                    .then(response => {
                        this.$router.push({
                            name: "DriverDashboard_trips_show",
                            params: {id: response.data.data.trip_id}
                        });
                    })
                    .catch(error => {
                        this.inputErrors = error.response.data.errors;
                        // window.scrollTo(0,0);
                        $("html, body").animate({scrollTop: 0}, "200");
                    });
            }
        },
        computed: {
            validationErrors() {
                let errors = Object.values(this.inputErrors);
                errors = errors.flat();
                return errors;
            }
        }
    };
</script>


<style scoped>
    .component-fade-enter-active,
    .component-fade-leave-active {
        transition: opacity 0.4s ease;
    }

    .component-fade-enter, .component-fade-leave-to
        /* .component-fade-leave-active below version 2.1.8 */
    {
        opacity: 0;
    }

    a:hover {
        text-decoration: none;
    }
</style>
