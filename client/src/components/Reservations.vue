<template>
  <div class="reservations">
    <h1>
      <font-awesome-icon icon="calendar-check"/>
      || {{ header }}
    </h1>
    <div class="container">
      <div class="calendar-controls">
        <button class="btn btn-info" id="expend-collapse" @click="expandCollapseTable()">{{ button.text }}</button>&nbsp;
        <button class="btn btn-info" id="todays-date" @click="setDateToToday()">Od dzis</button>&nbsp;
        <b-modal
            @hide="setReservations()"
            id="reservation-form-modal"
            title="Rezerwacja"
            hide-footer
        >
          <ReservationForm
              :clickedStartDate="$data.clickedStartDate"
              :editId="$data.editId"
          />
        </b-modal>
      </div>

      <vue-good-table
          ref="ReservationTable"
          :columns="columns"
          :rows="rows"
          theme="black-rhino"
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
              <a @click="editReservation(props.row.id)" class="btn btn-primary">
                <font-awesome-icon icon="edit"/>
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
import moment from 'moment'

export default {
  name: 'reservations',
  components: {
    ReservationForm,
  },
  data() {
    return {
      header: "Rezerwacje",
      editId: null,
      button: {
        text: 'Rozwiń wszystkie'
      },
      todaysDate: '',
      expandTable: true,
      columns: [
        {
          label: 'Data od',
          field: 'date_from',
          type: 'string',
          filterOptions: {
            enabled: true,
            filterValue: this.todaysDate,
            filterFn: function (data, filterString) {
              const filteredData = parseInt(Date.parse(filterString));
              data = parseInt(Date.parse(data));
              return data >= filteredData;
            }
          },
        },
        {
          label: 'Data do',
          field: 'date_to',
          type: 'string',
          filterOptions: {
            enabled: true,
            filterFn: function (data, filterString) {
              const filteredData = parseInt(Date.parse(filterString));
              data = parseInt(Date.parse(data));
              return data <= filteredData;
            }
          },
        },
        {
          label: 'Rezerwujący',
          field: 'full_name',
          type: 'string',
          filterOptions: {
            enabled: true,
          },
        },
        {
          label: 'Numer telefonur',
          field: 'phone_number',
          type: 'numeric',
          filterOptions: {
            enabled: true,
          },
        }, {
          label: 'Liczba gości',
          field: 'number_of_guests',
          type: 'numeric',
          filterOptions: {
            enabled: true,
          },
        },
        {
          label: 'Opcje',
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
      this.$bvModal.show("reservation-form-modal")
    },
    expandCollapseTable() {
      if (this.expandTable == true) {
        this.button.text = "Zwiń wszystkie"
        this.$refs.ReservationTable.expandAll()
        this.expandTable = false
      } else {
        this.button.text = "Rozwiń wszystkie"
        this.$refs.ReservationTable.collapseAll()
        this.expandTable = true
      }
    },
    editReservation(id) {
      this.editId = id
      this.$bvModal.show("reservation-form-modal")
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
                  let full_name = reservation.guest_first_name + " " + reservation.guest_last_name
                  let date_from = reservation.event.date_from
                  let date_to = reservation.event.date_to
                  let event_id = reservation.event.id
                  let number_of_guests = reservation.guests_number
                  let phone_number = reservation.guest_phone

                  children.push({
                    full_name: full_name,
                    date_from: date_from,
                    id: event_id,
                    date_to: date_to,
                    number_of_guests: number_of_guests,
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
          self.errorNotify = error
          self.loading = false
        }
      });
    },
    setDateToToday() {
      let date = this.columns[0].filterOptions.filterValue;

      if (date === '' || typeof date == 'undefined') {
        date = moment(String(Date(Date.now()))).format('YYYY/MM/DD')
      } else {
        date = ''
      }
      this.columns[0].filterOptions.filterValue = date
    },
  }
};
</script>

<style scoped>
.reservations {
  width: 100%;
  height: 100%;
  max-width: 1300px;
}
</style>
