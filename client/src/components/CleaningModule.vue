<template>
  <div>
    <h1>
      <font-awesome-icon icon="broom"/>
      || {{ header }}
    </h1>
    <div>
      <b-table :items="items" :fields="fields" striped responsive="sm">
        <template #cell(show_details)="row">
          <b-button size="sm" @click="row.toggleDetails" class="mr-2">
            {{ row.detailsShowing ? 'Ukryj' : 'Pokaż' }} szczegóły
          </b-button>
        </template>
        <template #row-details="row">
          <b-table :items="row.item.details" :fields="fields_details" striped responsive="sm">
          </b-table>
        </template>
      </b-table>
    </div>

  </div>
</template>

<script>

import {cleaningEventServices} from "@/_services/cleaning_event_service";

export default {
  name: "CleaningModule",
  data() {
    return {
      header: 'Nadchodzące zmiany',
      fields: [{
        key: 'date_from',
        label: 'Data',
      },
        {
          key: 'title',
          label: 'Tytuł',
        }, {
          key: 'number_of_cottages',
          label: 'Liczba domków',
        },
        {
          key: 'show_details',
          label: 'Pokaż szczegóły',
        },],
      fields_details:
          [
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
          ],
      items: []
    }
  },
  methods: {
    getEvents() {
      const self = this
      cleaningEventServices.getFutureCleaningEventsWithDetails().then(function (response) {

        self.items = response

      }).catch(function (error) {
        if (error) {
          self.errorNotify = error;
          self.loading = false;
        }
      });

    }
  },
  mounted() {

    this.getEvents()
  }
}
</script>

<style scoped>

</style>