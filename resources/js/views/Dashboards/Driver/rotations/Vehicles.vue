<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">
            <div class="wrapper_box">
                <NavbarDriver></NavbarDriver>
                <Drawer></Drawer>
                <!-- header -->
                <div class="col-12">
                    <v-btn color="grey" text tile class="float-left" :to="backRoute">
                        <v-icon class="mr-2">keyboard_return</v-icon>
                        Rotations Categories
                    </v-btn>
                </div>
                <div class="wrapper_header mt-10">
                    <div class="title_header text-uppercase">{{currentCategory}}</div>
                </div>
                <hr/>
                <div class="sentence">Pick a Vehicle !</div>
                <!-- progress -->
                <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                <!-- table -->
                <div class="table-responsive" v-if="!isLoading">
                    <table class="table table-hover" style="table-layout:fixed">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col" style="cursor:pointer" @click="sort('number')">Name</th>
                            <th scope="col" style="cursor:pointer" @click="sort('type')">Type</th>
                            <th scope="col" style="cursor:pointer" @click="sort('number')">Number</th>
                            <th scope="col" style="cursor:pointer" @click="sort('totalMarks')">Total Marks</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(vehicle,i) in sortedRows" :key="i">
                            <th
                                v-if="minVehicleMarksAndNumber.id == vehicle.id"
                                class="text-uppercase table-primary"
                            >{{vehicle.type}}-{{vehicle.number}}
                            </th>
                            <th v-else class="text-uppercase">{{vehicle.type}}-{{vehicle.number}}</th>
                            <th
                                v-if="minVehicleMarksAndNumber.id == vehicle.id"
                                class="text-uppercase table-primary"
                            >{{vehicle.type}}
                            </th>
                            <th v-else class="text-uppercase">{{vehicle.type}}</th>
                            <th
                                v-if="minVehicleMarksAndNumber.id == vehicle.id"
                                class="text-uppercase table-primary"
                            >{{vehicle.number}}
                            </th>
                            <th v-else>{{vehicle.number}}</th>
                            <th
                                v-if="minVehicleMarksAndNumber.id == vehicle.id"
                                class="text-uppercase table-primary"
                            >{{vehicle.totalMarks}}
                            </th>
                            <th v-else>{{vehicle.totalMarks}}</th>
                            <td class="text-right table-primary" v-if="minVehicleMarksAndNumber.id == vehicle.id">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn text class="ma-0 pa-0" v-on="on" @click="openVehicleHistory(vehicle)">
                                            <v-icon color="info" class="mx-1">mdi-file-document</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Click To Go To This Vehicle's History</span>
                                </v-tooltip>
                            </td>
                            <td v-else class="text-right">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn text class="ma-0 pa-0" v-on="on" @click="openVehicleHistory(vehicle)">
                                            <v-icon color="info" class="mx-1">mdi-file-document</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Click To Go To This Vehicle's History</span>
                                </v-tooltip>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <!--                    <div class="text-center">-->
                    <!--                        <v-pagination v-model="currentPage" :length="nb_pages"></v-pagination>-->
                    <!--                    </div>-->
                </div>
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
                <div class="">
                    <!-- header -->
                    <div class="row justify-center  mb-6">
                        <v-btn color="grey" text tile class="text-center" :to="backRoute">
                            <v-icon class="mr-2">keyboard_return</v-icon>
                            Rotations Categories
                        </v-btn>
                    </div>
                    <div
                        class=" text-uppercase text-center"
                        style="font-size: 2em;color: rgb(124, 124, 124);"
                    >
                        {{currentCategory}}
                    </div>

                    <hr/>
                    <div class="sentence text-center mb-5">Pick a Vehicle !</div>
                    <v-alert

                        tile
                        dense
                        color="#2A3B4D"
                        elevation="2"
                        class="mt-12 font-weight-light"
                        align="center"
                    >
                        <div class=" text-center text-white">PS : You can sort the table by clicking on headers!
                        </div>


                    </v-alert>
                    <!-- progress -->
                    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                    <!-- table -->
                    <div class="table-responsive" v-if="!isLoading">
                        <table class="table table-hover" style="table-layout:fixed">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col" style="cursor:pointer" class="align-middle fitCell"
                                    @click="sort('number')">Name
                                </th>
                                <th scope="col" style="cursor:pointer" class="align-middle fitCell"
                                    @click="sort('type')">Type
                                </th>
                                <th scope="col" style="cursor:pointer" class="align-middle fitCell"
                                    @click="sort('number')">Number
                                </th>
                                <th scope="col" style="cursor:pointer" class="align-middle fitCell"
                                    @click="sort('totalMarks')">Total Marks
                                </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(vehicle,i) in sortedRows" :key="i">
                                <th
                                    v-if="minVehicleMarksAndNumber.id == vehicle.id"
                                    class="text-uppercase table-primary align-middle fitCell "
                                >{{vehicle.type}}-{{vehicle.number}}
                                </th>
                                <th v-else class="text-uppercase align-middle fitCell">
                                    {{vehicle.type}}-{{vehicle.number}}
                                </th>
                                <th
                                    v-if="minVehicleMarksAndNumber.id == vehicle.id"
                                    class="text-uppercase table-primary align-middle fitCell"
                                >{{vehicle.type}}
                                </th>
                                <th v-else class="text-uppercase align-middle fitCell">{{vehicle.type}}</th>
                                <th
                                    v-if="minVehicleMarksAndNumber.id == vehicle.id"
                                    class="text-uppercase table-primary align-middle fitCell"
                                >{{vehicle.number}}
                                </th>
                                <th v-else class="align-middle fitCell">{{vehicle.number}}</th>
                                <th
                                    v-if="minVehicleMarksAndNumber.id == vehicle.id"
                                    class="text-uppercase table-primary align-middle fitCell"
                                >{{vehicle.totalMarks}}
                                </th>
                                <th v-else class="align-middle fitCell">{{vehicle.totalMarks}}</th>
                                <td class="text-right table-primary" v-if="minVehicleMarksAndNumber.id == vehicle.id">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text class="ma-0 pa-0" v-on="on"
                                                   @click="openVehicleHistory(vehicle)">
                                                <v-icon color="info" class="mx-1">mdi-file-document</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Click To Go To This Vehicle's History</span>
                                    </v-tooltip>
                                </td>
                                <td v-else class="text-right">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text class="ma-0 pa-0" v-on="on"
                                                   @click="openVehicleHistory(vehicle)">
                                                <v-icon color="info" class="mx-1">mdi-file-document</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Click To Go To This Vehicle's History</span>
                                    </v-tooltip>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <!--                        <div class="text-center">-->
                        <!--                            <v-pagination v-model="currentPage" :length="nb_pages"></v-pagination>-->
                        <!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>

    </div>


