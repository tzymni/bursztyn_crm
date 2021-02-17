<template>
  <div>
    {{ title }} dnia {{ date }}
    <br/>
    <br/>
    Lista domków do sprzątania:

    <div class="table-wrap">
      <b-table
          id="users"
          :per-page="perPage"
          :current-page="currentPage"
          small
          class="table-users"
          :fields="fields"
          :items="eventDetails"
          thead-class="thead-dark text-uppercase"
      >
        <template v-slot:head(id)="data">
          <p class="hide">{{ data.field.id }}</p>
        </template>
        <template v-slot:cell(id)="data">
          <p class="hide">{{ data.item.id }}</p>
        </template>
        <template v-slot:head(is_active)="data">
          <p class="hide">{{ data.field.is_active }}</p>
          <p>Operations</p>
        </template>
        <template v-slot:cell(is_active)="data">
          <p class="hide">{{ data.item.is_active }}</p>
          <a @click="deleteUser(data.item.id)" class="btn btn-danger">
            <font-awesome-icon icon="trash-alt"/>
          </a>
          <a @click="showModal(data.item.id)" class="btn btn-primary">
            <font-awesome-icon icon="edit"/>
          </a>
        </template>
      </b-table>
      <b-pagination
          v-model="currentPage"
          :total-rows="rows"
          :per-page="perPage"
          aria-controls="users"
      ></b-pagination>
    </div>

  </div>
</template>

<script>
import {cleaningEventServices} from "@/_services/cleaning_event_service";

export default {
  name: "CleaningForm",
  data() {
    return {
      // childMessage: '',
      id: null,
      title: "",
      date: "",
      eventDetails: [],
      fields: [
        {
          key: 'cottage_name',
          label: 'Domek',
        },
        {
          key: 'next_reservation_date',
          label: 'Data następnej rezerwacji',
        },
        {
          key: 'next_reservation_period',
          label: 'Długość następnej rezerwacji (dni)',
        },
      ]
    };
  },
  mounted() {
    if (typeof this.editId != 'undefined' && this.editId != null) {
      this.getCleaningEvent(this.editId);
      this.getCleaningEventDetails(this.editId);
    }
  },
  props: {
    editId: Number,
  },
  methods: {
    getCleaningEvent(id) {

      var self = this;
      cleaningEventServices.getCleaningEvent(id).then(function (data) {

            self.title = data.name;
            self.date = data.date;
            self.id = data.id;

          }
      )
          .catch(function (error) {
            if (error) {
              self.errorNotify = error;
            }
          });

    }, getCleaningEventDetails(id) {

      var self = this;
      cleaningEventServices.getCleaningEventDetails(id).then(function (data) {

            self.eventDetails = data
            console.log(data)
          }
      )
          .catch(function (error) {
            if (error) {
              self.errorNotify = error;
            }
          });

    },

  }
}
</script>

<style scoped>

</style>