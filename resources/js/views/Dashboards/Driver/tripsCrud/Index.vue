<template>
    <div class="ma-0 pa-0">
        <div class="wrapper_box" v-if="$mq == 'desktop'">
            <NavbarDriver></NavbarDriver>
            <Drawer></Drawer>

            <!-- header -->
            <div class="wrapper_header mt-10">

                <div
                    class="title_header text-uppercase"
                >
                    Trips
                </div>
                <v-btn
                    link
                    tile
                    class="ma-2 button_header"
                    outlined
                    color="success "
                    @click="addTrip"

                >
                    <v-icon left>mdi-pencil</v-icon>
                    <div>
                        New Trip
                    </div>
                </v-btn>
            </div>
            <hr>
            <!-- search start -->
            <div class="row">
                <div class="col-md-12 md-form active-cyan-2 mb-3" style="margin-bottom: -15px !important;">

                    <form @submit.prevent='search(searchbyjobid,"searchbyjobid")'>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <button type="submit" class="btn btn-dark">
                                    <v-icon dark small>search</v-icon>
                                </button>
                            </div>
                            <input type="search" name="searchValue" v-model="searchbyjobid"
                                   placeholder="Search by job id"
                                   aria-describedby="button-addon7" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
            <!-- search end -->
            <!-- search new start -->


            <!-- {{-- collapse button --}} -->
            <v-btn block outlined tile color="info" @click="showSearchOption = !showSearchOption">
                <v-icon color="info darken-1" class="mx-3">
                    arrow_drop_down
                </v-icon>
                Other search options
            </v-btn>
            <!-- {{-- end collapse button --}} -->

            <transition mode="out-in">
                <div class="card-deck" v-if="showSearchOption">
                    <!-- {{-- search by date --}} -->
                    <div class="card bg-light">
                        <div class="card-header">Search by date</div>
                        <div class="card-body text-center">
                            <div class="input-group mb-4">
                                <input type="date" v-model="searchbydate" max="3000-12-31" min="1000-01-01"
                                       class="form-control">
                                <!-- {{-- search button --}} -->
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-dark"
                                            @click.prevent="search(searchbydate,'searchbydate')">
                                        <v-icon dark small>search</v-icon>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- {{-- end search by date --}} -->


                    <!-- {{-- search by customer --}} -->

                    <div class="card bg-light">
                        <div class="card-header">Search by customer</div>
                        <div class="card-body text-center">
                            <form>
                                <div class="input-group mb-4">
                                    <!-- {{-- search box --}} -->
                                    <input type="search" v-model="searchbycustomer" name="searchbycustomer"
                                           placeholder="search y customer name" aria-describedby="button-addon7"
                                           class="form-control">

                                    <!-- {{-- search button --}} -->
                                    <div class="input-group-prepend">
                                        <button type="submit" class="btn btn-dark"
                                                @click.prevent="search(searchbycustomer,'searchbycustomer')">
                                            <v-icon dark small>search</v-icon>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- {{-- end search by customer --}} -->

                </div>
            </transition>
            <!-- search new end -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-dark">
                    <tr class="">
                        <th scope="col">Trip id</th>
                        <th scope="col">Job id</th>
                        <th scope="col">Date</th>
                        <th scope="col">Vehicle</th>
                        <th class="text-center" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="trip in trips" v-bind:key="trip.trip_id">
                        <td>{{trip.trip_id}}</td>
                        <td>{{trip.job_id}}</td>
                        <td>{{trip.date}}</td>
                        <td>{{trip.vehicle}}</td>
                        <td class="text-right">
                            <v-icon
                                color="success"
                                class="mx-1"
                                @click="editTrip(trip)"
                            >
                                edit
                            </v-icon>

                            <v-icon
                                color="red lighten-1"
                                class="mx-1"
                                @click="deleteTrip(trip.trip_id)"
                            >
                                delete
                            </v-icon>
                            <v-icon
                                color="info"
                                class="mx-1"
                                @click="showTrip(trip)"
                            >
                                person
                            </v-icon>

                        </td>
                    </tr>

                    </tbody>
                </table>
                <v-alert
                    v-if="noTripfound"
                    border="left"
                    tile
                    type="warning"
                    dense
                    dark
                    elevation="2"
                    class="mb-12"
                    align="center"
                >
                    Sorry, No Trip Found !
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


        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <!----------------------------Mobile Version---------------------------->
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->        <div v-if="$mq == 'mobile'" class="text-center mx-auto">
            <NavbarDriver></NavbarDriver>
            <!-- header -->
            <div class="mt-10 text-center ">

                <div
                    class=" text-uppercase"
                    style="font-size: 4em;color: rgb(124, 124, 124);"
                >
                    Trips
                </div>
                <v-btn

                    link
                    tile
                    outlined
                    color="success indentElem "
                    class="mt-6"
                    @click="addTrip"
                    style="width: 90%"

                >
                    <v-icon left>mdi-pencil</v-icon>
                    <div>
                        New Trip
                    </div>
                </v-btn>


            </div>
            <hr>


            <!-- {{-- collapse button --}} -->
            <v-btn  outlined tile color="info" @click="showSearchOption = !showSearchOption" class="mb-4 indentElem" >
                <v-icon color="info darken-1" class="mx-3">
                    arrow_drop_down
                </v-icon>
                 search options
            </v-btn>
            <!-- {{-- end collapse button --}} -->

            <transition mode="out-in">
                <div class="card-deck my-6 mx-10" v-if="showSearchOption">
                    <!-- {{-- search by date --}} -->
                    <div class="card bg-light">
                        <div class="card-header">Search by date</div>
                        <div class="card-body text-center">
                            <div class="input-group mb-4">
                                <input type="date" v-model="searchbydate" max="3000-12-31" min="1000-01-01"
                                       class="form-control">
                                <!-- {{-- search button --}} -->
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-dark"
                                            @click.prevent="search(searchbydate,'searchbydate')">
                                        <v-icon dark small>search</v-icon>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- {{-- end search by date --}} -->


                    <!-- {{-- search by job id --}} -->

                    <div class="card bg-light">
                        <div class="card-header">Search by job id</div>
                        <div class="card-body text-center">
                            <form @submit.prevent='search(searchbyjobid,"searchbyjobid")'>
                                <div class="input-group mb-4">
                                    <!-- {{-- search box --}} -->
                                    <input type="search" v-model="searchbyjobid" name="searchbycustomer"
                                           placeholder="Search by job id" aria-describedby="button-addon7"
                                           class="form-control">

                                    <!-- {{-- search button --}} -->
                                    <div class="input-group-prepend">
                                        <button type="submit" class="btn btn-dark"
                                        >
                                            <v-icon dark small>search</v-icon>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- {{-- end search by job id --}} -->

                    <!-- {{-- search by customer --}} -->

                    <div class="card bg-light">
                        <div class="card-header">Search by customer</div>
                        <div class="card-body text-center">
                            <form>
                                <div class="input-group mb-4">
                                    <!-- {{-- search box --}} -->
                                    <input type="search" v-model="searchbycustomer" name="searchbycustomer"
                                           placeholder="search y customer name" aria-describedby="button-addon7"
                                           class="form-control">

                                    <!-- {{-- search button --}} -->
                                    <div class="input-group-prepend">
                                        <button type="submit" class="btn btn-dark"
                                                @click.prevent="search(searchbycustomer,'searchbycustomer')">
                                            <v-icon dark small>search</v-icon>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- {{-- end search by customer --}} -->

                </div>
            </transition>
            <!-- search new end -->
