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
                                    <v-row class="mt-12 my-4 ">
                                        <v-select-search
                                            class="w-100"
                                            label="name"
                                            placeholder="Rotation Type"
                                            v-model="addForm.rotation_category_id"
                                            :options="rotationsCategories"
                                            :reduce="rotation_category => rotation_category.id"
                                        ></v-select-search>
                                    </v-row>
                                    <!-- vehicle -->
                                    <v-row class="my-8">
                                        <v-select-search
                                            class="w-100"
                                            label="name"
                                            placeholder="Vehicle"
                                            v-model="addForm.vehicle_id"
                                            :options="vehicles"
                                            :reduce="item => item.id"
                                        ></v-select-search>
                                    </v-row>
                                    <!-- city -->
                                    <v-row class="my-8">
                                        <v-select-search
                                            class="w-100"
                                            label="name"
                                            placeholder="City"
                                            v-model="addForm.city_id"
                                            :options="cities"
                                            :reduce="item => item.id"
                                        ></v-select-search>
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
                                        <v-btn v-if="!disableAddButton" color="info" class="mt-2" tile block
                                               @click="addRotation()">Add
                                        </v-btn>
                                        <v-btn v-if="disableAddButton" color="info" class="mt-2" tile block disabled>
                                            Adding ...
                                        </v-btn>
                                    </v-row>
                                    <v-row>
                                        <v-btn v-if="!disableAddButton" color="grey" class="mt-2 text-white" tile block
                                               @click="closeAddDialog()">Cancel
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

                                    <v-row class="my-8">
                                        <v-select-search
                                            class="w-100"
                                            label="name"
                                            placeholder="City"
                                            v-model="editForm.city_id"
                                            :options="cities"
                                            :reduce="item => item.id"
                                        ></v-select-search>
                                    </v-row>
                                    <!-- type -->
                                    <v-row>
                                        <v-radio-group v-model="editForm.type" row>
                                            <v-radio label="One-way" value="One-way"></v-radio>
                                            <v-radio label="Return" value="Return"></v-radio>
                                        </v-radio-group>
                                    </v-row>
                                    <!-- marks -->
                                    <v-row>
                                        <v-text-field autocomplete="false" label="Marks" required
                                                      v-model="editForm.marks"></v-text-field>
                                    </v-row>
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
                                        <v-btn v-if="!disableEditButton" color="info" class="mt-2" tile block
                                               @click="editRotation()">Update
                                        </v-btn>
                                        <v-btn v-if="disableEditButton" color="info" class="mt-2" disabled tile block>
                                            Updating ...
                                        </v-btn>
                                    </v-row>
                                    <v-row>
                                        <v-btn v-if="!disableEditButton" color="grey" class="mt-2 text-white" tile block
                                               @click="closeEditDialog()">Cancel
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
                    <div class="title_header text-uppercase">Rotations</div>
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


                <!--Start Content-->
                <div>
                    <div class="mb-6">


                        <!-- search start -->
                        <!-- {{-- collapse button --}} -->
                        <v-btn v-if="!showSearchOption"
                               block outlined tile class="my-4" color="info"
                               @click="showSearchOption = !showSearchOption">
                            <v-icon color="info darken-1" class="mx-3">arrow_drop_down</v-icon>
                            Show Search Panel
                        </v-btn>
                        <v-btn v-if="showSearchOption"
                               block outlined tile class="my-4" color="info"
                               @click="showSearchOption = !showSearchOption">
                            <v-icon color="info darken-1" class="mx-3">arrow_drop_up</v-icon>
                            Hide Search Panel
                        </v-btn>
                        <!-- {{-- end collapse button --}} -->

                        <transition mode="out-in">
                            <div class="shadow pa-4" v-if="showSearchOption ">
                                <div class="row col-12 justify-content-around">
                                    <!-- {{-- search by Date --}} -->
                                    <div class=" col-6">
                                        <div class="card bg-light">
                                            <div class="card-header">Search by Date</div>
                                            <div class="card-body text-center">
                                                <div class="input-group mb-4">
                                                    <input
                                                        type="date"
                                                        v-model="searchByDate"
                                                        max="3000-12-31"
                                                        min="1000-01-01"
                                                        class="form-control"
                                                        @input="search(searchByDate,'searchByDate')"
                                                    />
                                                    <!-- {{-- search button --}} -->
                                                    <div class="input-group-prepend">
                                                        <button
                                                            type="submit"
                                                            class="btn btn-dark"
                                                            @click.prevent="search(searchByDate,'searchByDate')"
                                                        >
                                                            <v-icon dark small>search</v-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- {{-- end search by Date --}} -->

                                    <!-- {{-- search by Job ID --}} -->
                                    <div class="col-6">

                                        <div class="card bg-light">
                                            <div class="card-header">Search by Job ID</div>
                                            <div class="card-body text-center">
                                                <form>
                                                    <div class="input-group mb-4">
                                                        <!-- {{-- search box --}} -->
                                                        <input
                                                            type="number"
                                                            v-model="searchByJobId"
                                                            placeholder="Search by Job ID"
                                                            aria-describedby="button-addon7"
                                                            class="form-control"
                                                            @input="search(searchByJobId,'searchByJobId')"
                                                        />

                                                        <!-- {{-- search button --}} -->
                                                        <div class="input-group-prepend">
                                                            <button
                                                                type="submit"
                                                                class="btn btn-dark"
                                                                @click.prevent="search(searchByJobId,'searchByJobId')"
                                                            >
                                                                <v-icon dark small>search</v-icon>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- {{-- end search by Job ID --}} -->
                                </div>
                                <!-- {{-- search by Badge ID --}} -->
                                <div class="row col-12 justify-content-around">
                                    <div class="col-6">

                                        <div class="card bg-light">
                                            <div class="card-header">Search by Badge ID</div>
                                            <div class="card-body text-center">
                                                <form>
                                                    <div class="input-group mb-4">
                                                        <!-- {{-- search box --}} -->
                                                        <input
                                                            type="number"
                                                            v-model="searchByBadgeId"
                                                            placeholder="Search by Badge ID"
                                                            aria-describedby="button-addon7"
                                                            class="form-control"
                                                            @input="search(searchByBadgeId,'searchByBadgeId')"
                                                        />

                                                        <!-- {{-- search button --}} -->
                                                        <div class="input-group-prepend">
                                                            <button
                                                                type="submit"
                                                                class="btn btn-dark"
                                                                @click.prevent="search(searchByBadgeId,'searchByBadgeId')"
                                                            >
                                                                <v-icon dark small>search</v-icon>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- {{-- end search by Badge Id --}} -->

                                    <!-- {{-- search by Vehicle Number--}} -->
                                    <div class="col-6">

                                        <div class="card bg-light">
                                            <div class="card-header">Search by Vehicle Number</div>
                                            <div class="card-body text-center">
                                                <form>
                                                    <div class="input-group mb-4">
                                                        <!-- {{-- search box --}} -->
                                                        <input
                                                            type="number"
                                                            v-model="searchByVehicleNumber"
                                                            placeholder="Search by Vehicle Number"
                                                            aria-describedby="button-addon7"
                                                            class="form-control"
                                                            @input="search(searchByVehicleNumber,'searchByVehicleNumber')"
                                                        />

                                                        <!-- {{-- search button --}} -->
                                                        <div class="input-group-prepend">
                                                            <button
                                                                type="submit"
                                                                class="btn btn-dark"
                                                                @click.prevent="search(searchByVehicleNumber,'searchByVehicleNumber')"
                                                            >
                                                                <v-icon dark small>search</v-icon>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- {{-- end search by Vehicle Number --}} -->
                                    <!--Clear button-->
                                    <div class="row col-12">

                                        <v-btn

                                            outlined
                                            elevation="0"
                                            block
                                            color=""
                                            @click="clearSearchFields"
                                        >
                                            <v-icon dark left>restore</v-icon>
                                            Clear All
                                        </v-btn>
                                    </div>
                                    <!--end clear button-->
                                </div>
                            </div>
                        </transition>

                        <!-- search end -->
                    </div>
                    <!--SELECT SEARCH-->
                    <div class="card  border-info mb-6 " style="border-radius: 0">
                        <div class="card-header text-white border-primary  text-center   text-uppercase"
                             style="font-size: 1.2em;background-color: #8fc2ec !important;">
                            Categories
                        </div>
                        <div class="card-body border-dark">
                            <v-select-search
                                @input="search(currentRotationCategory?currentRotationCategory.id:null,'searchByCategoryId')"
                                v-model="currentRotationCategory"
                                :options="rotationsCategories" label="name"
                                placeholder="Choose a Category"

                                style=" color: #1d68a7"
                                class="">
                                <template #selected-option="{ id,name }" class="">
                                    <div class="font-weight-bolder pa-0 ma-0">{{ name }}</div>
                                </template>
                            </v-select-search>
                        </div>
                    </div>
                    <!--End SELECT-->
                    <!--count rotations-->
                    <hr>
                    <div class="card mt-12" style="    border-width: 1.5px !important;
                    border-radius: 0;
    border-color: #3490dc !important;">
                        <div class="card-body text-center   font-weight-bold" style="font-size: 1.5em;">
                            <strong class="text-primary">{{rotationsTotalNumber}}</strong>
                            <span v-if="currentRotationCategory">{{currentRotationCategory.name}}</span>
                            <span v-else> Rotations</span>
                            <span v-if="searchByDate">, For Date : {{searchByDate}}</span>
                            <span v-if="searchByBadgeId">, For Driver ID : {{searchByBadgeId}}</span>
                            <span v-if="searchByVehicleNumber">, For Vehicle : {{searchByVehicleNumber}}</span>
                            <span v-if="searchByJobId">, For Job ID : {{searchByJobId}}</span>
                        </div>
                    </div>
                    <!--end count rotations-->
                    <!-- progress -->
                    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                    <!--End Progress-->
                    <!-- table -->
                    <div class="table-responsive shadow-sm pa-4" v-if="!isLoading">
                        <table class="table table-hover table-striped" style="table-layout:fixed">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col"></th>
                                <th scope="col" @click="sort('driver')" style="cursor:ns-resize	">Driver</th>
                                <th scope="col"></th>
                                <th scope="col" @click="sort('vehicle')" style="cursor:ns-resize	">Vehicle</th>
                                <th scope="col" @click="sort('job_id')" style="cursor:ns-resize	">Job ID</th>
                                <th scope="col" @click="sort('rotationsCategory')" style="cursor:ns-resize	">Rotation
                                    type
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
                                <td colspan="2" class="font-italic align-middle" style="color:grey;">{{rotation.note}}
                                </td>
                                <td class="text-right align-middle">
                                    <v-icon color="green" class="mx-1" @click="openEditDialog(rotation)">edit</v-icon>
                                    <v-icon color="red" class="mx-1" @click="deleteRotation(rotation)">delete</v-icon>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-6">
                        <v-pagination :total-visible="5" v-model="current_page" :length="last_page"
                                      @input="next"></v-pagination>
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
                                <v-btn text color="black" block @click="closeAddDialog()">
                                    <v-icon>close</v-icon>
                                </v-btn>
                                <v-row class="col-12 justify-content-around align-content-center mx-0 px-0">
                                    <div
                                        class="text-uppercase font-weight-black mx-8"
                                        style="font-size: 1.4em;
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
                                                v-model="addForm.rotation_category_id"
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
                                            <v-btn color="info" class="mt-2" tile block @click="addRotation()">Add
                                            </v-btn>
                                        </v-row>
                                        <v-row>
                                            <v-btn color="grey text-white" class="mt-2" tile block
                                                   @click="closeAddDialog()">cancel
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
                                <v-row class="col-12 justify-content-around align-content-center mx-0 px-0">
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
                                        </v-row> -->
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
                                        <v-row>
                                            <v-btn color="grey text-white" class="mt-2" tile block
                                                   @click="closeEditDialog()">Cancel
                                            </v-btn>
                                        </v-row>
                                    </v-container>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                    <!-- end edit dialog -->

                    <!-- header -->
                    <div class="">
                        <v-btn color="grey" text tile class="" block :to="rotationsManagerRoute">
                            <v-icon class="mr-2">keyboard_return</v-icon>
                            Rotations Manager
                        </v-btn>
                    </div>
                    <hr>

                    <div
                        class=" text-uppercase text-center"
                        style="font-size: 2em;color: rgb(124, 124, 124);"
                    >
                        Rotations
                    </div>

                    <v-btn
                        block
                        link
                        tile
                        class="my-4"
                        outlined
                        color="success "
                        @click="openAddDialog()"
                    >
                        <v-icon left>mdi-pencil</v-icon>
                        <div>New Rotation</div>
                    </v-btn>
                    <hr/>


                    <!-- search start -->
                    <!-- {{-- collapse button --}} -->
                    <v-btn block outlined tile class="my-4" color="info"
                           @click="showSearchOption = !showSearchOption">
                        <v-icon color="info darken-1" class="mx-3">arrow_drop_down</v-icon>
                        Search options
                    </v-btn>
                    <!-- {{-- end collapse button --}} -->

                    <transition mode="out-in">
                        <div class="" v-if="showSearchOption">
                            <!-- {{-- search by Date --}} -->
                            <div class="card bg-light  mt-4">
                                <div class="card-header">Search by Date</div>
                                <div class="card-body text-center">
                                    <div class="input-group mb-4">
                                        <input
                                            type="date"
                                            v-model="searchByDate"
                                            max="3000-12-31"
                                            min="1000-01-01"
                                            class="form-control"
                                            @input="search(searchByDate,'searchByDate')"
                                        />
                                        <!-- {{-- search button --}} -->
                                        <div class="input-group-prepend">
                                            <button
                                                type="submit"
                                                class="btn btn-dark"
                                                @click.prevent="search(searchByDate,'searchByDate')"
                                            >
                                                <v-icon dark small>search</v-icon>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- {{-- end search by Date --}} -->

                            <!-- {{-- search by Job ID --}} -->

                            <div class="card bg-light  mt-4">
                                <div class="card-header">Search by Job ID</div>
                                <div class="card-body text-center">
                                    <form>
                                        <div class="input-group mb-4">
                                            <!-- {{-- search box --}} -->
                                            <input
                                                type="number"
                                                v-model="searchByJobId"
                                                placeholder="Search by Job ID"
                                                aria-describedby="button-addon7"
                                                class="form-control"
                                                @input="search(searchByJobId,'searchByJobId')"
                                            />

                                            <!-- {{-- search button --}} -->
                                            <div class="input-group-prepend">
                                                <button
                                                    type="submit"
                                                    class="btn btn-dark"
                                                    @click.prevent="search(searchByJobId,'searchByJobId')"
                                                >
                                                    <v-icon dark small>search</v-icon>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- {{-- end search by Job ID --}} -->
                            <!-- {{-- search by Badge ID --}} -->

                            <div class="card bg-light  mt-4">
                                <div class="card-header">Search by Badge ID</div>
                                <div class="card-body text-center">
                                    <form>
                                        <div class="input-group mb-4">
                                            <!-- {{-- search box --}} -->
                                            <input
                                                type="number"
                                                v-model="searchByBadgeId"
                                                placeholder="Search by Badge ID"
                                                aria-describedby="button-addon7"
                                                class="form-control"
                                                @input="search(searchByBadgeId,'searchByBadgeId')"
                                            />

                                            <!-- {{-- search button --}} -->
                                            <div class="input-group-prepend">
                                                <button
                                                    type="submit"
                                                    class="btn btn-dark"
                                                    @click.prevent="search(searchByBadgeId,'searchByBadgeId')"
                                                >
                                                    <v-icon dark small>search</v-icon>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- {{-- end search by Badge Id --}} -->

                            <!-- {{-- search by Vehicle Number--}} -->

                            <div class="card bg-light mt-4">
                                <div class="card-header">Search by Vehicle Number</div>
                                <div class="card-body text-center">
                                    <form>
                                        <div class="input-group mb-4">
                                            <!-- {{-- search box --}} -->
                                            <input
                                                type="number"
                                                v-model="searchByVehicleNumber"
                                                placeholder="Search by Vehicle Number"
                                                aria-describedby="button-addon7"
                                                class="form-control"
                                                @input="search(searchByVehicleNumber,'searchByVehicleNumber')"
                                            />

                                            <!-- {{-- search button --}} -->
                                            <div class="input-group-prepend">
                                                <button
                                                    type="submit"
                                                    class="btn btn-dark"
                                                    @click.prevent="search(searchByVehicleNumber,'searchByVehicleNumber')"
                                                >
                                                    <v-icon dark small>search</v-icon>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- {{-- end search by Vehicle Number --}} -->
                            <!--Clear button-->
                            <v-btn
                                block
                                color="info"
                                @click="clearSearchFields"
                                class="my-4"
                            >
                                <v-icon dark left>restore</v-icon>
                                Clear All
                            </v-btn>
                        </div>

                        <!--end clear button-->
                    </transition>

                    <!-- search end -->
                    <!--SELECT SEARCH-->
                    <div class="sentence text-center mb-6">
                        Pick a category !
                    </div>
                    <v-select-search
                        @input="search(currentRotationCategory?currentRotationCategory.id:null,'searchByCategoryId')"
                        v-model="currentRotationCategory"
                        :options="rotationsCategories" label="name"
                        style="font-size: 1.3em !important; color: #1d68a7"
                        class="shadow my-4">
                        <template #selected-option="{ id,name }" class="">
                            <div class="font-weight-bolder pa-0 ma-0">{{ name }}</div>
                        </template>
                    </v-select-search>
                </div>
                <!--End SELECT-->
                <!--count rotations-->
                <hr>
                <div class="card my-12" style="    border-width: 1.5px !important;
                    border-radius: 0;
    border-color: #3490dc !important;">
                    <div class="card-body text-center   font-weight-bold" style="font-size: 1.5em;">
                        <strong class="text-primary">{{rotationsTotalNumber}}</strong>
                        <span v-if="currentRotationCategory">{{currentRotationCategory.name}}</span>
                        <span v-else> Rotations</span>
                        <span v-if="searchByDate">, For Date : {{searchByDate}}</span>
                        <span v-if="searchByBadgeId">, For Driver ID : {{searchByBadgeId}}</span>
                        <span v-if="searchByVehicleNumber">, For Vehicle : {{searchByVehicleNumber}}</span>
                        <span v-if="searchByJobId">, For Job ID : {{searchByJobId}}</span>
                    </div>
                </div>
                <!--end count rotations-->
                <!-- progress -->
                <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                <!-- table -->
                <div class="table-responsive" v-if="!isLoading">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="align-middle fitCell"></th>
                            <th scope="col" class="align-middle fitCell" @click="sort('driver')"
                                style="cursor:ns-resize	">Driver
                            </th>
                            <th scope="col" class="align-middle fitCell"></th>
                            <th scope="col" class="align-middle fitCell" @click="sort('vehicle')"
                                style="cursor:ns-resize	">Vehicle
                            </th>
                            <th scope="col" class="align-middle fitCell" @click="sort('job_id')"
                                style="cursor:ns-resize	">Job ID
                            </th>
                            <th scope="col" class="align-middle fitCell" @click="sort('rotationsCategory')"
                                style="cursor:ns-resize	">Rotation type
                            </th>
                            <th scope="col" class="align-middle fitCell" @click="sort('city')"
                                style="cursor:ns-resize	">City
                            </th>
                            <th scope="col" class="align-middle fitCell" @click="sort('type')"
                                style="cursor:ns-resize	">Type
                            </th>
                            <th scope="col" class="align-middle fitCell" @click="sort('marks')"
                                style="cursor:ns-resize	">Marks
                            </th>
                            <th scope="col" class="align-middle fitCell" @click="sort('date')"
                                style="cursor:ns-resize	">Date
                            </th>
                            <th scope="col" class="align-middle fitCell" @click="sort('note')">Note</th>
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
                                    class=""
                                    style="cursor:pointer; width: 100px "
                                    @click="showDriver(rotation.badge_id)"
                                />
                                <img
                                    v-else
                                    :src="BASE_URL()+'/storage/uploads/IMAGES/man.png'"
                                    :alt="rotation.driver"
                                    class=""
                                    style="cursor:pointer;width: 100px"
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
                            <td class="text-uppercase align-middle fitCell" style="color:#01a3a4">
                                {{rotation.vehicle}}
                            </td>
                            <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.job_id}}</td>
                            <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.rotationsCategory}}
                            </td>
                            <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.city}}</td>
                            <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.type}}</td>
                            <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.marks}}</td>
                            <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.date}}</td>
                            <td colspan="2" class="font-italic align-middle fitCell" style="color:grey;">
                                {{rotation.note}}
                            </td>
                            <td class="text-right align-middle">
                                <v-icon color="green" class="ma-2" @click="openEditDialog(rotation)">edit</v-icon>
                                <v-icon color="red" class="ma-2" @click="deleteRotation(rotation)">delete</v-icon>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <v-pagination :total-visible="5" v-model="current_page" :length="last_page"
                                      @input="next"></v-pagination>
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
                    });
            },
        },
        data() {
            return {
                current_page: 1,
                last_page: 1,
                pageSize: 0,
                cities: [],
                vehicles: [],
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
                disableAddButton: false,
                disableEditButton: false,
                rotationsManagerRoute: {name: "DispatcherDashboard_rotationsManager"},
                menu: false,
                currentSortDir: "",
                currentRotationCategory: null,
                //search inputs
                showSearchOption: false,
                searchByDate: null,
                searchByJobId: null,
                searchByBadgeId: null,
                searchByVehicleNumber: null,
                searchQuery: null,
                searchQueryArray: new Map(),
                rotationsTotalNumber: 0,
            };
        },
        methods: {

            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            makePagination(meta) {
                this.last_page = meta.last_page;
                this.current_page = meta.current_page;
                this.pageSize = meta.pageSize;
            },
            next(page) {
                this.fetchRotations(page);
            },
            sort: function (s) {
                //if s == current sort, reverse
                if (s === this.currentSort) {
                    this.currentSortDir = this.currentSortDir === "asc" ? "desc" : "asc";
                }
                this.currentSort = s;
            },
            compare(a, b) {
                if (parseInt(a.number, 10) < parseInt(b.number, 10)) {
                    return -1;
                }
                if (parseInt(a.number, 10) > parseInt(b.number, 10)) {
                    return 1;
                }
                return 0;
            },
            fetchVehicles() {
                let url = this.BASE_URL() + "/api/dispatcher/vehicles";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;

                axios
                    .get(url)
                    .then(res => {
                        this.vehicles = res.data;
                        this.vehicles.sort(this.compare);
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
            fetchRotations(page) {

                this.isLoading = true;
                let vm = this;
                let url = this.BASE_URL() + "/api/dispatcher/rotations?";
                if (this.searchQuery) {
                    url = url + this.searchQuery;
                }
                if (page) {
                    url = url + "&page=" + page;
                }


                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .get(url)
                    .then(res => {
                        res = res.data;
                        this.rotations = res.data;
                        if (this.rotations.length == 0) {
                            // this.searchQuery = null;
                            this.$swal("No Rotations Found!", "", "warning");
                            this.isLoading = false;
                            this.rotationsTotalNumber = 0;
                            return
                        }
                        let meta = {};
                        meta.current_page = res.current_page
                        meta.last_page = res.last_page;
                        meta.pageSize = res.per_page;
                        this.rotationsTotalNumber = res.total;
                        vm.makePagination(meta)
                        this.isLoading = false;
                    })
                    .catch(error => {
                        this.isLoading = false;
                        this.searchQuery = null;
                        console.log("error: " + error);
                        this.$swal("Something went wrong! ", "try again ", "warning");
                    });
            },

            fetchItems() {
                this.fetchVehicles();
                this.fetchCities();
                this.fetchRotationsCategories();
                this.fetchRotations();
            },
            search(searchValue, type) {
                if (searchValue == null) {
                    searchValue = "";
                    this.searchQueryArray.delete(type);
                    this.searchQuery = null;
                    for (let [key, value] of this.searchQueryArray) {
                        this.searchQuery = (this.searchQuery != null ? this.searchQuery : "") + "&" + key + "=" + value;
                    }
                } else {
                    this.searchQueryArray.delete(type);
                    this.searchQueryArray.set(type, searchValue);
                    this.searchQuery = null;
                    for (let [key, value] of this.searchQueryArray) {
                        this.searchQuery = (this.searchQuery != null ? this.searchQuery : "") + "&" + key + "=" + value;
                    }
                }
                this.fetchRotations();
                return;

            },
            clearSearchFields() {
                this.searchByDate = null;
                this.searchByJobId = null;
                this.searchByBadgeId = null;
                this.searchByVehicleNumber = null;
                this.searchQueryArray.delete("searchByDate");
                this.searchQueryArray.delete("searchByJobId");
                this.searchQueryArray.delete("searchByBadgeId");
                this.searchQueryArray.delete("searchByVehicleNumber");
                this.searchQuery = null;
                for (let [key, value] of this.searchQueryArray) {
                    this.searchQuery = (this.searchQuery != null ? this.searchQuery : "") + "&" + key + "=" + value;
                }
                this.fetchRotations();
            },
            openAddDialog() {
                this.addDialogIsOpen = true;
            },
            closeAddDialog() {
                this.addDialogIsOpen = false;
                Object.keys(this.addForm).forEach(key => (this.addForm[key] = null));
            },
            openEditDialog(rotation) {
                this.editForm = Object.assign({}, rotation);
                this.editForm.city_id= parseInt(this.editForm.city_id, 10);
                this.editDialogIsOpen = true;
            },
            closeEditDialog() {
                this.editDialogIsOpen = false;
            },
            addRotation() {
                this.disableAddButton = true;
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
                        this.disableAddButton = false;

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
                        this.disableAddButton = false;

                    });
            },
            editRotation() {
                this.disableEditButton = true;
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
                            text: "Rotation Updated successfully!",
                            type: "success",
                            timer: 2000
                        });
                        this.fetchItems();
                        this.closeEditDialog();
                        this.buttonLoading = false;
                        this.disableEditButton = false;

                    })
                    .catch(error => {
                        let output = "<br><br>";
                        for (let property in error.response.data.errors) {
                            output +=
                                '<p align="left">' +
                                "*" +
                                error.response.data.errors[property] +
                                "</p>";
                        }

                        this.$swal(error.response.data.message, output, "warning");
                        this.disableEditButton = false;

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

    .sentence {
        margin-top: 2em;
        font-family: "Quicksand", sans-serif;
        justify-self: center;
        font-size: 2.5em;
    }

</style>
