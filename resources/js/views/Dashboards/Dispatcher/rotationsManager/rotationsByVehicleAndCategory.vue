<template>

    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div class="wrapper_box">
                <NavbarDispatcher></NavbarDispatcher>
                <Drawer></Drawer>
                <!-- start add dialog -->
                <v-dialog v-model="addDialogIsOpen" max-width="800px" persistent>
                    <v-card v-if="addDialogIsOpen">
                        <v-container>
                            <v-row class="col-12 justify-content-around align-content-center mx-0 px-0 pt-10">
                                <div
                                    class="text-uppercase font-weight-black mx-8"
                                    style="font-size: 2em;
    color: #2196f3;"
                                >New Rotation
                                </div>
                                <v-spacer></v-spacer>
                                <div class="float-right">
                                    <v-btn text color="black" class="float-right" @click="closeAddDialog()">
                                        <v-icon>close</v-icon>
                                    </v-btn>
                                </div>
                            </v-row>
                        </v-container>
                        <v-card-text>
                            <v-container>
                                <v-container>
                                    <!-- rotation type -->
                                    <v-row class="mt-12">
                                        <v-select
                                            autocomplete="false"
                                            label="Rotation Type"
                                            required
                                            v-model="addForm.rotation_type_id"
                                            :items="rotationsCategories"
                                            item-text="name"
                                            item-value="id"
                                        ></v-select>
                                    </v-row>
                                    <!-- vehicle -->
                                    <v-row>
                                        <v-select
                                            autocomplete="false"
                                            label="Vehicle"
                                            required
                                            v-model="addForm.vehicle_id"
                                            :items="vehicles"
                                            item-text="name"
                                            item-value="id"
                                        ></v-select>
                                    </v-row>
                                    <!-- city -->
                                    <v-row>
                                        <v-select
                                            autocomplete="false"
                                            label="City"
                                            required
                                            v-model="addForm.city_id"
                                            :items="cities"
                                            item-text="name"
                                            item-value="id"
                                        ></v-select>
                                    </v-row>

                                    <!-- type -->
                                    <v-row>
                                        <v-radio-group v-model="addForm.type" row>
                                            <v-radio label="One-way" value="One-way"></v-radio>
                                            <v-radio label="Return" value="Return"></v-radio>
                                        </v-radio-group>
                                    </v-row>
                                    <!-- marks -->
                                    <v-row>
                                        <v-text-field autocomplete="false" label="Marks" required
                                                      v-model="addForm.marks"></v-text-field>
                                    </v-row>
                                    <!-- job_id -->
                                    <v-row>
                                        <v-text-field autocomplete="false" label="Job ID" required
                                                      v-model="addForm.job_id"></v-text-field>
                                    </v-row>
                                    <!-- badge_id -->
                                    <v-row>
                                        <v-text-field
                                            autocomplete="false"
                                            label="Badge ID"
                                            required
                                            v-model="addForm.badge_id"
                                        ></v-text-field>
                                    </v-row>

                                    <!-- date -->
                                    <v-row>
                                        <v-col class="mx-0 px-0" cols="12">
                                            <v-menu
                                                ref="menu"
                                                v-model="menu"
                                                :close-on-content-click="false"
                                                :return-value.sync="addForm.date"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="290px"
                                            >
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field
                                                        v-model="addForm.date"
                                                        label="Date"
                                                        prepend-icon="event"
                                                        readonly
                                                        v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker v-model="addForm.date" no-title scrollable>
                                                    <v-spacer></v-spacer>
                                                    <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                                                    <v-btn text color="primary" @click="$refs.menu.save(addForm.date)">
                                                        OK
                                                    </v-btn>
                                                </v-date-picker>
                                            </v-menu>
                                        </v-col>
                                    </v-row>
                                    <!-- note -->
                                    <v-row>
                                        <v-textarea
                                            class
                                            outlined
                                            shaped
                                            label="Leave a Note Here .."
                                            type="textarea"
                                            v-model="addForm.note"
                                        ></v-textarea>
                                    </v-row>
                                    <!-- Comment  -->

                                    <v-row>
                                        <v-btn color="info" class="mt-2" tile block @click="addRotation()">Add</v-btn>
                                    </v-row>
                                </v-container>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <!-- end add dialog -->

                <!-- start edit dialog -->
                <v-dialog v-model="editDialogIsOpen" max-width="800px" persistent>
                    <v-card v-if="editDialogIsOpen">
                        <v-container>
                            <v-row class="col-12 justify-content-around align-content-center mx-0 px-0 pt-10">
                                <div
                                    class="text-uppercase font-weight-black mx-8"
                                    style="font-size: 2em;
    color: #2196f3;"
                                >Edit Rotation
                                </div>
                                <v-spacer></v-spacer>
                                <div class="float-right">
                                    <v-btn text color="black" class="float-right" @click="closeEditDialog()">
                                        <v-icon>close</v-icon>
                                    </v-btn>
                                </div>
                            </v-row>
                        </v-container>
                        <v-card-text>
                            <v-container>
                                <v-container>
                                    <!-- rotation category -->
                                    <!-- <v-row class="mt-12">
                                      <v-select
                                        autocomplete="false"
                                        label="Rotation Type"
                                        required
                                        v-model="editForm.rotation_type_id"
                                        :items="rotationsCategories"
                                        item-text="name"
                                        item-value="id"
                                      ></v-select>
                                    </v-row>-->
                                    <!-- vehicle -->
                                    <!-- <v-row>
                                      <v-select
                                        autocomplete="false"
                                        label="Vehicle"
                                        required
                                        v-model="editForm.vehicle_id"
                                        :items="vehicles"
                                        item-text="name"
                                        item-value="id"
                                      ></v-select>
                                    </v-row>-->
                                    <!-- city -->
                                    <v-row>
                                        <v-select
                                            autocomplete="false"
                                            label="City"
                                            required
                                            v-model="editForm.city_id"
                                            :items="cities"
                                            item-text="name"
                                            item-value="id"
                                        ></v-select>
                                    </v-row>

                                    <!-- type -->
                                    <v-row>
                                        <v-radio-group v-model="editForm.type" row>
                                            <v-radio label="One-way" value="One-way"></v-radio>
                                            <v-radio label="Return" value="Return"></v-radio>
                                        </v-radio-group>
                                    </v-row>
                                    <!-- marks -->
                                    <!-- <v-row>
                                      <v-text-field autocomplete="false" label="Marks" required v-model="editForm.marks"></v-text-field>
                                    </v-row>-->
                                    <!-- job_id -->
                                    <v-row>
                                        <v-text-field
                                            autocomplete="false"
                                            label="Job ID"
                                            required
                                            v-model="editForm.job_id"
                                        ></v-text-field>
                                    </v-row>
                                    <!-- badge_id -->
                                    <v-row>
                                        <v-text-field
                                            autocomplete="false"
                                            label="Badge ID"
                                            required
                                            v-model="editForm.badge_id"
                                        ></v-text-field>
                                    </v-row>

                                    <!-- date -->
                                    <v-row>
                                        <v-col class="mx-0 px-0" cols="12">
                                            <v-menu
                                                ref="menu"
                                                v-model="menu"
                                                :close-on-content-click="false"
                                                :return-value.sync="editForm.date"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="290px"
                                            >
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field
                                                        v-model="editForm.date"
                                                        label="Date"
                                                        prepend-icon="event"
                                                        readonly
                                                        v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker v-model="editForm.date" no-title scrollable>
                                                    <v-spacer></v-spacer>
                                                    <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                                                    <v-btn text color="primary" @click="$refs.menu.save(editForm.date)">
                                                        OK
                                                    </v-btn>
                                                </v-date-picker>
                                            </v-menu>
                                        </v-col>
                                    </v-row>
                                    <!-- note -->
                                    <v-row>
                                        <v-textarea
                                            class
                                            outlined
                                            shaped
                                            label="Leave a Note Here .."
                                            type="textarea"
                                            v-model="editForm.note"
                                        ></v-textarea>
                                    </v-row>
                                    <!-- Comment  -->

                                    <v-row>
                                        <v-btn color="info" class="mt-2" tile block @click="editRotation()">Update
                                        </v-btn>
                                    </v-row>
                                </v-container>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <!-- end edit dialog -->

                <!-- header -->
                <div class="col-12">
                    <v-btn color="grey" text tile class="float-left" :to="rotationsManagerRoute">
                        <v-icon class="mr-2">keyboard_return</v-icon>
                        Rotations Manager
                    </v-btn>
                </div>
                <div class="wrapper_header mt-10">
                    <div class="title_header text-uppercase">{{currentVehicleName}} History</div>
                    <v-btn
                        link
                        tile
                        class="ma-2 button_header"
                        outlined
                        color="success "
                        @click="openAddDialog()"
                    >
                        <v-icon left>mdi-pencil</v-icon>
                        <div>New Rotation</div>
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
                            <th scope="col"></th>
                            <th scope="col" @click="sort('driver')" style="cursor:ns-resize	">Driver</th>
                            <th scope="col"></th>
                            <th scope="col" @click="sort('vehicle')" style="cursor:ns-resize	">Vehicle</th>
                            <th scope="col" @click="sort('job_id')" style="cursor:ns-resize	">Job ID</th>
                            <th
                                scope="col"
                                @click="sort('rotationsCategory')"
                                style="cursor:ns-resize	"
                            >Rotation type
                            </th>
                            <th scope="col" @click="sort('city')" style="cursor:ns-resize	">City</th>
                            <th scope="col" @click="sort('type')" style="cursor:ns-resize	">Type</th>
                            <th scope="col" @click="sort('marks')" style="cursor:ns-resize	">Marks</th>
                            <th scope="col" @click="sort('date')" style="cursor:ns-resize	">Date</th>
                            <th scope="col" @click="sort('note')">Note</th>
                            <th scope="col"></th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="(rotation,i) in sortedRows" :key="i">
                            <td>
                                <img
                                    v-if="rotation.driverImageUrl"
                                    :src="BASE_URL()+'/storage/'+rotation.driverImageUrl"
                                    :alt="rotation.driver"
                                    class="w-100"
                                    style="cursor:pointer; "
                                    @click="showDriver(rotation.badge_id)"
                                />
                                <img
                                    v-else
                                    :src="BASE_URL()+'/storage/uploads/IMAGES/man.png'"
                                    :alt="rotation.driver"
                                    class="w-100"
                                    style="cursor:pointer;"
                                    @click="showDriver(rotation.badge_id)"
                                />
                            </td>

                            <td
                                colspan="2"
                                class="align-middle"
                                @click="showDriver(rotation.badge_id)"
                                style="cursor:pointer; color:#1e3799;"
                            >
                                <v-tooltip bottom v-if="rotation.driver">
                                    <template v-slot:activator="{ on }">
                                        <v-btn outlined v-on="on">{{rotation.driver}}</v-btn>
                                    </template>
                                    <span>See Driver's Information</span>
                                </v-tooltip>
                                <v-tooltip bottom v-else>
                                    <template v-slot:activator="{ on }">
                                        <v-btn outlined v-on="on">{{rotation.badge_id}}</v-btn>
                                    </template>
                                    <span>This Driver doesnt exist try another badge ID!</span>
                                </v-tooltip>
                            </td>
                            <td class="text-uppercase align-middle" style="color:#01a3a4">{{rotation.vehicle}}</td>
                            <td class="align-middle" style="color:#3c6382;">{{rotation.job_id}}</td>
                            <td class="align-middle" style="color:#3c6382;">{{rotation.rotationsCategory}}</td>
                            <td class="align-middle" style="color:#3c6382;">{{rotation.city}}</td>
                            <td class="align-middle" style="color:#3c6382;">{{rotation.type}}</td>
                            <td class="align-middle" style="color:#3c6382;">{{rotation.marks}}</td>
                            <td class="align-middle" style="color:#3c6382;">{{rotation.date}}</td>
                            <td colspan="2" class="font-italic align-middle" style="color:grey;">{{rotation.note}}</td>
                            <td class="text-right align-middle">
                                <v-icon color="green" class="mx-1" @click="openEditDialog(rotation)">edit</v-icon>
                                <v-icon color="red" class="mx-1" @click="deleteRotation(rotation)">delete</v-icon>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <v-pagination v-model="currentPage" :length="nb_pages"></v-pagination>
                    </div>
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
                    <v-dialog v-model="addDialogIsOpen" max-width="800px" persistent>
                        <v-card v-if="addDialogIsOpen">
                            <v-container>
                                <v-btn text color="black" class="mt-4" block @click="closeAddDialog()">
                                    <v-icon>close</v-icon>
                                </v-btn>
                                <v-row class="col-12 justify-content-around align-content-center mx-0 px-0">
                                    <div
                                        class="text-uppercase font-weight-black mx-8"
                                        style="font-size: 2em;
    color: #2196f3;"
                                    >New Rotation
                                    </div>


                                </v-row>
                            </v-container>
                            <v-card-text>
                                <v-container>
                                    <v-container>
                                        <!-- rotation type -->
                                        <v-row class="mt-12">
                                            <v-select
                                                autocomplete="false"
                                                label="Rotation Type"
                                                required
                                                v-model="addForm.rotation_type_id"
                                                :items="rotationsCategories"
                                                item-text="name"
                                                item-value="id"
                                            ></v-select>
                                        </v-row>
                                        <!-- vehicle -->
                                        <v-row>
                                            <v-select
                                                autocomplete="false"
                                                label="Vehicle"
                                                required
                                                v-model="addForm.vehicle_id"
                                                :items="vehicles"
                                                item-text="name"
                                                item-value="id"
                                            ></v-select>
                                        </v-row>
                                        <!-- city -->
                                        <v-row>
                                            <v-select
                                                autocomplete="false"
                                                label="City"
                                                required
                                                v-model="addForm.city_id"
                                                :items="cities"
                                                item-text="name"
                                                item-value="id"
                                            ></v-select>
                                        </v-row>

                                        <!-- type -->
                                        <v-row>
                                            <v-radio-group v-model="addForm.type" row>
                                                <v-radio label="One-way" value="One-way"></v-radio>
                                                <v-radio label="Return" value="Return"></v-radio>
                                            </v-radio-group>
                                        </v-row>
                                        <!-- marks -->
                                        <v-row>
                                            <v-text-field autocomplete="false" label="Marks" required
                                                          v-model="addForm.marks"></v-text-field>
                                        </v-row>
                                        <!-- job_id -->
                                        <v-row>
                                            <v-text-field autocomplete="false" label="Job ID" required
                                                          v-model="addForm.job_id"></v-text-field>
                                        </v-row>
                                        <!-- badge_id -->
                                        <v-row>
                                            <v-text-field
                                                autocomplete="false"
                                                label="Badge ID"
                                                required
                                                v-model="addForm.badge_id"
                                            ></v-text-field>
                                        </v-row>

                                        <!-- date -->
                                        <v-row>
                                            <v-col class="mx-0 px-0" cols="12">
                                                <v-menu
                                                    ref="menu"
                                                    v-model="menu"
                                                    :close-on-content-click="false"
                                                    :return-value.sync="addForm.date"
                                                    transition="scale-transition"
                                                    offset-y
                                                    min-width="290px"
                                                >
                                                    <template v-slot:activator="{ on }">
                                                        <v-text-field
                                                            v-model="addForm.date"
                                                            label="Date"
                                                            prepend-icon="event"
                                                            readonly
                                                            v-on="on"
                                                        ></v-text-field>
                                                    </template>
                                                    <v-date-picker v-model="addForm.date" no-title scrollable>
                                                        <v-spacer></v-spacer>
                                                        <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                                                        <v-btn text color="primary"
                                                               @click="$refs.menu.save(addForm.date)">OK
                                                        </v-btn>
                                                    </v-date-picker>
                                                </v-menu>
                                            </v-col>
                                        </v-row>
                                        <!-- note -->
                                        <v-row>
                                            <v-textarea
                                                class
                                                outlined
                                                shaped
                                                label="Leave a Note Here .."
                                                type="textarea"
                                                v-model="addForm.note"
                                            ></v-textarea>
                                        </v-row>
                                        <!-- Comment  -->

                                        <v-row>
                                            <v-btn color="info" class="" tile block @click="addRotation()">Add
                                            </v-btn>
                                        </v-row>

                                        <v-row>
                                            <v-btn color="grey text-white" class="mt-2" tile block @click="closeAddDialog()">Cancel
                                            </v-btn>
                                        </v-row>
                                    </v-container>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                    <!-- end add dialog -->

                    <!-- start edit dialog -->
                    <v-dialog v-model="editDialogIsOpen" max-width="800px" persistent>
                        <v-card v-if="editDialogIsOpen">
                            <v-container>
                                <v-btn text color="black" class="" block @click="closeEditDialog()">
                                    <v-icon>close</v-icon>
                                </v-btn>
                                <v-row class="col-12 justify-content-around align-content-center mx-0 px-0 ">
                                    <div
                                        class="text-uppercase font-weight-black mx-8"
                                        style="font-size: 2em;
    color: #2196f3;"
                                    >Edit Rotation
                                    </div>

                                </v-row>
                            </v-container>
                            <v-card-text>
                                <v-container>
                                    <v-container>
                                        <!-- rotation category -->
                                        <!-- <v-row class="mt-12">
                                          <v-select
                                            autocomplete="false"
                                            label="Rotation Type"
                                            required
                                            v-model="editForm.rotation_type_id"
                                            :items="rotationsCategories"
                                            item-text="name"
                                            item-value="id"
                                          ></v-select>
                                        </v-row>-->
                                        <!-- vehicle -->
                                        <!-- <v-row>
                                          <v-select
                                            autocomplete="false"
                                            label="Vehicle"
                                            required
                                            v-model="editForm.vehicle_id"
                                            :items="vehicles"
                                            item-text="name"
                                            item-value="id"
                                          ></v-select>
                                        </v-row>-->
                                        <!-- city -->
                                        <v-row>
                                            <v-select
                                                autocomplete="false"
                                                label="City"
                                                required
                                                v-model="editForm.city_id"
                                                :items="cities"
                                                item-text="name"
                                                item-value="id"
                                            ></v-select>
                                        </v-row>

                                        <!-- type -->
                                        <v-row>
                                            <v-radio-group v-model="editForm.type" row>
                                                <v-radio label="One-way" value="One-way"></v-radio>
                                                <v-radio label="Return" value="Return"></v-radio>
                                            </v-radio-group>
                                        </v-row>
                                        <!-- marks -->
                                        <!-- <v-row>
                                          <v-text-field autocomplete="false" label="Marks" required v-model="editForm.marks"></v-text-field>
                                        </v-row>-->
                                        <!-- job_id -->
                                        <v-row>
                                            <v-text-field
                                                autocomplete="false"
                                                label="Job ID"
                                                required
                                                v-model="editForm.job_id"
                                            ></v-text-field>
                                        </v-row>
                                        <!-- badge_id -->
                                        <v-row>
                                            <v-text-field
                                                autocomplete="false"
                                                label="Badge ID"
                                                required
                                                v-model="editForm.badge_id"
                                            ></v-text-field>
                                        </v-row>

                                        <!-- date -->
                                        <v-row>
                                            <v-col class="mx-0 px-0" cols="12">
                                                <v-menu
                                                    ref="menu"
                                                    v-model="menu"
                                                    :close-on-content-click="false"
                                                    :return-value.sync="editForm.date"
                                                    transition="scale-transition"
                                                    offset-y
                                                    min-width="290px"
                                                >
                                                    <template v-slot:activator="{ on }">
                                                        <v-text-field
                                                            v-model="editForm.date"
                                                            label="Date"
                                                            prepend-icon="event"
                                                            readonly
                                                            v-on="on"
                                                        ></v-text-field>
                                                    </template>
                                                    <v-date-picker v-model="editForm.date" no-title scrollable>
                                                        <v-spacer></v-spacer>
                                                        <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                                                        <v-btn text color="primary"
                                                               @click="$refs.menu.save(editForm.date)">OK
                                                        </v-btn>
                                                    </v-date-picker>
                                                </v-menu>
                                            </v-col>
                                        </v-row>
                                        <!-- note -->
                                        <v-row>
                                            <v-textarea
                                                class
                                                outlined
                                                shaped
                                                label="Leave a Note Here .."
                                                type="textarea"
                                                v-model="editForm.note"
                                            ></v-textarea>
                                        </v-row>
                                        <!-- Comment  -->

                                        <v-row>
                                            <v-btn color="info" class="mt-2" tile block @click="editRotation()">Update
                                            </v-btn>
                                        </v-row>
                                    </v-container>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                    <!-- end edit dialog -->

                    <!-- header -->
                    <v-btn color="grey" text tile block class="" :to="rotationsManagerRoute">
                        <v-icon class="mr-2">keyboard_return</v-icon>
                        Rotations Manager
                    </v-btn>
                    <hr>
                    <!--header-->
                    <div
                        class=" text-uppercase text-center"
                        style="font-size: 2em;color: rgb(124, 124, 124);"
                    >
                        {{currentVehicleName}} History
                    </div>

                    <v-btn
                        link
                        tile
                        class="my-4"
                        block
                        outlined
                        color="success "
                        @click="openAddDialog()"
                    >
                        <v-icon left>mdi-pencil</v-icon>
                        <div>New Rotation</div>
                    </v-btn>
                    <hr/>
                    <!-- progress -->
                    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                    <!-- table -->
                    <div class="table-responsive" v-if="!isLoading">
                        <table class="table table-hover table-striped ">
                            <thead class="thead-dark ">
                            <tr>
                                <th scope="col" class="fitCell align-middle"></th>
                                <th scope="col" class="fitCell align-middle" @click="sort('driver')"
                                    style="cursor:ns-resize	">Driver
                                </th>
                                <th scope="col" class="fitCell align-middle"></th>
                                <th scope="col" class="fitCell align-middle" @click="sort('vehicle')"
                                    style="cursor:ns-resize	">Vehicle
                                </th>
                                <th scope="col" class="fitCell align-middle" @click="sort('job_id')"
                                    style="cursor:ns-resize	">Job ID
                                </th>
                                <th
                                    scope="col"
                                    class="fitCell align-middle"
                                    @click="sort('rotationsCategory')"
                                    style="cursor:ns-resize	"
                                >Rotation type
                                </th>
                                <th scope="col" class="fitCell align-middle" @click="sort('city')"
                                    style="cursor:ns-resize	">City
                                </th>
                                <th scope="col" class="fitCell align-middle" @click="sort('type')"
                                    style="cursor:ns-resize	">Type
                                </th>
                                <th scope="col" class="fitCell align-middle" @click="sort('marks')"
                                    style="cursor:ns-resize	">Marks
                                </th>
                                <th scope="col" class="fitCell align-middle" @click="sort('date')"
                                    style="cursor:ns-resize	">Date
                                </th>
                                <th scope="col" class="fitCell align-middle" @click="sort('note')">Note</th>
                                <th scope="col"></th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="(rotation,i) in sortedRows" :key="i">
                                <td>
                                    <img
                                        v-if="rotation.driverImageUrl"
                                        :src="BASE_URL()+'/storage/'+rotation.driverImageUrl"
                                        :alt="rotation.driver"
                                        style="cursor:pointer; width: 100px"
                                        @click="showDriver(rotation.badge_id)"
                                    />
                                    <img
                                        v-else
                                        :src="BASE_URL()+'/storage/uploads/IMAGES/man.png'"
                                        :alt="rotation.driver"
                                        style="cursor:pointer; width: 100px"
                                        @click="showDriver(rotation.badge_id)"
                                    />
                                </td>

                                <td
                                    colspan="2"
                                    class="align-middle fitCell"
                                    @click="showDriver(rotation.badge_id)"
                                    style="cursor:pointer; color:#1e3799;"
                                >
                                    <v-tooltip bottom v-if="rotation.driver">
                                        <template v-slot:activator="{ on }">
                                            <v-btn outlined v-on="on">{{rotation.driver}}</v-btn>
                                        </template>
                                        <span>See Driver's Information</span>
                                    </v-tooltip>
                                    <v-tooltip bottom v-else>
                                        <template v-slot:activator="{ on }">
                                            <v-btn outlined v-on="on">{{rotation.badge_id}}</v-btn>
                                        </template>
                                        <span>This Driver doesnt exist try another badge ID!</span>
                                    </v-tooltip>
                                </td>
                                <td class="text-uppercase align-middle fitCell" style="color:#01a3a4">{{rotation.vehicle}}</td>
                                <td class="align-middle fitCell " style="color:#3c6382;">{{rotation.job_id}}</td>
                                <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.rotationsCategory}}</td>
                                <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.city}}</td>
                                <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.type}}</td>
                                <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.marks}}</td>
                                <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.date}}</td>
                                <td colspan="2" class="font-italic align-middle fitCell" style="color:grey;">{{rotation.note}}
                                </td>
                                <td class="text-right align-middle fitCell">
                                    <v-icon color="green" class="ma-2" @click="openEditDialog(rotation)">edit</v-icon>
                                    <v-icon color="red" class="ma-2" @click="deleteRotation(rotation)">delete</v-icon>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="text-center">
                            <v-pagination v-model="currentPage" :length="nb_pages"></v-pagination>
                        </div>
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
        computed: {
            sortedRows: function () {
                return this.rotations
                    .sort((a, b) => {
                        let modifier = 1;
                        if (this.currentSortDir === "desc") modifier = -1;
                        if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                        if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                        return 0;
                    })
                    .filter((row, index) => {
                        let start = (this.currentPage - 1) * this.pageSize;
                        let end = this.currentPage * this.pageSize;
                        if (index >= start && index < end) return true;
                    });
            },
            nb_pages() {
                return Math.round(this.rotations.length / this.pageSize);
            }
        },
        data() {
            return {
                cities: [],
                vehicles: [],
                currentVehicleName: "",
                currentVehicleId: "",
                rotationsCategories: [],
                rotations: [],
                noCityfound: false,
                addDialogIsOpen: false,
                editDialogIsOpen: false,
                editedId: "",
                addForm: {},
                editForm: {},
                cityName_editForm: "",
                cityMarks_editForm: "",
                isLoading: true,
                rotationsManagerRoute: {name: "DispatcherDashboard_rotationsManager"},
                menu: false,
                currentSortDir: "",
                pageSize: 10,
                currentPage: 1
            };
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            nextPage: function () {
                if (this.currentPage * this.pageSize < this.rotations.length)
                    this.currentPage++;
            },
            prevPage: function () {
                if (this.currentPage > 1) this.currentPage--;
            },
            sort: function (s) {
                //if s == current sort, reverse
                if (s === this.currentSort) {
                    this.currentSortDir = this.currentSortDir === "asc" ? "desc" : "asc";
                }
                this.currentSort = s;
            },
            fetchVehicles() {
                let url = this.BASE_URL() + "/api/dispatcher/vehicles";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;

                axios
                    .get(url)
                    .then(res => {
                        this.vehicles = res.data;
                        this.vehicles.forEach(vehicle => {
                            vehicle.name = vehicle.type + " " + vehicle.number;
                        });
                    })
                    .catch(error => {
                        this.$swal(
                            "Error in fetching Vehicles",
                            error.response.data.errors,
                            "warning"
                        );
                    });
            },
            fetchCities() {
                let url = this.BASE_URL() + "/api/dispatcher/cities";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .get(url)
                    .then(res => {
                        this.cities = res.data.data;
                    })
                    .catch(error => {
                        this.$swal(
                            "Error in fetching Cities",
                            error.response.data.errors,
                            "warning"
                        );
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
                    })
                    .catch(error => {
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            fetchRotations() {
                let url =
                    this.BASE_URL() +
                    "/api/dispatcher/rotationsByVehicleAndCategory/" +
                    this.$route.params.category_id + "/" + this.$route.params.vehicle_id;
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .get(url)
                    .then(res => {
                        this.rotations = res.data;
                        if (this.rotations.length == 0) {
                            this.$swal("No rotations found", "Sorry this car has 0 rotation in this category", "warning");
                            this.isLoading = false;
                            return;
                        }
                        this.currentVehicleName = this.rotations[0].vehicle;
                        this.currentVehicleId = this.rotations[0].vehicle_id;
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.log(error);
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            fetchItems() {
                this.fetchVehicles();
                this.fetchCities();
                this.fetchRotationsCategories();
                this.fetchRotations();
            },
            openAddDialog() {
                this.addDialogIsOpen = true;
                this.addForm.vehicle_id = this.currentVehicleId;
            },
            closeAddDialog() {
                this.addDialogIsOpen = false;
                Object.keys(this.addForm).forEach(key => (this.addForm[key] = null));
            },
            openEditDialog(rotation) {
                this.editForm = Object.assign({}, rotation);
                this.editDialogIsOpen = true;
            },
            closeEditDialog() {
                this.editDialogIsOpen = false;
            },
            addRotation() {
                let url = this.BASE_URL() + "/api/dispatcher/rotation";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .post(url, this.addForm)
                    .then(res => {
                        this.$swal({
                            title: "Success",
                            text: "Rotation added successfully!",
                            type: "success",
                            timer: 2000
                        });
                        this.fetchItems();
                        this.closeAddDialog();
                    })
                    .catch(error => {
                        this.pageIsLoading = false;
                        let output = "<br><br>";
                        for (let property in error.response.data.errors) {
                            output +=
                                '<p align="left">' +
                                "*" +
                                error.response.data.errors[property] +
                                "</p>";
                        }

                        this.$swal(error.response.data.message, output, "warning");
                    });
            },
            editRotation() {
                let url =
                    this.BASE_URL() + "/api/dispatcher/rotation/" + this.editForm.id;

                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                this.editForm._method = "put";
                axios
                    .post(url, this.editForm)
                    .then(res => {
                        this.$swal({
                            title: "Success",
                            text: "Rotation Type Updated successfully!",
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
            deleteRotation(rotation) {
                this.$swal
                    .fire({
                        text: "Are You Sure You Want To Delete This Rotation?",
                        type: "question",
                        animation: true,
                        focusConfirm: false,
                        padding: "2rem",
                        showCancelButton: true,
                        confirmButtonText: "Yes"
                    })
                    .then(res => {
                        if (res.value) {
                            let url =
                                this.BASE_URL() + "/api/dispatcher/rotation/" + rotation.id;
                            axios.defaults.headers.common["Authorization"] =
                                "Bearer " + this.$store.state.token_dispatcher;
                            axios
                                .delete(url)
                                .then(res => {
                                    this.fetchItems();
                                    this.$swal({
                                        title: "Success",
                                        text: "Rotation Deleted successfully!",
                                        type: "success",
                                        timer: 2000
                                    });
                                })
                                .catch(error => {
                                    this.$swal("Try again", error.response.data.errors, "warning");
                                });
                        }
                    });
            },
            showDriver: function (badge_id) {
                this.$router.push({
                    name: "DispatcherDashboard_drivers_show",
                    params: {id: badge_id}
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
