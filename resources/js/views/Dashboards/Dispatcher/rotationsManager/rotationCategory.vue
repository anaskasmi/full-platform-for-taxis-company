<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div class="wrapper_box">
                <NavbarDispatcher></NavbarDispatcher>
                <Drawer></Drawer>
                <!-- start add dialog -->
                <v-dialog v-model="addDialogIsOpen" max-width="850px">
                    <v-card v-if="addDialogIsOpen">
                        <v-card-text>
                            <v-container>
                                <div class="row float-right">
                                    <v-btn text color="black" class="float-right" @click="closeAddDialog()">
                                        <v-icon>close</v-icon>
                                    </v-btn>
                                </div>
                                <v-container>
                                    <v-row class="mt-12">
                                        <v-text-field
                                            autocomplete="false"
                                            class
                                            label="Vehicle Number"
                                            required
                                            v-model="addForm.number"
                                            v-on:keyup.enter="addVehicle()"
                                        ></v-text-field>
                                    </v-row>
                                    <v-row class>
                                        <v-text-field
                                            autocomplete="false"
                                            class
                                            label="Vehicle Type (van,car,..)"
                                            required
                                            v-model="addForm.type"
                                            v-on:keyup.enter="addVehicle()"
                                        ></v-text-field>
                                    </v-row>
                                    <v-row v-for="(rotationsCategory, index) in rotationsCategories" :key="index" class>
                                        <v-text-field
                                            autocomplete="false"
                                            class
                                            :label="rotationsCategory.name+ ' total marks'"
                                            required
                                            v-model="addForm.marksByCategory[rotationsCategory.id]"
                                        ></v-text-field>
                                    </v-row>
                                    <v-row>
                                        <v-btn color="info" class="mt-2" tile block @click="addVehicle()">Add</v-btn>
                                    </v-row>
                                </v-container>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <!-- end add dialog -->

                <!-- start edit dialog -->
                <v-dialog v-model="editDialogIsOpen" max-width="500px">
                    <v-card v-if="editDialogIsOpen">
                        <v-card-text>
                            <v-container>
                                <div class="row float-right">
                                    <v-btn text color="black" class="float-right" @click="closeEditDialog()">
                                        <v-icon>close</v-icon>
                                    </v-btn>
                                </div>
                                <v-container>
                                    <v-row class="mt-12">
                                        <v-text-field
                                            autocomplete="false"
                                            class
                                            label="Vehicle Number"
                                            required
                                            v-model="editForm.number"
                                        ></v-text-field>
                                    </v-row>
                                    <v-row class>
                                        <v-text-field
                                            autocomplete="false"
                                            class
                                            label="Vehicle Type(van,car,..)"
                                            required
                                            v-model="editForm.type"
                                            v-on:keyup.enter="editVehicle()"
                                        ></v-text-field>
                                    </v-row>
