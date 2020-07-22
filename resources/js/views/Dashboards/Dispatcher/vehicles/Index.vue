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
                                    <v-row v-for="(rotationsCategory, index) in rotationsCategories" :key="index" class>
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
                    <div class="title_header text-uppercase">Vehicles</div>
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
                            <th scope="col">Name</th>
                            <th scope="col">Type</th>
                            <th scope="col">Number</th>
                            <th scope="col">Total Marks</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(vehicle,i) in vehicles" :key="i">
                            <th class="text-uppercase">{{vehicle.type}}-{{vehicle.number}}</th>
                            <th>{{vehicle.type}}</th>
                            <th>{{vehicle.number}}</th>
                            <td>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn outlined color="teal darken-3" v-on="on"
                                               @click="openMarksDialog(vehicle)">
                                            <v-icon color="teal darken-3" class="mx-1">view_list</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Click To open Vehicle Marks</span>
                                </v-tooltip>
                            </td>
                            <td class="text-right">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn
                                            text
                                            class="ma-0 pa-0 "
                                            v-on="on"
                                            @click="openVehicleHistory(vehicle)"
                                        >
                                            <v-icon color="info" class="mx-1">mdi-file-document</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Click To Go To This Vehicle's History</span>
                                </v-tooltip>

                                <v-icon color="green" class="mx-1" @click="openEditDialog(vehicle)">edit</v-icon>
                                <v-icon color="red" class="mx-1" @click="deleteVehicle(vehicle)">delete</v-icon>
                            </td>
                        </tr>
                        </tbody>
                    </table>
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

                <div class="wrapper_box">

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
                        <v-btn color="grey" text tile class="my-4" block :to="rotationsManagerRoute">
                            <v-icon class="mr-2">keyboard_return</v-icon>
                            Rotations Manager
                        </v-btn>
                    <hr>
                    <div
                        class=" text-uppercase text-center"
                        style="font-size: 2em;color: rgb(124, 124, 124);"
                    >
                        Vehicles
                    </div>


                        <v-btn
                            link
                            block
                            tile
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
                        <table class="table table-hover table-striped " >
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col"  class="fitCell align-middle" >Name</th>
                                <th scope="col"  class="fitCell align-middle">Type</th>
                                <th scope="col"  class="fitCell align-middle">Number</th>
                                <th scope="col"  class="fitCell align-middle">Total Marks</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(vehicle,i) in vehicles" :key="i">
                                <th class="text-uppercase fitCell align-middle">{{vehicle.type}}-{{vehicle.number}}</th>
                                <th class="fitCell align-middle">{{vehicle.type}}</th>
                                <th class="fitCell align-middle">{{vehicle.number}}</th>
                                <td class="fitCell align-middle">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn outlined color="teal darken-3" v-on="on"
                                                   @click="openMarksDialog(vehicle)">
                                                <v-icon color="teal darken-3" class="mx-1">view_list</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Click To open Vehicle Marks</span>
                                    </v-tooltip>
                                </td>
                                <td class="text-right">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn
                                                text
                                                class="ma-0 pa-0 "
                                                v-on="on"
                                                @click="openVehicleHistory(vehicle)"
                                            >
                                                <v-icon color="info" class="ma-4">mdi-file-document</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Click To Go To This Vehicle's History</span>
                                    </v-tooltip>

                                    <v-icon color="green" class="ma-4" @click="openEditDialog(vehicle)">edit</v-icon>
                                    <v-icon color="red" class="ma-4" @click="deleteVehicle(vehicle)">delete</v-icon>
                                </td>
                            </tr>
                            </tbody>
                        </table>
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
                openedVehicleMarks: {},
                noResultfound: false,
                addDialogIsOpen: false,
                editDialogIsOpen: false,
                marksDialogIsOpen: false,
                editedId: "",
                addForm: {},
                editForm: {},
                isLoading: true,
                rotationsManagerRoute: {name: "DispatcherDashboard_rotationsManager"}
            };
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
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
                        this.isLoading = false;
                        if (!this.vehicles) {
                            this.noResultfound = true;
                        }
                    })
                    .catch(error => {
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
                    name: "DispatcherDashboard_vehicleHistory",
                    params: {id: vehicle.id}
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
    .fitCell{
        max-width:100%;white-space:nowrap;
    }

</style>
