<template>
  <div class="Reservations">
    <h1>
      <font-awesome-icon icon="calendar-check" />
      || {{ header }}
    </h1>
    <div class="container">
      <div class="table-wrap">
        <b-table
          id="reservations"
          :per-page="perPage"
          :current-page="currentPage"
          small
          class="table-reservations"
          :fields="fields"
          :items="reservations"
          thead-class="thead-dark text-uppercase"
        >
        </b-table>
        <b-pagination
          v-model="currentPage"
          :total-rows="rows"
          :per-page="perPage"
          aria-controls="reservations"
        ></b-pagination>
      </div>
    </div>
  </div>
</template>

<script>
import { reservationService } from "../_services/reservation.service";

export default {
  name: "Reservations",
  data: function() {
    return {
      header: "Reservations",
      reservations: [],
      perPage: 10,
      currentPage: 1,
      fields: [],
      editId: null,
    };
  },
  mounted() {
    this.setReservations();
  },
  methods: {
    setReservations() {
      var self = this;
      reservationService
        .getEvents()
        .then(function(response) {
          self.reservations = response;
        })
        .catch(function(error) {
          if (error) {
            self.errorNotify = error;
            self.loading = false;
          }
        });
    },
  },
  computed: {
    rows() {
      return this.reservations.length;
    },
  },
};
</script>
<style scoped></style>