<!--table-->
            <div class="container mx-auto">

            <div class=" table-responsive ">
                <table class=" table table-hover table-striped">
                    <thead class="thead-dark">
                    <tr class="">
                        <th scope="col">Trip id</th>
                        <th scope="col">Job id</th>
                        <th scope="col">Date</th>
                        <th scope="col">Vehicle</th>
                        <th class="text-center" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="trip in trips" v-bind:key="trip.trip_id" >
                        <td class="align-middle fitCell">{{trip.trip_id}}</td>
                        <td class="align-middle fitCell">{{trip.job_id}}</td>
                        <td class="align-middle fitCell">{{trip.date}}</td>
                        <td class="align-middle fitCell">{{trip.vehicle}}</td>
                        <td class="text-right align-middle fitCell">
                            <v-icon
                                color="success"
                                class="ma-4"
                                @click="editTrip(trip)"
                            >
                                edit
                            </v-icon>

                            <v-icon
                                color="red lighten-1"
                                class="ma-4"
                                @click="deleteTrip(trip.trip_id)"
                            >
                                delete
                            </v-icon>
                            <v-icon
                                color="info"
                                class="ma-4"
                                @click="showTrip(trip)"
                            >
                                person
                            </v-icon>

                        </td>
                    </tr>

                    </tbody>
                </table>
                <v-alert
                    v-if="noTripfound"
                    border="left"
                    tile
                    type="warning"
                    dense
                    dark
                    elevation="2"
                    class="mb-12"
                    align="center"
                >
                    Sorry, No Trip Found !
                </v-alert>
            </div>
            </div>

            <v-pagination
                class="my-6"
                :total-visible="5"
                v-model="current_page"
                :length="last_page"
                @input="next"
            >
            </v-pagination>

        </div>


    </div>

