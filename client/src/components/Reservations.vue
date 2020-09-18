<template>
  <div class="Reservations">

    <h1>
      <font-awesome-icon icon="calendar-check"/>
      || {{ header }}
    </h1>
    <div class="calendar-controls">

      <b-modal
          @hide="setReservations()"
          id="reservation-form-modal"
          title="Reservation form"
          hide-footer
      >
        <ReservationForm
            :clickedStartDate="$data.clickedStartDate"
            :editId="$data.editId"
        />
      </b-modal>
    </div>
    <div>
      <div class="button-group">
        <b-button
            class="btn btn-info"
            id="show-reservation-form-modal"
            @click="showReservationFormModal()"
        >
          Add reservation
        </b-button>&nbsp;
        <button  class="btn btn-info"  @click="$refs.ReservationTable.expandAll()">Expand All</button>&nbsp;
        <button  class="btn btn-info" @click="$refs.ReservationTable.collapseAll()">Collapse All</button>&nbsp;
      </div>
      <vue-good-table
          ref="ReservationTable"
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
<!--       {{ props.row.id }}-->

              <a @click="editReservation(props.row.id)" class="btn btn-primary">
                <font-awesome-icon icon="edit" />
              </a>
    </span>

          <span v-else>
      {{ props.formattedRow[props.column.field] }}
    </span>
        </template>

      </vue-good-table>
    </div>
  </div>
</template>

<script>
import {reservationService} from "../_services/reservation.service";
import ReservationForm from "@/components/ReservationForm";

export default {
  name: 'my-component',
  components: {
    ReservationForm,

  },
  data() {
    return {
      header: "Reservations",
      editId: null,
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
          label: 'Phone number',
          field: 'phone_number',
          type: 'numeric',
          filterOptions: {
            enabled: true, // enable filter for this column
            // placeholder: 'Filter This Thing', // placeholder for filter input
            // filterValue: 'Jane', // initial populated value for this filter
            // filterDropdownItems: [], // dropdown (with selected values) instead of text input
            // filterMultiselectDropdownItems: [], // dropdown (with multiple selected values) instead of text input
            // filterFn: this.columnFilterFn, //custom filter function that
            // trigger: 'enter', //only trigger on enter not on keyup
          },
        }, {
          label: 'Number of guests',
          field: 'number_of_guests',
          type: 'numeric',
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
          label: 'Advance payment',
          field: 'advance_payment',
          type: 'boolean',
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
          label: 'Action',
          field: 'action',

        }
      ],
      rows: [],
    };
  },
  mounted() {
    this.setReservations()
    this.editId = null
  },
  methods: {
    showReservationFormModal() {
      this.editId = null
      this.$bvModal.show("reservation-form-modal");
    },
    editReservation(id) {
      this.editId = id
      this.$bvModal.show("reservation-form-modal");
    },
    setReservations() {
      this.clickedStartDate = null;
      const self = this;
      reservationService.getReservationsGroupedByCottages().then(function (response) {

            let list = [];
            response.cottages.map(function (cottage) {

              if (typeof cottage.reservations != 'undefined') {
                const children = [];
                cottage.reservations.map(function (reservation) {
                  let full_name = reservation.guest_first_name + " " + reservation.guest_last_name;
                  let date_from = reservation.event.date_from;
                  let date_to = reservation.event.date_to;
                  let event_id = reservation.event.id;
                  let number_of_guests = reservation.guests_number;
                  let advance_payment = reservation.advance_payment;
                  let phone_number = reservation.guest_phone;

                  children.push({
                    full_name: full_name,
                    date_from: date_from,
                    id: event_id,
                    date_to: date_to,
                    number_of_guests: number_of_guests,
                    advance_payment: advance_payment,
                    phone_number: phone_number,
                  });
                })

                let newEvent = {
                  label: cottage.name,
                  mode: 'span',
                  html: false,
                  children: children
                }
                list.push(newEvent)
              }
            });

            self.rows = list

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
