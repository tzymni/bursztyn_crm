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
export default {
  name: "CleaningModule",
  data() {
    return {
      header: 'Nadchodzące zmiany',
      fields: [{
        key: 'date',
        label: 'Data',
      },
        {
          key: 'title',
          label: 'Tytuł',
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
  }
}
</script>

<style scoped>

</style>