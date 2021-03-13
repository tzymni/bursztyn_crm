<template>
  <div>
    <form @submit.prevent="checkReservationAvailability">
      <div class="row form-group">
        <div class="col">
          <label for="date_from">Od</label>
          <input
              type="date"
              v-model="date_from"
              name="date_from"
              class="form-control"
              required="required"
              :class="{ 'is-invalid': submitted && !date_from }"
          />
          <div v-show="submitted && !date_from" class="invalid-feedback">
            Data jest wymagana.
          </div>
        </div>

        <div class="col">
          <label for="date_to">Do</label>
          <input
              type="date"
              v-model="date_to"
              name="date_from"
              required="required"
              class="form-control"
              :class="{ 'is-invalid': submitted && !date_to }"
          />
          <div v-show="submitted && !date_to" class="invalid-feedback">
            Data jest wymagana.
          </div>
        </div>
      </div>
      <div class="row form-group">
        <div class="col">
          <label for="cottage_ids">Domki</label>


          <multiselect
              v-model="cottage_ids"
              :height="300"
              :options="options"
              :multiple="true"
              :taggable="true"
              :close-on-select="false"
              :clear-on-select="false"
              :preserve-search="true"
              :allowEmpty="false"
              tag-placeholder="Dodaj nowy domek"
              placeholder="Wyszukaj domki"
              label="text"
              track-by="id"
              :preselect-first="false"
          ></multiselect>


          <div style="white-space: pre-line;" id="availability-response">{{ availability_response }}</div>
        </div>
      </div>

      <div class="form-group">
        <button class="btn btn-primary" :disabled="loading">
          Szukaj
        </button>
        <img
            v-show="loading"
            src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA=="
        />
      </div>
      <div v-if="errorNotify" class="alert alert-danger">
        {{ errorNotify }}
      </div>
    </form>
  </div>
</template>

<script>
import {cottageService} from "../_services/cottage.service";
import {reservationService} from "@/_services/reservation.service";
import Multiselect from 'vue-multiselect'

export default {
  name: "CheckAvailabilityForm",
  components: {Multiselect},
  data() {
    return {
      // childMessage: '',
      id: null,
      date_from: "",
      date_to: "",
      cottage_ids: null,
      submitted: false,
      returnUrl: "",
      errorNotify: "",
      loading: false,
      selected: null,
      options: [],
      selectedIndex: 0,
      availability_response: null
    };
  },
  mounted() {
    this.getCottages();
  },
  methods: {
    checkReservationAvailability() {

      const {date_from, date_to, cottage_ids} = this;
      let only_ids = [];

      cottage_ids.map(function (value) {
        only_ids.push(value.id)
      })

      let cottage_ids_string = only_ids.join(",")

      const self = this;
      reservationService.checkAvailability(date_from, date_to, cottage_ids_string).then(function (response) {

        let tmp_response = ''

        if (response.available_cottages.length === 0) {
          tmp_response += "Brak dostępnych domków w okresie " + response.request_details.date_from + " - " + response.request_details.date_to + ".\n"
        } else {
          tmp_response += "Dostępne domki w okresie " + response.request_details.date_from + " - " + response.request_details.date_to + ":\n"

          response.available_cottages.map(function (value) {
            tmp_response += "-" +value.name + "\n"
          })
        }


        self.availability_response = tmp_response
      })
          .catch(function (error) {
            if (error) {
              self.errorNotify = error;
              self.loading = false;
            }
          })

    },
    formatClickedDate() {
      let changedDate = this.clickedStartDate.replace(/\./g, "-");
      const parts = changedDate.split("-");
      let newDate =
          parts[2] + "-" + parts[1] + "-" + ("0" + parts[0]).slice(-2);
      return newDate;
    },
    getCottages() {
      const self = this;
      cottageService
          .getCottages()
          .then(function (response) {
            let list = [];
            response.map(function (value) {
              let text = value.name;
              list.push({id: value.id, text: text});
            });
            self.options = list;
            self.cottage_ids = list;
          })
          .catch(function (error) {
            if (error) {
              self.errorNotify = error;
              self.loading = false;
            }
          });
    },

  }
  ,
  computed: {}
  ,
}
;
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>


