<template>
  <div>
    <!-- add dialog by job id -->
    <v-dialog v-model="addDialogByJobId" max-width="500px">
      <v-card>
        <v-card-text>
          <v-container>
            <div class="row float-right">
              <v-btn
                tile
                outlined
                color="black"
                class="float-right"
                @click="addDialogByJobId = false;"
              >
                <v-icon>close</v-icon>
              </v-btn>
            </div>
            <v-container>
              <v-row class="mt-12 mx-0 px-0">
                <v-text-field
                  class="mx-0 px-0"
                  label="Job ID"
                  required
                  v-model="jobIdToImport"
                  v-on:keyup.enter="importTrip(jobIdToImport)"
                ></v-text-field>
              </v-row>
              <v-row>
                <v-btn
                  color="info"
                  class
                  outlined
                  tile
                  block
                  @click="importTrip(jobIdToImport)"
                >Import</v-btn>
              </v-row>
            </v-container>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- create new trip dialog -->
    <v-dialog
      v-model="addDialogManually"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <!-- header -->
      <v-toolbar dark color="primary">
        <v-btn icon dark @click="addDialogManually = false;trip= {};">
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-toolbar-title>Create New Trip Manually</v-toolbar-title>
      </v-toolbar>
      <!-- body -->
      <v-card v-if="addDialogManually">
        <v-card-text>
          <v-container>
            <!-- form -->
            <div>
              <v-alert
                border="left"
                tile
                dense
                color="#1F7087"
                dark
                elevation="2"
                class="mt-12"
                align="center"
              >TRIP INFO</v-alert>

              <div class="form-group">
                <v-text-field
                  ref="add_job_id_ref"
                  v-on:keyup.enter="$refs.add_slip_id_ref.focus()"
                  label="JOB ID"
                  v-model="trip.job_id"
                  type="text"
                  name="job_id"
                ></v-text-field>
              </div>

              <v-select
                ref="add_envelope_id_ref"
                label="Envelope ID"
                :items="envelopes_ids"
                v-model="currentEnvelopeID"
              ></v-select>

              <!-- slip ID -->
              <div class="form-group">
                <v-text-field
                  ref="add_slip_id_ref"
                  v-on:keyup.enter="$refs.add_date_ref.focus()"
                  label="Slip ID"
                  v-model="currentSlipID"
                  type="text"
                  name="slip_id"
                ></v-text-field>
              </div>

              <!-- start date time of trip  -->
              <div class="row col-12 px-0 mx-0 align-center">
                <!-- date -->
                <v-menu
                  v-model="add_modal_dateDate"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template v-slot:activator="{  }">
                    <v-text-field
                      ref="add_date_ref"
                      v-on:keyup.enter="$refs.add_time_ref.focus()"
                      autocomplete="off"
                      v-model="trip.date"
                      label="Date (yyyy-mm-dd)"
                      prepend-icon="event"
                      @click="add_modal_dateDate=true;"
                    ></v-text-field>
                  </template>
                  <v-date-picker v-model="trip.date" @input="add_modal_dateDate = false;"></v-date-picker>
                </v-menu>

                <!-- time -->
                <v-col cols="6" class="pr-0 mr-0 float-right">
                  <v-menu
                    ref="menu"
                    v-model="modal_dateTime"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="trip.time"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="290px"
                  >
                    <template v-slot:activator="{  }">
                      <v-text-field
                        ref="add_time_ref"
                        v-on:keyup.enter="$refs.add_vehicle_ref.focus()"
                        v-model="trip.time"
                        label="Time (HH:MM)"
                        autocomplete="off"
                        prepend-icon="access_time"
                        @click="modal_dateTime=true;"
                      ></v-text-field>
                    </template>
                    <v-time-picker
                      v-if="modal_dateTime"
                      v-model="trip.time"
                      full-width
                      @click:minute="$refs.menu.save(trip.time)"
                    ></v-time-picker>
                  </v-menu>
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
              >DRIVER INFO</v-alert>

              <div class="form-group">
                <v-text-field
                  ref="add_vehicle_ref"
                  v-on:keyup.enter="$refs.add_badge_id_ref.focus()"
                  label="vehicle"
                  v-model="trip.vehicle"
                  type="text"
                  name="vehicle"
                ></v-text-field>
              </div>

              <div class="form-group">
                <v-text-field
                  ref="add_badge_id_ref"
                  v-on:keyup.enter="$refs.add_customer_name_ref.focus()"
                  label="Badge Id"
                  v-model="trip.badge_id"
                  type="text"
                  name="badge_id"
                ></v-text-field>
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
              >CUSTOMER INFO</v-alert>

              <div class="form-group">
                <v-text-field
                  ref="add_customer_name_ref"
                  v-on:keyup.enter="$refs.add_fare_ref.focus()"
                  label="CUSTOMER NAME"
                  v-model="trip.customer_name"
                  type="text"
                  name="customer_name"
                ></v-text-field>
              </div>

              <div class="form-group">
                <v-text-field
                  ref="add_fare_ref"
                  v-on:keyup.enter="$refs.add_tip_ref.focus()"
                  label="FARE (cent)"
                  v-model="trip.fare"
                  type="text"
                  name="fare"
                ></v-text-field>
              </div>

              <div class="form-group">
                <v-text-field
                  ref="add_tip_ref"
                  v-on:keyup.enter="$refs.add_account_ref.focus()"
                  label="TIP (cent)"
                  v-model="trip.tip"
                  type="text"
                  name="tip"
                ></v-text-field>
              </div>

              <div class="form-group">
                <v-text-field
                  ref="add_account_ref"
                  v-on:keyup.enter="$refs.add_po_ref.focus()"
                  label="ACCOUNT"
                  v-model="trip.account"
                  type="text"
                  name="account"
                ></v-text-field>
              </div>

              <div class="form-group">
                <v-text-field
                  ref="add_po_ref"
                  v-on:keyup.enter="$refs.add_pickup_ref.focus()"
                  label="PO"
                  v-model="trip.po"
                  type="text"
                  name="po"
                ></v-text-field>
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
              >TIME AND LOCALISATION INFO</v-alert>

              <div class="form-group">
                <v-text-field
                  ref="add_pickup_ref"
                  v-on:keyup.enter="$refs.add_dropoff_ref.focus()"
                  label="PICKUP ADDRESS"
                  v-model="trip.pickup"
                  type="text"
                  name="pickup"
                ></v-text-field>
              </div>

              <div class="form-group">
                <v-text-field
                  ref="add_dropoff_ref"
                  label="DROP OFF ADDRESS"
                  v-model="trip.dropoff"
                  type="text"
                  name="dropoff"
                ></v-text-field>
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
              >STATUS</v-alert>

              <div class="form-group text-center align-center">
                <v-radio-group v-model="trip.status" row>
                  <v-radio label="verified" value="verified"></v-radio>
                  <v-radio label="unverified" value="unverified"></v-radio>
                </v-radio-group>
              </div>
              <!-- submit btn -->
              <div class="row col-12 mt-4">
                <v-btn color="success" tile class="float-left" @click="storeTrip">submit</v-btn>
                <v-btn
                  color="grey lighten-1"
                  dark
                  tile
                  class="float-left mx-4"
                  @click="addDialogManually = false;trip= {};"
                >cancel</v-btn>
              </div>
            </div>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
    <!-- 
    -->
    <!-- 

    -->
    <!-- 




























    -->
    <!-- start of editing an account slip -->
    <v-dialog
      v-model="isOpenEditDialogComputed"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <!-- header -->
      <v-toolbar dark color="primary">
        <v-btn icon dark @click="isOpenEditDialogComputed = false;">
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-toolbar-title>Trip with Job ID : {{editedTrip.job_id}}</v-toolbar-title>
      </v-toolbar>
      <!-- body -->
      <v-card>
        <v-card-text>
          <v-container>
            <!-- form -->
            <div v-if="isOpenEditDialogComputed">
              <div id="firstRow" class="row col-12">
                <div id="tripInfo" class="col-6">
                  <v-alert
                    border="left"
                    tile
                    dense
                    color="#1F7087"
                    dark
                    elevation="2"
                    class="mt-12"
                    align="center"
                  >TRIP INFO</v-alert>

                  <!-- job id -->
                  <div class="form-group">
                    <v-text-field
                      ref="edit_job_id_ref"
                      v-on:keyup.enter="$refs.edit_envelopes_ref.focus()"
                      label="JOB ID"
                      v-model="editedTrip.job_id"
                      disable-lookup="false"
                      type="text"
                      name="job_id"
                    ></v-text-field>
                  </div>

                  <v-select
                    label="Envelope ID"
                    ref="edit_envelopes_ref"
                    :items="envelopes_ids"
                    v-model="envelope_id_selected"
                  ></v-select>

                  <!-- slip ID -->
                  <div class="form-group">
                    <v-text-field
                      ref="edit_slip_id_ref"
                      v-on:keyup.enter="$refs.edit_image_id_ref.focus()"
                      label="Slip ID*"
                      v-model="currentSlipID_edit"
                      type="text"
                      name="slip_id"
                    ></v-text-field>
                  </div>
                  <!-- Image id -->
                  <div class="form-group">
                    <v-text-field
                      ref="edit_image_id_ref"
                      v-on:keyup.enter="$refs.edit_date_ref.focus()"
                      label="Image ID (will be filled automatically )"
                      v-model="editedTrip.image_id"
                      type="text"
                    ></v-text-field>
                  </div>

                  <!-- start date time of trip  -->
                  <div class="row col-12 px-0 mx-0 align-center">
                    <!-- date -->
                    <v-menu
                      v-model="modal_dateDate"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      transition="scale-transition"
                      offset-y
                      min-width="290px"
                    >
                      <template v-slot:activator="{  }">
                        <v-text-field
                          ref="edit_date_ref"
                          v-on:keyup.enter="$refs.edit_time_ref.focus()"
                          v-model="editedTrip.date"
                          label="Date (yyyy-mm-dd)"
                          prepend-icon="event"
                          @click="modal_dateDate=true;"
                        ></v-text-field>
                      </template>
                      <v-date-picker v-model="editedTrip.date" @input="modal_dateDate = false;"></v-date-picker>
                    </v-menu>

                    <!-- time -->
                    <v-col cols="6" class="pr-0 mr-0 float-right">
                      <v-menu
                        ref="menu"
                        v-model="modal_dateTime_edit"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        :return-value.sync="editedTrip.time"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                      >
                        <template v-slot:activator="{  }">
                          <v-text-field
                            ref="edit_time_ref"
                            v-on:keyup.enter="$refs.edit_vehicle_ref.focus()"
                            v-model="editedTrip.time"
                            label="Time"
                            prepend-icon="access_time"
                            @click="modal_dateTime_edit = true;"
                          ></v-text-field>
                        </template>
                        <v-time-picker
                          v-if="modal_dateTime_edit"
                          v-model="editedTrip.time"
                          full-width
                          @click:minute="$refs.menu.save(editedTrip.time)"
                        ></v-time-picker>
                      </v-menu>
                    </v-col>
                  </div>
                </div>
                <div id="driverInfo" class="col-6">
                  <v-alert
                    border="left"
                    tile
                    dense
                    color="#1F7087"
                    dark
                    elevation="2"
                    class="mt-12"
                    align="center"
                  >DRIVER INFO</v-alert>

                  <div class="form-group">
                    <v-text-field
                      ref="edit_vehicle_ref"
                      v-on:keyup.enter="$refs.edit_badge_id_ref.focus()"
                      label="vehicle"
                      v-model="editedTrip.vehicle"
                      type="text"
                      name="vehicle"
                    ></v-text-field>
                  </div>

                  <div class="form-group">
                    <v-text-field
                      ref="edit_badge_id_ref"
                      v-on:keyup.enter="$refs.edit_pickup_ref.focus()"
                      label="Badge Id"
                      v-model="editedTrip.badge_id"
                      type="text"
                      name="badge_id"
                    ></v-text-field>
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
                  >PICKUP AND DROPOFF</v-alert>

                  <div class="form-group">
                    <v-text-field
                      ref="edit_pickup_ref"
                      v-on:keyup.enter="$refs.edit_dropoff_ref.focus()"
                      label="PICKUP ADDRESS"
                      v-model="editedTrip.pickup"
                      type="text"
                      name="pickup"
                    ></v-text-field>
                  </div>

                  <div class="form-group">
                    <v-text-field
                      v-on:keyup.enter="$refs.edit_customer_name_ref.focus()"
                      ref="edit_dropoff_ref"
                      label="DROP OFF ADDRESS"
                      v-model="editedTrip.dropoff"
                      type="text"
                      name="dropoff"
                    ></v-text-field>
                  </div>
                  <!-- end date time of editedTrip  -->
                </div>
              </div>

              <div id="secondRow" class="row col-12">
                <div id="customerInfo" class="col-6">
                  <v-alert
                    border="left"
                    tile
                    dense
                    color="#1F7087"
                    dark
                    elevation="2"
                    align="center"
                  >CUSTOMER INFO</v-alert>

                  <div class="form-group">
                    <v-text-field
                      ref="edit_customer_name_ref"
                      v-on:keyup.enter="$refs.edit_fare_ref.focus()"
                      label="CUSTOMER NAME"
                      v-model="editedTrip.customer_name"
                      type="text"
                      name="customer_name"
                    ></v-text-field>
                  </div>

                  <div class="form-group">
                    <v-text-field
                      ref="edit_fare_ref"
                      v-on:keyup.enter="$refs.edit_tip_ref.focus()"
                      label="FARE (cent)"
                      v-model="editedTrip.fare"
                      type="text"
                      name="fare"
                    ></v-text-field>
                  </div>

                  <div class="form-group">
                    <v-text-field
                      ref="edit_tip_ref"
                      v-on:keyup.enter="$refs.edit_account_ref.focus()"
                      label="TIP (cent)"
                      v-model="editedTrip.tip"
                      type="text"
                      name="tip"
                    ></v-text-field>
                  </div>

                  <div class="form-group">
                    <v-text-field
                      ref="edit_account_ref"
                      v-on:keyup.enter="$refs.edit_PO_ref.focus()"
                      label="ACCOUNT"
                      v-model="editedTrip.account"
                      type="text"
                      name="account"
                    ></v-text-field>
                  </div>

                  <div class="form-group">
                    <v-text-field
                      label="PO"
                      ref="edit_PO_ref"
                      v-model="editedTrip.PO"
                      type="text"
                      name="po"
                    ></v-text-field>
                  </div>
                </div>

                <div id="statusInfo" class="col-6">
                  <v-alert
                    border="left"
                    tile
                    dense
                    color="#1F7087"
                    dark
                    elevation="2"
                    align="center"
                  >STATUS</v-alert>
                  <!-- status -->
                  <div class="form-group text-center align-center">
                    <v-radio-group v-model="editedTrip.status" row>
                      <v-radio label="verified" value="verified"></v-radio>
                      <v-radio label="unverified" value="unverified"></v-radio>
                    </v-radio-group>
                  </div>
                </div>
                <!-- submit btn -->
                <div class="row col-12 mt-4">
                  <v-btn
                    color="success"
                    tile
                    class="float-left"
                    type="submit"
                    @click="editTrip();"
                  >submit</v-btn>
                  <v-btn
                    color="grey lighten-1"
                    dark
                    tile
                    class="float-left mx-4"
                    @click="isOpenEditDialogComputed = false;"
                  >cancel</v-btn>
                </div>
              </div>
            </div>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
    <!-- end of editing account slip -->
    <!-- edit dialog -->
    <v-dialog v-model="editDialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Edit row</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="4">
                <v-text-field v-model="edit_Envelope" label="Envelope ID"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field v-model="edit_Amount" label="Amount"></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="isOpenEditDialogComputed = false;">Cancel</v-btn>
          <v-btn color="blue darken-1" text @click="editTrip()">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <div class="row col-12 my-0 py-0"></div>

    <div class="row col-12 mt-2">
      <!-- search start -->
      <div v-if="!loading && !isClosed" class="col-md-12 md-form active-cyan-2 mb-4 mt-6">
        <form @submit.prevent="search(searchbyjobid)">
          <div class="input-group">
            <div class="input-group-prepend">
              <button type="submit" class="btn btn-dark">
                <v-icon dark small>search</v-icon>
              </button>
            </div>
            <input
              ref="searchInput"
              type="search"
              name="searchValue"
              v-model="searchbyjobid"
              placeholder="Search by job id"
              aria-describedby="button-addon7"
              class="form-control"
            />
          </div>
        </form>
      </div>
      <!-- search end -->

      <!-- start loading -->
      <div class="row col-12 text-center align-center">
        <v-progress-linear
          :active="loading"
          background-opacity="0.1"
          buffer-value="100"
          height="10"
          indeterminate
          rounded
          value="0"
          color="light-blue"
        ></v-progress-linear>
        <!-- end of loading -->
      </div>

      <!-- summary start -->
      <div v-if="!loading" class="row col-4 mx-auto">
        <div class="mx-auto ma-4">
          <table class="table table-primary table-bordered">
            <thead></thead>
            <tbody>
              <tr class="align-center text-center">
                <th colspan="2" class="align-center mx-auto">{{summary.mdt_count}} Account slips</th>
              </tr>
              <tr>
                <th>Verified Totals</th>
                <th>${{parseFloat(summary.mdt_verified ).toFixed(2) }}</th>
              </tr>
              <tr>
                <th>Claimed Totals</th>
                <th>${{parseFloat(summary.mdt_claimed ).toFixed(2) }}</th>
              </tr>

              <tr
                v-if="parseFloat(summary.mdt_verified -summary.mdt_claimed).toFixed(2)==0"
                class="table-success"
              >
                <th>Difference</th>
                <th>$0</th>
              </tr>
              <tr v-else class="table-warning">
                <th>Difference</th>
                <th>${{ parseFloat(summary.mdt_verified -summary.mdt_claimed).toFixed(2)}}</th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- summary end -->
      <div v-if="!loading " class="table-responsive">
        <div v-if="!isClosed" class="row col-12 text-center align-center">
          <div class="col-6">
            <v-btn color="info" class block @click="trip.tip=0;addDialogManually=true;">
              <v-icon class="ml-0 pl-0 mr-2 my-0">mdi-pencil-plus-outline</v-icon>Create New Trip
            </v-btn>
          </div>
          <div class="col-6">
            <v-btn color="info" class block @click="trip.tip=0;addDialogByJobId=true;">
              <v-icon class="ml-0 pl-0 mr-2 my-0">add_circle_outline</v-icon>Import Trip
            </v-btn>
          </div>
        </div>
        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
            <tr style="cursor: pointer;">
              <th class="text-center" @click="sort('status')" scope="col">STATUS</th>
              <th class="text-center" @click="sort('job_id')" scope="col">JOBID</th>
              <th class="text-center" @click="sort('envelope_id')" scope="col">EnvelopeIDS</th>
              <th class="text-center" @click="sort('slip_id')" scope="col">SLIPIDS</th>
              <th class="text-center" @click="sort('date')" scope="col">DATE</th>
              <th class="text-center" @click="sort('time')" scope="col">TIME</th>
              <th class="text-center" @click="sort('vehicle')" scope="col">VEHICLE</th>
              <th class="text-center" @click="sort('badge_id')" scope="col">BADGEID</th>
              <th class="text-center" @click="sort('customer_name')" scope="col">CUSTOMERNAME</th>
              <th class="text-center" @click="sort('fare')" scope="col">FARE</th>
              <th class="text-center" @click="sort('tip')" scope="col">TIP</th>
              <th class="text-center" @click="sort('total')" scope="col">TOTAL</th>
              <th class="text-center" @click="sort('account')" scope="col">ACCOUNT</th>
              <th class="text-center" @click="sort('PO')" scope="col">PO</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="15" class></td>
            </tr>
            <tr v-for="row in sortedRows" v-bind:key="row.id">
              <th
                class="text-center success"
                scope="row"
                v-if="row.status == 'verified'"
              >{{row.status}}</th>
              <th class="text-center" scope="row" v-if="row.status == 'unverified'">{{row.status}}</th>
              <th class="text-center" scope="row">{{row.job_id}}</th>
              <th v-if="!row.envelope_id" class="text-center pa-0 ma-0" scope="row">-</th>
              <th v-else class="text-center" style="color:#218c74;" scope="row">{{row.envelope_id}}</th>
              <th v-if="!row.slip_id" class="text-center pa-0 ma-0" scope="row">-</th>
              <th v-else class="text-center" style="color:#218c74;" scope="row">{{row.slip_id}}</th>

              <th
                class="text-center"
                scope="row"
                style="  font-weight: bold; max-width:100%; white-space:nowrap;"
              >{{row.date}}</th>
              <th class="text-center" scope="row">{{row.time}}</th>
              <th class="text-center" scope="row">{{row.vehicle}}</th>
              <th class="text-center" scope="row">{{row.badge_id}}</th>
              <th class="text-center" scope="row">{{row.customer_name}}</th>
              <th class="text-center" scope="row">${{ parseFloat(row.fare/100).toFixed(2)}}</th>
              <th class="text-center" scope="row">${{ parseFloat(row.tip/100).toFixed(2)}}</th>
              <th class="text-center" scope="row">${{ parseFloat(row.total/100).toFixed(2)}}</th>
              <th class="text-center" scope="row">{{row.account}}</th>
              <th class="text-center" scope="row">{{row.PO}}</th>
              <th class="text-right">
                <v-icon
                  v-if="!isClosed"
                  color="success"
                  class="mx-1"
                  @click="openEditDialog(row)"
                >edit</v-icon>
                <v-icon
                  v-if="!isClosed"
                  @click="deleteItem(row.id)"
                  color="red lighten-1"
                  class="mx-1"
                >delete</v-icon>
              </th>
            </tr>
            <tr>
              <th class="text-uppercase text-center table-primary" colspan="9">TOTALS</th>
              <th
                class="text-uppercase text-center table-primary"
                style="  font-weight: bold; max-width:100%; white-space:nowrap;"
              >$ {{total_fare}}</th>
              <th
                class="text-uppercase text-center table-primary"
                style="  font-weight: bold; max-width:100%; white-space:nowrap;"
              >$ {{total_tip}}</th>
              <th
                class="text-uppercase text-center table-primary"
                style="  font-weight: bold; max-width:100%; white-space:nowrap;"
              >$ {{total_total}}</th>
              <th
                colspan="3"
                class="text-uppercase text-center table-success"
              >{{accountSlips_number}} verified account slips</th>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- generate button -->
  </div>