</template>
<script>
    import NavbarDriver from "@/js/components/navbars/Driver.vue";
    import Drawer from "@/js/components/drawers/Driver.vue";

    export default {
        beforeMount() {
            this.fetchItems();
        },
        components: {
            NavbarDriver,
            Drawer
        },
        data() {
            return {
                vehicles: [],
                rotationsCategories: {
                    marksByCategory: []
                },
                currentCategory: "",
                openedVehicleMarks: {},
                noResultfound: false,
                addDialogIsOpen: false,
                editDialogIsOpen: false,
                marksDialogIsOpen: false,
                editedId: "",
                addForm: {},
                editForm: {},
                isLoading: true,
                backRoute: {name: "DriverDashboard_rotations_categories"},
                currentSort: "",
                currentSortDir: "",
                pageSize: 10,
                currentPage: 1,
                minVehicleMarks: {},
                minVehicleMarksAndNumber: {},
                arrayOfEqualVehicles: []
            };
        },
        computed: {
            sortedRows: function () {
                return this.vehicles
                    .sort((a, b) => {
                        let modifier = 1;
                        if (this.currentSortDir === "desc") modifier = -1;


                        if (this.currentSort == "number") {
                            if (
                                parseInt(a[this.currentSort], 10) <
                                parseInt(b[this.currentSort], 10)
                            )
                                return 1 * modifier;
                            if (
                                parseInt(a[this.currentSort], 10) >
                                parseInt(b[this.currentSort], 10)
                            )
                                return -1 * modifier;
                            // if (
                            //     parseInt(a[this.currentSort], 10) ==
                            //     parseInt(b[this.currentSort], 10)
                            // )
                            //     return 1 * modifier;
                        } else if (this.currentSort == "totalMarks") {


                            return (a.totalMarks - b.totalMarks || a.number - b.number)*modifier;


                        } else {
                            if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                            if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                            if (a[this.currentSort] == b[this.currentSort]) return 1 * modifier;

                        }
                        return 0;
                    })
                    // .filter((row, index) => {
                    //     let start = (this.currentPage - 1) * this.pageSize;
                    //     let end = this.currentPage * this.pageSize;
                    //     if (index >= start && index < end) return true;
                    // })
                    ;
            },
            nb_pages() {
                return Math.ceil(this.vehicles.length / this.pageSize);
            }
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            sort: function (s) {
                if (s === this.currentSort) {
                    this.currentSortDir = this.currentSortDir === "asc" ? "desc" : "asc";
                }
                this.currentSort = s;
            },
            nextPage: function () {
                if (this.currentPage * this.pageSize < this.rotations.length)
                    this.currentPage++;
            },
            prevPage: function () {
                if (this.currentPage > 1) this.currentPage--;
            },
            parsIntFromItems() {
                this.vehicles.forEach(vehicle => {
                    vehicle.marksByCategory[this.$route.params.id] = parseInt(
                        vehicle.marksByCategory[this.$route.params.id], 10
                    );
                    vehicle.number = parseInt(
                        vehicle.number, 10
                    );
                });
            },
            sortVehiclesByMarksThenNumbers() {
                this.vehicles.sort(function (a, b) {
                    return a.totalMarks - b.totalMarks || a.number - b.number;
                });

            },
            fetchItems() {
                let url = this.BASE_URL() + "/api/driver/vehicles";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;

                axios
                    .get(url)
                    .then(res => {
                        this.vehicles = res.data;
                        this.fetchRotationsCategories();
                        this.parsIntFromItems();
                        //get min vehicle in marks
                        let minMarks = parseInt(
                            this.vehicles[0].marksByCategory[this.$route.params.id], 10
                        );
                        this.minVehicleMarks = this.vehicles[0];
                        this.vehicles.forEach(vehicle => {
                            vehicle.totalMarks =
                                parseInt(
                                    vehicle.marksByCategory[this.$route.params.category_id], 10
                                );
                            if (vehicle.totalMarks < minMarks) {
                                minMarks = vehicle.totalMarks;
                                this.minVehicleMarks = vehicle;
                            }
                        });
                        //get all vehicles with same marks
                        this.vehicles.forEach(vehicle => {
                            if (vehicle.totalMarks == this.minVehicleMarks.totalMarks) {
                                this.arrayOfEqualVehicles.push(vehicle);
                            }
                        });
                        //get the car with the smallest car number
                        let smallestNumber = parseInt(this.arrayOfEqualVehicles[0].number, 10);
                        this.minVehicleMarksAndNumber = this.arrayOfEqualVehicles[0];
                        this.arrayOfEqualVehicles.forEach(vehicle => {
                            if (parseInt(vehicle.number, 10) < smallestNumber) {
                                smallestNumber = parseInt(vehicle.number, 10);
                                this.minVehicleMarksAndNumber = vehicle;
                            }
                        });
                        //sort
                        this.sortVehiclesByMarksThenNumbers();
                        this.minVehicleMarksAndNumber = this.vehicles[0];
                        this.isLoading = false;
                        if (!this.vehicles) {
                            this.noResultfound = true;
                        }
                    })
                    .catch(error => {
                        console.log("error is : " + error);

                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            fetchRotationsCategories() {
                let url = this.BASE_URL() + "/api/driver/rotationsCategories";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .get(url)
                    .then(res => {
                        this.rotationsCategories = res.data.data;
                        this.addForm["marksByCategory"] = {};
                        this.rotationsCategories.forEach(rotationsCategory => {

                            this.addForm.marksByCategory[rotationsCategory.id] = 0;
                            if (rotationsCategory.id == this.$route.params.category_id) {
                                this.currentCategory = rotationsCategory.name;
                            }

                        });
                    })
                    .catch(error => {
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            openVehicleHistory(vehicle) {
                this.$router.push({
                    name: "DriverDashboard_rotations_vehicleHistory",
                    params: {vehicle_id: vehicle.id}
                });
            }
        }
    };
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

    a,
    a:hover {
        text-decoration: none;
    }

    .wrapper_header {
        display: grid;
        grid-template-columns: 10fr 4fr;
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
        margin-top: 2em;
        font-family: "Quicksand", sans-serif;
        justify-self: center;
        font-size: 2.5em;
    }

    .fitCell {
        max-width: 100%;
        white-space: nowrap;
    }
</style>
