<template>
    <div class="Reservations">
        <h1>
            <font-awesome-icon icon="calendar-check"/>
            || {{ header }}
        </h1>
        <div>
            <div class="button-group">
                <button @click="$refs.MyCoolTable.expandAll()">Expand All</button>
                <button @click="$refs.MyCoolTable.collapseAll()">Collapse All</button>
            </div>
            <vue-good-table
                    ref="MyCoolTable"
                    :columns="columns"
                    :rows="rows"
                    :line-numbers="true"

                    :group-options="{
    enabled: true,
    collapsable: true,
  }"
                    :search-options="{
    enabled: true
  }">
                <template slot="table-row" slot-scope="props">
    <span v-if="props.column.field == 'action'">
       {{props.row.id}}

                      <a class="btn btn-primary">
                <font-awesome-icon icon="edit"/>
              </a>
    </span>

                    <span v-else>
      {{props.formattedRow[props.column.field]}}
    </span>
                </template>

            </vue-good-table>
        </div>
    </div>
</template>

<script>
    import {reservationService} from "../_services/reservation.service";

    export default {
        name: 'my-component',
        data() {
            return {
                header: "Reservations",
                columns: [
                    {
                        label: 'Date from',
                        field: 'date_from',
                        type: 'string',
                        filterOptions: {
                            enabled: true, // enable filter for this column
                            // placeholder: 'Filter This Thing', // placeholder for filter input
                            // filterValue: 'Jane', // initial populated value for this filter
                            // filterDropdownItems: [], // dropdown (with selected values) instead of text input
                            // filterMultiselectDropdownItems: [], // dropdown (with multiple selected values) instead of text input
                            // filterFn: this.columnFilterFn, //custom filter function that
                            // trigger: 'enter', //only trigger on enter not on keyup
                        },
                    },
                    {
                        label: 'Date to',
                        field: 'date_to',
                        type: 'string',
                        filterOptions: {
                            enabled: true, // enable filter for this column
                            // placeholder: 'Filter This Thing', // placeholder for filter input
                            // filterValue: 'Jane', // initial populated value for this filter
                            // filterDropdownItems: [], // dropdown (with selected values) instead of text input
                            // filterMultiselectDropdownItems: [], // dropdown (with multiple selected values) instead of text input
                            // filterFn: this.columnFilterFn, //custom filter function that
                            // trigger: 'enter', //only trigger on enter not on keyup
                        },
                    },
                    {
                        label: 'Full name',
                        field: 'full_name',
                        type: 'string',
                        filterOptions: {
                            enabled: true, // enable filter for this column
                            // placeholder: 'Filter This Thing', // placeholder for filter input
                            // filterValue: 'Jane', // initial populated value for this filter
                            // filterDropdownItems: [], // dropdown (with selected values) instead of text input
                            // filterMultiselectDropdownItems: [], // dropdown (with multiple selected values) instead of text input
                            // filterFn: this.columnFilterFn, //custom filter function that
                            // trigger: 'enter', //only trigger on enter not on keyup
                        },
                    },
                    {
                        label: 'Number of guests',
                        field: 'number_of_guests',
                        type: 'numeric',
                    },
                    {
                        label: 'Advance payment',
                        field: 'advance_payment',
                        type: 'boolean',
                    },
                    {
                        label: 'Action',
                        field: 'action',

                    }
                ],
                rows: [],
            };
        },
        mounted() {
            this.setReservations()
        },
        methods: {
            setReservations() {
                this.clickedStartDate = null;
                var self = this;
                reservationService.getReservationsGroupedByCottages().then(function (response) {


                        let list = [];
                        response.cottages.map(function (cottage) {

                            console.log(cottage);

              

                            let newEvent = {
                                label: cottage.name,
                                mode: 'span',
                                html: false,
                                children: [
                                    {full_name: 'Janek Kowalski', date_from: '2020-06-11', id: 28, date_to: '2020-06-17'},
                                    {
                                        full_name: 'JArosław Kaczyński',
                                        date_from: '2020-06-11',
                                        id: 28,
                                        date_to: '2020-06-17'
                                    }
                                ]
                            }
                            list.push(newEvent)
                        });

                        self.rows = list

                        // self.rows = [                    {
                        //     mode: 'span', // span means this header will span all columns
                        //     label: 'Domek 1', // this is the label that'll be used for the header
                        //     html: false, // if this is true, label will be rendered as html
                        //     children: [
                        //         {full_name: 'Janek Kowalski', date_from: '2020-06-11', id: 28, date_to: '2020-06-17'},
                        //         {full_name: 'JArosław Kaczyński', date_from: '2020-06-11', id: 28, date_to: '2020-06-17'}
                        //     ]
                        // },
                        //     {
                        //         mode: 'span', // span means this header will span all columns
                        //         label: 'Domek 2', // this is the label that'll be used for the header
                        //         html: false, // if this is true, label will be rendered as html
                        //         children: [
                        //             {name: 'Elephant', diet: 'herbivore', count: 5},
                        //             {name: 'Cat', diet: 'carnivore', count: 28}
                        //         ]
                        //     }, {
                        //         mode: 'span', // span means this header will span all columns
                        //         label: 'Domek 3', // this is the label that'll be used for the header
                        //         html: false, // if this is true, label will be rendered as html
                        //         children: [
                        //             {name: 'Elephant', diet: 'herbivore', count: 5},
                        //             {name: 'Cat', diet: 'carnivore', count: 28}
                        //         ]
                        //     },]
                    }
                ).catch(function (error) {
                    if (error) {
                        self.errorNotify = error;
                        self.loading = false;
                    }
                });
            },
        }
    };