</template>
<script>
export default {
  created() {
    this.loading = true;
    this.fetchBatchData(this.$route.params.batch_id);
    this.getSummaryById(this.$route.params.envelope_id);
  },
  data() {
    return {
      summary: {},
      //sort
      currentSort: "name",
      currentSlipIDTemp: null,
      currentSortDir: "asc",
      searchbyjobid: null,
      //trip form data
      envelopes: [],
      envelopes_ids: [],
      trip: {
        date:'',
        time:'',
      },
      date: null,
      add_modal_dateDate: false,
      pickup_date: null,
      dropoff__date: null,

      modal_dateDate: false,
      modal_dateTime: false,
      modal_dateTime_edit: false,

      modal_pickup_dateDate: false,
      modal_pickup_dateTime: false,

      modal_dropoff__dateDate: false,
      modal_dropoff__dateTime: false,
      //other data
      jobIdToImport: "",
      editId: -1,
      statuses: ["verified", "unverified"],
      editedTrip: {
        dateDate: null,
        dateTime: null
      },
      addDialog1: false,
      addDialogByJobId: false,
      addDialogManually: false,
      isOpenEditDialog: false,
      envelopeInput: "",
      editDialog: false,
      loading: true,
      form_Amount: null,
      form_Envelope: null,
      edit_Amount: null,
      edit_Envelope: null,
      batchId: "",
      envelope: "",
      month: "",
      batch: "",
      batches: [
        {
          text: "Batch 1",
          value: "1"
        },
        {
          text: "Batch 2",
          value: "2"
        },
        {
          text: "Batch 3",
          value: "3"
        }
      ],
      rows: []
    };
  },

  // watch: {
  //   envelope_id_selected: function(newVal) {
  //     this.envelope_id_selected = newVal;
  //     this.$refs.edit_image_id_ref.focus();
  //   }
  // },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    //sort
    sort: function(s) {
      //if s == current sort, reverse
      if (s === this.currentSort) {
        this.currentSortDir = this.currentSortDir === "asc" ? "desc" : "asc";
      }
      this.currentSort = s;
    },
    //add trip methods
    pad(n) {
      return n < 10 ? "0" + n : n;
    },
    prepareDateForMenu() {
      // date
      if (this.editedTrip.date) {
        console.log("prepare date");
        console.log(this.editedTrip.date);
        var date = new Date(this.editedTrip.date);
        var day = date.getDate();
        var month = date.getMonth(); //Be careful! January is 0 not 1
        var year = date.getFullYear();
        var hour = date.getHours();
        var minute = date.getMinutes();
        this.editedTrip.date =
          year + "-" + this.pad(month + 1) + "-" + this.pad(day);
      }
    },
    constructDates() {
      if (this.trip.dateDate && this.trip.dateTime) {
        var date = new Date(this.trip.dateDate);
        var day = date.getDate();
        var month = date.getMonth(); //Be careful! January is 0 not 1
        var year = date.getFullYear();
        var date = year + "-" + this.pad(month + 1) + "-" + this.pad(day);
        this.trip.date = date + " " + this.trip.dateTime + ":00";
      } 
      if (this.editedTrip.date && this.editedTrip.time) {
        console.log("construct date");
        console.log(this.editedTrip.date);

        var date = new Date(this.editedTrip.date);
        var day = date.getDate();
        var month = date.getMonth(); //Be careful! January is 0 not 1
        var year = date.getFullYear();
        this.editedTrip.date =
          year + "-" + this.pad(month + 1) + "-" + this.pad(day);
        this.editedTrip.date =
          this.editedTrip.date + " " + this.editedTrip.time + ":00";
      }
    },
    separateDates() {
      // date
      if (this.editedTrip.date) {
        var date = new Date(this.editedTrip.date);
        var day = date.getDate();
        var month = date.getMonth(); //Be careful! January is 0 not 1
        var year = date.getFullYear();
        var hour = date.getHours();
        var minute = date.getMinutes();
        this.editedTrip.dateDate =
          year + "-" + this.pad(month + 1) + "-" + this.pad(day);
        this.editedTrip.dateTime = this.pad(hour) + ":" + this.pad(minute);
      }
    },
    storeTrip() {
      // this.constructDates();
      this.trip.batch_id = this.batchId;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .post(this.BASE_URL() + "/api/admin/trip", this.trip)
        .then(res => {
          this.addDialogManually = false;
          this.addDialog1 = false;
          this.refreshTable();
          this.$swal({
            title: "Created!",
            text: "Trip created Successfully!",
            type: "success",
            timer: 700
          });

          this.trip = {
            envelope_id: this.envelope.envelope_id
          };
          // this.openEditDialog(res.data.data);
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
        });
    },
    //other methods
    refreshTable() {
      this.loading = true;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(
          this.BASE_URL() +
            "/api/admin/AccountSlips/" +
            this.batchId +
            "/" +
            this.envelope.envelope_id
        )
        .then(res => {
          this.rows = res.data.data;
          let max = 0;
          this.rows.forEach(row => {
            if (row.slip_id && parseInt(row.slip_id) > parseInt(max)) {
              max = parseInt(row.slip_id);
            }
          });
          this.currentSlipIDTemp = parseInt(max) + 1;
          this.loading = false;
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
          this.loading = false;
        });
    },
    getSummaryById(id) {
      //fetch envelope data
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/Envelope/summary/" + id)
        .then(res => {
          this.summary = res.data;
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    fetchBatchData(id) {
      //fetch batches by year
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/batch/" + id)
        .then(res => {
          this.batch = res.data.data;
          this.batchId =
            "B" + this.batch.number + this.batch.month + this.batch.year;
          this.fetchEnvelopes();
          this.getEnvelopeById(this.$route.params.envelope_id);
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    getEnvelopeById(id) {
      //fetch envelope data
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/Envelope/" + id)
        .then(res => {
          this.envelope = res.data.data;
          this.trip.envelope_id = this.envelope.envelope_id;
          this.refreshTable();
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },

    fetchEnvelopes() {
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/Envelopes/" + this.batchId)
        .then(res => {
          this.envelopes = res.data.data;
          this.envelopes.forEach(envelope => {
            this.envelopes_ids.push(envelope.envelope_id);
          });
        })
        .catch(error => {
          this.$swal(
            "Error in fetching evelopes",
            error.response.data.errors,
            "warning"
          );
        });
    },
    search(searchbyjobid) {
      this.searchbyjobid = "";
      if (!searchbyjobid) {
        this.refreshTable();
      } else {
        //verifiying search value is a number
        if (isNaN(searchbyjobid)) {
          this.$swal(
            "Invalid Job ID!",
            "please try to enter a number !",
            "warning"
          );
          return;
        }
        // getting info from api
        this.loading = true;
        axios.defaults.headers.common["Authorization"] =
          "Bearer " + this.$store.state.token_admin;
        axios
          .get(
            this.BASE_URL() +
              "/api/admin/AccountSlips_search/search?job_id=" +
              searchbyjobid +
              "&batch_id=" +
              this.batchId +
              "&envelope_id=" +
              this.envelope.envelope_id
          )
          .then(res => {
            if (res.data.data.length == 0) {
              this.loading = false;
              this.$swal.fire({
                text: "No Account Slip found!",
                type: "warning",
                animation: true,
                focusConfirm: false,
                padding: "2rem",
                confirmButtonText: "Try Again"
              });
            } else {
              this.rows = res.data.data;
              this.loading = false;
              if (this.rows.length == 1) {
                this.openEditDialog(this.rows[0]);
              }
            }
          })
          .catch(error => {
            this.$swal(
              "Try again",
              error.response.data.errors + " " + error.response.data.message,
              "warning"
            );
            this.loading = false;
          });
        //verifying if any data matches the search exist
      }
    },
    importTrip(jobIdToImport) {
      if (
        this.jobIdToImport != null &&
        this.jobIdToImport != "" &&
        this.jobIdToImport != "0"
      ) {
        axios.defaults.headers.common["Authorization"] =
          "Bearer " + this.$store.state.token_admin;
        axios
          .get(
            this.BASE_URL() +
              "/api/admin/importTrip/" +
              jobIdToImport +
              "/" +
              this.batchId
          )

          .then(res => {
            this.$swal({
              title: "Imported!",
              text: "Trip Imported Successfully!",
              type: "success",
              timer: 700
            });
            this.addDialogByJobId = false;
            this.addDialog1 = false;
            this.refreshTable();
            //after refreshing table open the imported trip in the edit panel
            this.openEditDialog(res.data.data);
          })
          .catch(error => {
            this.$swal("Error", error.response.data.message, "warning");
          });
        //reset form
        this.jobIdToImport = null;
      } else {
        this.$swal("Invalid data", "", "warning");
      }
    },
    openEditDialog(row) {
      this.editedTrip = Object.assign({}, row);
      if (!this.editedTrip.slip_id)
        this.editedTrip.slip_id = this.currentSlipIDTemp;
      this.editId = row.id;
      // this.prepareDateForMenu();
      this.isOpenEditDialogComputed = true;
    },
    editTrip() {
      // this.constructDates();
      if (
        this.editedTrip.slip_id &&
        this.editedTrip.envelope_id &&
        this.editedTrip.account
      ) {
        this.editedTrip._method = "patch";
        //add tow to the database
        axios.defaults.headers.common["Authorization"] =
          "Bearer " + this.$store.state.token_admin;
        axios
          .post(
            this.BASE_URL() + "/api/admin/AccountSlip/" + this.editId,
            this.editedTrip
          )
          .then(res => {
            this.isOpenEditDialogComputed = false;
            this.editId = -1;
            this.refreshTable();
            this.$swal({
              title: "Updated!",
              text: "Account Slip updated Successfully!",
              type: "success",
              timer: 700
            }).then(res => {
              this.$refs.searchInput.focus();
            });
          })
          .catch(error => {
            this.$swal(
              "Something wrong!",
              error.response.data.message,
              "warning"
            );
          });
      } else {
        this.$swal(
          "Something missing !",
          "make sure that slip id, envelope id, account are filled ! ",
          "warning"
        );
        this.refreshTable();
      }
    },

    deleteItem(id) {
      this.$swal
        .fire({
          text: "Are You Sure You Want To Delete This Row?",
          type: "question",
          animation: true,
          focusConfirm: false,
          padding: "2rem",
          showCancelButton: true,
          confirmButtonText: "Yes"
        })
        .then(res => {
          if (res.value) {
            axios.defaults.headers.common["Authorization"] =
              "Bearer " + this.$store.state.token_admin;
            axios
              .delete(this.BASE_URL() + "/api/admin/AccountSlip/" + id)
              .then(res => {
                this.$swal({
                  title: "Deleted!",
                  text: "Trip Deleted Successfully!",
                  type: "success",
                  timer: 700
                });
                this.refreshTable();
              })
              .catch(error => {
                this.$swal("Try again", error.response.data.errors, "warning");
              });
          }
        });
    },

    generateExcel() {
      console.log("generateExcel");
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/posSlips/download/" + this.batchId)
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error.response.data.errors);
        });
    }
  },
  computed: {
    isClosed() {
      if (
        this.envelope.status == "UNPROCESSED" ||
        this.envelope.status == "CLOSED" ||
        this.envelope.status == ""
      ) {
        this.status_label =
          "Envelope " + this.envelope.envelope_id + " is Closed";
        return true;
      } else {
        this.status_label =
          "Envelope " + this.envelope.envelope_id + " Is Under Processing";
        return false;
      }
    },
    total_fare() {
      let total = 0;
      this.rows.forEach(row => {
        if (row.status == "verified")
          total = parseFloat(total) + parseFloat(row.fare);
      });
      total = total / 100;

      return total.toFixed(2);
    },

    total_tip() {
      let total = 0;
      this.rows.forEach(row => {
        if (row.status == "verified")
          total = parseFloat(total) + parseFloat(row.tip);
      });
      total = total / 100;

      return total.toFixed(2);
    },

    total_total() {
      let total = 0;
      this.rows.forEach(row => {
        if (row.status == "verified")
          total = parseFloat(total) + parseFloat(row.total);
      });
      total = total / 100;
      return total.toFixed(2);
    },
    accountSlips_number() {
      let total = 0;
      this.rows.forEach(row => {
        if (row.status == "verified") total++;
      });
      return total;
    },

    currentSlipID: {
      get: function() {
        if (this.trip.slip_id) {
          return this.trip.slip_id;
        } else {
          let max = 0;
          this.rows.forEach(row => {
            if (row.slip_id && parseInt(row.slip_id) > parseInt(max)) {
              max = parseInt(row.slip_id);
            }
          });
          this.trip.slip_id = parseInt(max) + 1;
          return this.trip.slip_id;
        }
      },
      // setter
      set: function(newValue) {
        this.trip.slip_id = newValue;
      }
    },
    currentSlipID_edit: {
      get: function() {
        if (this.editedTrip.slip_id) {
          return this.editedTrip.slip_id;
        } else {
          let max = 0;
          this.rows.forEach(row => {
            if (row.slip_id && parseInt(row.slip_id) > parseInt(max)) {
              max = parseInt(row.slip_id);
            }
          });
          this.editedTrip.slip_id = parseInt(max) + 1;
          return this.editedTrip.slip_id;
        }
      },
      // setter
      set: function(newValue) {
        this.editedTrip.slip_id = newValue;
      }
    },
    currentEnvelopeID: {
      get: function() {
        if (this.trip.envelope_id) {
          return this.trip.envelope_id;
        } else {
          this.trip.envelope_id = this.envelope.envelope_id;
          return this.trip.envelope_id;
        }
      },
      // setter
      set: function(newValue) {
        this.trip.envelope_id = newValue;
      }
    },
    envelope_id_selected: {
      get: function() {
        if (this.editedTrip.envelope_id) {
          return this.editedTrip.envelope_id;
        } else {
          this.editedTrip.envelope_id = this.envelope.envelope_id;
          return this.editedTrip.envelope_id;
        }
      },
      // setter
      set: function(newValue) {
        this.editedTrip.envelope_id = newValue;
      }
    },

    isOpenEditDialogComputed: {
      // getter
      get: function() {
        return this.isOpenEditDialog;
      },
      // setter
      set: function(newValue) {
        this.isOpenEditDialog = newValue;
      }
    },
    valid() {
      if (
        this.form_Envelope != null &&
        this.form_Amount != null &&
        this.form_Envelope != "" &&
        this.form_Amount != "" &&
        this.form_Envelope != 0 &&
        this.form_Amount != 0
      ) {
        return true;
      } else {
        return false;
      }
    },
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Current row";
    },
    sortedRows: function() {
      return this.rows.sort((a, b) => {
        let modifier = 1;
        if (this.currentSortDir === "desc") modifier = -1;
        if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
        if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
        return 0;
      });
    }
  }
};
</script>
<style scoped>
.wrapper_box {
  display: grid;
  grid-gap: 0.2em;
  grid-auto-rows: minmax(10px, auto);
}

a,
a:hover {
  text-decoration: none;
}
.wrapper_header {
  display: grid;
  grid-template-columns: 10fr 0fr;
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
</style>