</template>
<script>

    import NavbarDriver from '@/js/components/navbars/Driver.vue';
    import Drawer from '@/js/components/drawers/Driver.vue';

    export default {
        beforeMount() {
            this.fetchItems();
            this.searchQuery = null;

        },
        components: {
            NavbarDriver,
            Drawer,
        },
        data() {
            return {
                trips: [],
                current_page: 1,
                last_page: 1,
                noTripfound: false,
                searchbyjobid: '',
                searchbydate: '',
                searchbydriver: '',
                searchbycustomer: '',
                showSearchOption: false,
                searchQuery: null,
                badgeId: '',
            };
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            search(searchValue, type) {
                console.log('search value : ' + searchValue + 'type : ' + type);
                if (searchValue == '') {
                    this.fetchItems();
                    this.noTripfound = false;
                    return;
                }
                let vm = this;
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token_driver;
                axios.get(this.BASE_URL() + '/api/driver/trips/search?' + type + '=' + searchValue)
                    .then(res => {
                        console.log(res.data);
                        if (res.data.data.length == 0) {
                            console.log('no drii');
                            this.noTripfound = true;
                            this.trips = {};
                            this.searchQuery = null;
                        } else {
                            console.log("trip found meta =>");
                            console.log(res.data.meta);
                            this.noTripfound = false;
                            this.trips = res.data.data;
                        }
                        this.searchQuery = type + '=' + searchValue;
                        vm.makePagination(res.data.meta, res.data.links);
                    })
                    .catch(error => {
                        console.log('search errors : ');
                        console.log(error);
                        this.inputErrors = error.response.data.errors;
                        $('html, body').animate({scrollTop: 0}, '200');
                    });
            },
            fetchItems(page_url) {
                let vm = this;
                page_url = page_url || this.BASE_URL() + '/api/driver/trips';
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token_driver;
                axios.get(page_url)
                    .then(res => {
                        this.trips = res.data.data;
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


            next(page) {
                if (this.searchQuery != null) {
                    this.fetchItems(this.BASE_URL() + '/api/driver/trips/search?' + this.searchQuery + '&page=' + page);
                    // example of searchQuery : searchbydriver=979
                } else {
                    this.fetchItems(this.BASE_URL() + '/api/driver/trips/' + '?page=' + page);
                }

            },


            deleteTrip(id) {
                this.$swal.fire({
                    title: 'Are you sure?',
                    text: "Trip with id " + id + " will be deleted !",
                    type: 'question',
                    animation: false,
                    focusConfirm: false,
                    padding: "2rem",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#424242',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token_driver;
                        axios.delete(this.BASE_URL() + '/api/driver/trip/' + id)
                            .then(data => {
                                console.log(data);
                                this.fetchItems();
                            })
                            .catch(err => console.log(err));
                        this.$swal.fire(
                            'Deleted!',
                            'Trip has been deleted.',
                            'success'
                        )
                    }
                })
            },


            addTrip() {
                this.$router.push({name: "DriverDashboard_trips_add"})
            },


            showTrip: function (trip) {
                this.$router.push({name: "DriverDashboard_trips_show", params: {id: trip.trip_id}})
            },


            editTrip(trip) {

                this.$router.push({name: "DriverDashboard_trips_edit", params: {id: trip.trip_id}})

            },
        }
    }
</script>
<style scoped>
    .wrapper_box {
        display: grid;
        grid-gap: 2em;
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

    a, a:hover {
        text-decoration: none;
    }

    .wrapper_header {
        display: grid;
        grid-template-columns: 10fr 2fr;
        grid-auto-rows: minmax(50px, auto);
        align-items: center;
        /* background-color: rgb(255, 153, 0); */

    }

    .title_header {
        justify-self: start;
        font-family: 'roboto';
        font-size: 3em;
        color: rgb(124, 124, 124);

    }

    .button_header {
        justify-self: end;
    }
    .indentElem{
        width: 90%;
    }
    .vertical-center {
        min-height: 100%;  /* Fallback for browsers do NOT support vh unit */
        min-height: 100vh; /* These two lines are counted as one :-)       */

        display: flex;
        align-items: center;
    }
    .fitCell{
        max-width:100%;white-space:nowrap;
    }
</style>