</script>


<!--<template>-->
<!--  <div class="Reservations">-->
<!--    <h1>-->
<!--      <font-awesome-icon icon="calendar-check" />-->
<!--      || {{ header }}-->
<!--    </h1>-->
<!--    <div class="container">-->
<!--      <div class="table-wrap">-->
<!--        <b-table-->
<!--          id="reservations"-->
<!--          :per-page="perPage"-->
<!--          :current-page="currentPage"-->
<!--          small-->
<!--          class="table-reservations"-->
<!--          :fields="fields"-->
<!--          :items="reservations"-->
<!--          thead-class="thead-dark text-uppercase"-->
<!--        >-->
<!--        </b-table>-->
<!--        <b-pagination-->
<!--          v-model="currentPage"-->
<!--          :total-rows="rows"-->
<!--          :per-page="perPage"-->
<!--          aria-controls="reservations"-->
<!--        ></b-pagination>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</template>-->

<!--<script>-->
<!--import { reservationService } from "../_services/reservation.service";-->

<!--export default {-->
<!--  name: "Reservations",-->
<!--  data: function() {-->
<!--    return {-->
<!--      header: "Reservations",-->
<!--      reservations: [],-->
<!--      perPage: 10,-->
<!--      currentPage: 1,-->
<!--      fields: [],-->
<!--      editId: null,-->
<!--    };-->
<!--  },-->
<!--  mounted() {-->
<!--    this.setReservations();-->
<!--  },-->
<!--  methods: {-->
<!--    setReservations() {-->
<!--      var self = this;-->
<!--      reservationService-->
<!--        .getEvents()-->
<!--        .then(function(response) {-->
<!--          self.reservations = response;-->
<!--        })-->
<!--        .catch(function(error) {-->
<!--          if (error) {-->
<!--            self.errorNotify = error;-->
<!--            self.loading = false;-->
<!--          }-->
<!--        });-->
<!--    },-->
<!--  },-->
<!--  computed: {-->
<!--    rows() {-->
<!--      return this.reservations.length;-->
<!--    },-->
<!--  },-->
<!--};-->
<!--</script>-->
<style scoped></style>