<!--                                    <v-row v-for="(rotationsCategory, index) in rotationsCategories" :key="index" class>-->
<!--                                        <v-text-field-->
<!--                                            autocomplete="false"-->
<!--                                            class-->
<!--                                            :label="rotationsCategory.name+ ' total marks'"-->
<!--                                            required-->
<!--                                            v-model="editForm.marksByCategory[rotationsCategory.id]"-->
<!--                                        ></v-text-field>-->
<!--                                    </v-row>-->
                                    <v-row>
                                        <v-btn color="info" class="mt-2" tile block @click="editVehicle()">UPDATE
                                        </v-btn>
                                    </v-row>
                                </v-container>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <!-- end edit dialig -->

                <!-- start show vehicle marks dialog -->
                <v-dialog v-model="marksDialogIsOpen" max-width="500px">
                    <v-card v-if="marksDialogIsOpen">
                        <v-card-text>
                            <v-container>
                                <div class="row float-right mb-4">
                                    <v-btn text color="black" class="float-right" @click="closeMarksDialog()">
                                        <v-icon>close</v-icon>
                                    </v-btn>
                                </div>
                                <v-container>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <tbody>
                                            <tr v-for="(rotationsCategory, index) in rotationsCategories" :key="index">
                                                <th>{{rotationsCategory.name}}</th>
                                                <th
                                                    style="color:#0984e3"
                                                    class="text-center"
                                                    v-if="openedVehicleMarks[rotationsCategory.id]"
                                                >{{openedVehicleMarks[rotationsCategory.id]}}
                                                </th>
                                                <th style="color:#0984e3" class="text-center" v-else>0</th>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </v-container>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <!-- start show vehicle marks dialog-->
                <!-- header -->
                <div class="col-12">
                    <v-btn color="grey" text tile class="float-left" :to="rotationsManagerRoute">
                        <v-icon class="mr-2">keyboard_return</v-icon>
                        Rotations Manager
                    </v-btn>
                </div>
                <div class="wrapper_header mt-10">
                    <div class="title_header text-uppercase">{{currentCategory.name}}</div>
                    <v-btn
                        link
                        tile
                        class="ma-2 button_header"
                        outlined
                        color="success "
                        @click="openAddDialog()"
                    >
                        <v-icon left>mdi-pencil</v-icon>
                        <div>New Vehicle</div>
                    </v-btn>
                </div>
                <hr/>
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
                            <th v-else>{{vehicle.type}}</th>
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
                                <v-icon color="red" class="mx-1" @click="deleteVehicle(vehicle)">delete</v-icon>
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

                                <v-icon color="red" class="mx-1" @click="deleteVehicle(vehicle)">delete</v-icon>
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
            <NavbarDispatcher></NavbarDispatcher>
            <div class="container px-4">

                <div class="">

                    <!-- start add dialog -->
                    <v-dialog v-model="addDialogIsOpen" max-width="850px">
                        <v-card v-if="addDialogIsOpen">
                            <v-card-text>
                                <v-container>
                                    <div class="row float-right">
                                        <v-btn text color="black" class="float-right" @click="closeAddDialog()">
                                            <v-icon>close</v-icon>
                                        </v-btn>
                                    </div>
                                    <v-container>
                                        <v-row class="mt-12">
                                            <v-text-field
                                                autocomplete="false"
                                                class
                                                label="Vehicle Number"
                                                required
                                                v-model="addForm.number"
                                                v-on:keyup.enter="addVehicle()"
                                            ></v-text-field>
                                        </v-row>
                                        <v-row class>
                                            <v-text-field
                                                autocomplete="false"
                                                class
                                                label="Vehicle Type (van,car,..)"
                                                required
                                                v-model="addForm.type"
                                                v-on:keyup.enter="addVehicle()"
                                            ></v-text-field>
                                        </v-row>
                                        <v-row v-for="(rotationsCategory, index) in rotationsCategories" :key="index"
                                               class>
                                            <v-text-field
                                                autocomplete="false"
                                                class
                                                :label="rotationsCategory.name+ ' total marks'"
                                                required
                                                v-model="addForm.marksByCategory[rotationsCategory.id]"
                                            ></v-text-field>
                                        </v-row>
                                        <v-row>
                                            <v-btn color="info" class="mt-2" tile block @click="addVehicle()">Add
                                            </v-btn>
                                        </v-row>
                                    </v-container>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                    <!-- end add dialog -->

                    <!-- start edit dialog -->
                    <v-dialog v-model="editDialogIsOpen" max-width="500px">
                        <v-card v-if="editDialogIsOpen">
                            <v-card-text>
                                <v-container>
                                    <div class="row float-right">
                                        <v-btn text color="black" class="float-right" @click="closeEditDialog()">
                                            <v-icon>close</v-icon>
                                        </v-btn>
                                    </div>
                                    <v-container>
                                        <v-row class="mt-12">
                                            <v-text-field
                                                autocomplete="false"
                                                class
                                                label="Vehicle Number"
                                                required
                                                v-model="editForm.number"
                                            ></v-text-field>
                                        </v-row>
                                        <v-row class>
                                            <v-text-field
                                                autocomplete="false"
                                                class
                                                label="Vehicle Type(van,car,..)"
                                                required
                                                v-model="editForm.type"
                                                v-on:keyup.enter="editVehicle()"
                                            ></v-text-field>
                                        </v-row>
                                        <v-row v-for="(rotationsCategory, index) in rotationsCategories" :key="index"
                                               class>
                                            <v-text-field
                                                autocomplete="false"
                                                class
                                                :label="rotationsCategory.name+ ' total marks'"
                                                required
                                                v-model="editForm.marksByCategory[rotationsCategory.id]"
                                            ></v-text-field>
                                        </v-row>
                                        <v-row>
                                            <v-btn color="info" class="mt-2" tile block @click="editVehicle()">UPDATE
                                            </v-btn>
                                        </v-row>
                                    </v-container>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                    <!-- end edit dialig -->

                    <!-- start show vehicle marks dialog -->
                    <v-dialog v-model="marksDialogIsOpen" max-width="500px">
                        <v-card v-if="marksDialogIsOpen">
                            <v-card-text>
                                <v-container>
                                    <div class="row float-right mb-4">
                                        <v-btn text color="black" class="float-right" @click="closeMarksDialog()">
                                            <v-icon>close</v-icon>
                                        </v-btn>
                                    </div>
                                    <v-container>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <tbody>
                                                <tr v-for="(rotationsCategory, index) in rotationsCategories"
                                                    :key="index">
                                                    <th>{{rotationsCategory.name}}</th>
                                                    <th
                                                        style="color:#0984e3"
                                                        class="text-center"
                                                        v-if="openedVehicleMarks[rotationsCategory.id]"
                                                    >{{openedVehicleMarks[rotationsCategory.id]}}
                                                    </th>
                                                    <th style="color:#0984e3" class="text-center" v-else>0</th>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </v-container>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                    <!-- start show vehicle marks dialog-->
                    <!-- header -->

                    <v-btn color="grey" text tile block class="" :to="rotationsManagerRoute">
                        <v-icon class="mr-2">keyboard_return</v-icon>
                        Rotations Manager
                    </v-btn>
                    <hr>
                    <div
                        class=" text-uppercase text-center my-4"
                        style="font-size: 2em;color: rgb(124, 124, 124);"
                    >
                        {{currentCategory.name}}
                    </div>


                    <v-btn
                        link
                        tile
                        block
                        class=""
                        outlined
                        color="success "
                        @click="openAddDialog()"
                    >
                        <v-icon left>mdi-pencil</v-icon>
                        <div>New Vehicle</div>
                    </v-btn>
                    <hr/>
                    <!-- progress -->
                    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                    <!-- table -->
                    <div class="table-responsive" v-if="!isLoading">
                        <table class="table table-hover" style="">
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
                                    class="text-uppercase table-primary align-middle fitCell"
                                >{{vehicle.type}}-{{vehicle.number}}
                                </th>
                                <th v-else class="text-uppercase middle fitCell">{{vehicle.type}}-{{vehicle.number}}
                                </th>
                                <th
                                    v-if="minVehicleMarksAndNumber.id == vehicle.id"
                                    class="text-uppercase table-primary align-middle fitCell"
                                >{{vehicle.type}}
                                </th>
                                <th v-else class="middle fitCell">{{vehicle.type}}</th>
                                <th
                                    v-if="minVehicleMarksAndNumber.id == vehicle.id"
                                    class="text-uppercase table-primary align-middle fitCell"
                                >{{vehicle.number}}
                                </th>
                                <th v-else class="middle fitCell">{{vehicle.number}}</th>
                                <th
                                    v-if="minVehicleMarksAndNumber.id == vehicle.id"
                                    class="text-uppercase table-primary align-middle fitCell"
                                >{{vehicle.totalMarks}}
                                </th>
                                <th v-else class="middle fitCell">{{vehicle.totalMarks}}</th>
                                <td class="align-middle fitCell table-primary  "
                                    v-if="minVehicleMarksAndNumber.id == vehicle.id">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text class="ma-0 pa-0" v-on="on"
                                                   @click="openVehicleHistory(vehicle)">
                                                <v-icon color="info" class="ma-2">mdi-file-document</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Click To Go To This Vehicle's History</span>
                                    </v-tooltip>

                                    <v-icon color="red" class="ma-2" @click="deleteVehicle(vehicle)">delete</v-icon>
                                </td>
                                <td v-else class="align-middle fitCell">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text class="ma-0 pa-0" v-on="on"
                                                   @click="openVehicleHistory(vehicle)">
                                                <v-icon color="info" class="ma-2">mdi-file-document</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Click To Go To This Vehicle's History</span>
                                    </v-tooltip>

                                    <v-icon color="red" class="ma-2" @click="deleteVehicle(vehicle)">delete</v-icon>
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
    import NavbarDispatcher from "@/js/components/navbars/Dispatcher.vue";
    import Drawer from "@/js/components/drawers/Dispatcher.vue";

    export default {
        beforeMount() {
            this.fetchItems();
        },
        components: {
            NavbarDispatcher,
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
                rotationsManagerRoute: {name: "DispatcherDashboard_rotationsManager"},
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
                                return -1 * modifier;
                            if (
                                parseInt(a[this.currentSort], 10) >
                                parseInt(b[this.currentSort], 10)
                            )
                                return 1 * modifier;
                            if (
                                parseInt(a[this.currentSort], 10) ==
                                parseInt(b[this.currentSort], 10)
                            )
                                return 1 * modifier;
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
                return Math.round(this.vehicles.length / this.pageSize) + 1;
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
                    vehicle.marksByCategory[this.$route.params.id]= parseInt(
                        vehicle.marksByCategory[this.$route.params.id], 10
                    );
                    vehicle.number= parseInt(
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
                let url = this.BASE_URL() + "/api/dispatcher/vehicles";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;

                axios
                    .get(url)
                    .then(res => {
                        this.vehicles = res.data;
                        this.fetchRotationsCategories();
                        //get min vehicle in marks
                        this.parsIntFromItems();
                        let minMarks = this.vehicles[0].marksByCategory[
                            this.$route.params.id
                            ];
                        this.minVehicleMarks = this.vehicles[0];
                        this.vehicles.forEach(vehicle => {
                            vehicle.totalMarks = vehicle.marksByCategory[this.$route.params.id];
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
                        //get the car with the smallest number
                        let smallestNumber = this.arrayOfEqualVehicles[0].number;
                        this.minVehicleMarksAndNumber = this.arrayOfEqualVehicles[0];
                        this.arrayOfEqualVehicles.forEach(vehicle => {
                            if (vehicle.number < smallestNumber) {
                                smallestNumber = vehicle.number;
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
                let url = this.BASE_URL() + "/api/dispatcher/rotationsCategories";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .get(url)
                    .then(res => {
                        this.rotationsCategories = res.data.data;

                        this.addForm["marksByCategory"] = {};
                        this.rotationsCategories.forEach(rotationsCategory => {
                            this.addForm.marksByCategory[rotationsCategory.id] = 0;
                            if (rotationsCategory.id == this.$route.params.id) {
                                this.currentCategory = rotationsCategory;
                            }
                        });
                    })
                    .catch(error => {
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            openAddDialog() {
                this.addDialogIsOpen = true;
                this.addForm.type = "car";
            },
            closeAddDialog() {
                this.addDialogIsOpen = false;
                this.addForm = {};
            },
            openVehicleHistory(vehicle) {
                this.$router.push({
                    name: "DispatcherDashboard_rotations_byCategories_byVehicle",
                    params: {category_id: this.currentCategory.id, vehicle_id: vehicle.id}
                });
            },
            openEditDialog(vehicle) {
                this.editDialogIsOpen = true;
                this.editedId = vehicle.id;
                this.editForm.number = vehicle.number;
                this.editForm.type = vehicle.type;
                this.editForm.marksByCategory = vehicle.marksByCategory;
            },
            openMarksDialog(vehicle) {
                this.marksDialogIsOpen = true;
                this.openedVehicleMarks = vehicle.marksByCategory;
            },
            closeEditDialog(vehicle) {
                this.editDialogIsOpen = false;
                this.editedId = null;
                this.vehicleNumber_editForm = null;
                this.vehicleType_editForm = null;
                this.vehicleTotalMarks_editForm = null;
            },
            closeMarksDialog(vehicle) {
                this.marksDialogIsOpen = false;
                this.openedVehicleMarks = {};
            },

            addVehicle() {
                if (!this.addForm.type || !this.addForm.number) {
                    this.$swal({
                        title: "Invalid enteries !",
                        text: "please make sure the input is Filled",
                        type: "warning",
                        timer: 3000
                    });
                    return;
                }
                let url = this.BASE_URL() + "/api/dispatcher/vehicle";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .post(url, this.addForm)
                    .then(res => {
                        this.$swal({
                            title: "Success",
                            text: "Vehicle added successfully!",
                            type: "success",
                            timer: 2000
                        });
                        this.fetchItems();
                        this.closeAddDialog();
                    })
                    .catch(error => {
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            editVehicle() {
                this.editForm._method = "put";
                let url = this.BASE_URL() + "/api/dispatcher/vehicle/" + this.editedId;
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .post(url, this.editForm)
                    .then(res => {
                        this.$swal({
                            title: "Success",
                            text: "Vehicle Updated successfully!",
                            type: "success",
                            timer: 2000
                        });
                        this.fetchItems();
                        this.closeEditDialog();
                    })
                    .catch(error => {
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            deleteVehicle(vehicle) {
                this.$swal
                    .fire({
                        text: "Are You Sure You Want To Delete This Vehicle?",
                        type: "question",
                        animation: true,
                        focusConfirm: false,
                        padding: "2rem",
                        showCancelButton: true,
                        confirmButtonText: "Yes"
                    })
                    .then(res => {
                        if (res.value) {
                            let url = this.BASE_URL() + "/api/dispatcher/vehicle/" + vehicle.id;
                            axios.defaults.headers.common["Authorization"] =
                                "Bearer " + this.$store.state.token_dispatcher;
                            axios
                                .delete(url)
                                .then(res => {
                                    this.fetchItems();
                                    this.$swal({
                                        title: "Success",
                                        text: "Vehicle Deleted successfully!",
                                        type: "success",
                                        timer: 2000
                                    });
                                })
                                .catch(error => {
                                    this.$swal("Try again", error.response.data.errors, "warning");
                                });
                        }
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

    .fitCell {
        max-width: 100%;
        white-space: nowrap;
    }
</style>
