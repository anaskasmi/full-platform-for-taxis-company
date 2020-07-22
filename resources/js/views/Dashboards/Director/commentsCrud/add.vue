<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div class>
                <NavbarDirector></NavbarDirector>
                <Drawer></Drawer>

                <!-- back button -->
                <div class="row col-12">
                    <v-btn color="grey" text tile class="float-left" :to="commentsRoute">
                        <v-icon class="mr-2">keyboard_return</v-icon>
                        Comments
                    </v-btn>
                </div>
                <hr/>
                <!-- comment box -->
                <v-col cols="12" class="pa-0 ma-0 pt-10">
                    <v-textarea
                        v-model="comment.comment"
                        label="My Comment"
                        prepend-inner-icon="edit"
                        color="light-blue darken-4"
                        shaped
                        clearable
                        auto-grow
                        outlined
                        rows="16"
                        row-height="15"
                    ></v-textarea>
                </v-col>
                <!-- date -->
                <v-col cols="12" class="pl-0">
                    <v-dialog
                        ref="dialog"
                        v-model="modal"
                        :return-value.sync="comment.date"
                        persistent
                        width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field v-model="comment.date" label="Date" prepend-icon="event" readonly
                                          v-on="on"></v-text-field>
                        </template>
                        <v-date-picker v-model="comment.date">
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="modal = false">Cancel</v-btn>
                            <v-btn text color="primary" @click="$refs.dialog.save(comment.date)">OK</v-btn>
                        </v-date-picker>
                    </v-dialog>
                </v-col>

                <!-- buttons -->
                <v-col cols="12" class="pa-0 ma-0">
                    <v-btn color="primary" tile block class="mb-1" @click="submitComment()">submit</v-btn>
                    <v-btn block color="grey lighten-1" dark tile class @click="$router.go(-1)">cancel</v-btn>
                </v-col>
            </div>
        </div>
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <!----------------------------Mobile Version---------------------------->
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->

        <div class="pa-0 ma-0 " v-if="$mq=='mobile'">
            <!-- nav -->
            <NavbarDirector></NavbarDirector>
            <div class="container px-4">

                <div>


                    <!-- back button -->
                    <div class=" text-center my-4">
                        <v-btn color="grey" text tile class="" @click="$router.go(-1)">
                            <v-icon class="mr-2">keyboard_return</v-icon>
                            Back to comments
                        </v-btn>
                    </div>
                    <hr/>
                    <!-- comment box -->
                    <v-col cols="12" class="pa-0 ma-0 pt-10">
                        <v-textarea
                            v-model="comment.comment"
                            label="My Comment"
                            prepend-inner-icon="edit"
                            color="light-blue darken-4"
                            shaped
                            clearable
                            auto-grow
                            outlined
                            rows="16"
                            row-height="15"
                        ></v-textarea>
                    </v-col>
                    <!-- date -->
                    <v-col cols="12" class="pl-0">
                        <v-dialog
                            ref="dialog"
                            v-model="modal"
                            :return-value.sync="comment.date"
                            persistent
                            width="290px"
                        >
                            <template v-slot:activator="{ on }">
                                <v-text-field v-model="comment.date" label="Date" prepend-icon="event" readonly
                                              v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="comment.date">
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" @click="modal = false">Cancel</v-btn>
                                <v-btn text color="primary" @click="$refs.dialog.save(comment.date)">OK</v-btn>
                            </v-date-picker>
                        </v-dialog>
                    </v-col>

                    <!-- buttons -->
                    <v-col cols="12" class="pa-0 ma-0">
                        <v-btn color="primary" tile block class="mb-1" @click="submitComment()">submit</v-btn>
                        <v-btn block color="grey lighten-1" dark tile class @click="$router.go(-1)">cancel</v-btn>
                    </v-col>
                </div>
            </div>
        </div>

    </div>

</template>
<script>
    import NavbarDirector from "@/js/components/navbars/Director.vue";
    import Drawer from "@/js/components/drawers/Director.vue";

    export default {
        components: {
            NavbarDirector,
            Drawer
        },
        created() {
            this.id = this.$route.params.id;
        },
        data() {
            return {
                commentsRoute: {name: ""},
                comment: {
                    comment: "",
                    date: ""
                },
                modal: false,
                id: ""
            };
        },

        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            submitComment() {
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_director;
                axios
                    .post(this.BASE_URL() + "/api/director/comment", this.comment)
                    .then(response => {
                        let newId = response.data.data.id;
                        console.log(response.data.data.id);
                        this.showComment(newId);
                    })
                    .catch(error => {
                        //   this.inputErrors = error.response.data.errors;
                        console.log(error)
                        $("html, body").animate({scrollTop: 0}, "200");
                    });
            },

            showComment(newId) {
                this.$router.push({
                    name: "DirectorDashboard_comments_show",
                    params: {id: newId}
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
        grid-template-columns: 10fr 2fr;
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
</style>
