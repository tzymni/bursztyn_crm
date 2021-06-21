<template>
  <div>
    {{ title }} dnia {{ date }}
    <br/>
    <br/>
    Kto potwierdził, że będzie? <b></b>
    <br/>
    <div v-if="users_presences">
    <ul id="example-1">
      <li v-if="!users_presences.length">Nikt :(</li>
      <li v-for="item in users_presences" :key="item.title">
        {{ item.title }}
      </li>
    </ul>
</div>

    <br/>
    Lista domków do sprzątania:

    <div class="table-wrap">
      <b-table
          id="users"
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
      ></b-pagination>
    </div>

  </div>
</template>

<script>
import {cleaningEventServices} from "@/_services/cleaning_event.service";
import {userPresenceService} from "@/_services/user_presence.service";

export default {
  name: "CleaningForm",
  data() {
    return {
      // childMessage: '',
      id: null,
      title: "",
      users_presences: [],
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
      this.getCleaningEvent(this.editId)
      this.getCleaningEventDetails(this.editId)
      this.getUserPresences(this.editId)
    }
  },
  props: {
    editId: Number,
  },
  methods: {
    getCleaningEvent(id) {

      const self = this
      cleaningEventServices.getCleaningEvent(id).then(function (data) {
            self.title = data.details.name
            self.date = data.details.date
            self.id = data.details.id
          }
      )
      .catch(function (error) {
            if (error) {
              self.errorNotify = error;
            }
          });

    }, getCleaningEventDetails(id) {

      const self = this
      cleaningEventServices.getCleaningEventDetails(id).then(function (data) {
            self.eventDetails = data
          }
      )
          .catch(function (error) {
            if (error) {
              self.errorNotify = error
            }
          });
    },
    getUserPresences(id) {
      const self = this
      userPresenceService.getUserPresencesByCleaningEvent(id).then(function (data) {
        console.log(data)
        self.users_presences = data.data
      }).catch(function (error) {
        if (error) {
          self.errorNotify = error
        }
      })
    }
  }
}
</script>
