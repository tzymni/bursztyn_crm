<template>
  <div>
    <form @submit.prevent="handleSubmit">
      <div id="square" class="square mb-3">
        <i class="fas fa-home fa-5x"></i>
      </div>
      <div class="row form-group">
        <div class="col">
          <label for="date_from">Od</label>
          <input
              type="date"
              v-model="date_from"
              name="date_from"
              class="form-control"
              :class="{ 'is-invalid': submitted && !date_from }"
          />
          <div v-show="submitted && !date_from" class="invalid-feedback">
            Data przyjazdu jest obowiazkowa
          </div>
        </div>

        <div class="col">
          <label for="date_to">Do</label>
          <input
              type="date"
              v-model="date_to"
              name="date_from"
              class="form-control"
              :class="{ 'is-invalid': submitted && !date_to }"
          />
          <div v-show="submitted && !date_to" class="invalid-feedback">
            Data wyjazdu jest obowiazkowa
          </div>
        </div>
      </div>

      <div class="row form-group">
        <div class="col">
          <label for="guest_first_name">Imie</label>
          <input
              type="text"
              v-model="guest_first_name"
              name="guest_first_name"
              class="form-control"
              :class="{ 'is-invalid': submitted && !guest_first_name }"
          />
          <div v-show="submitted && !guest_first_name" class="invalid-feedback">
            Podaj imie gościa
          </div>
        </div>
        <div class="col">
          <label for="guest_last_name">Nazwisko</label>
          <input
              type="text"
              v-model="guest_last_name"
              name="guest_last_name"
              class="form-control"
              :class="{ 'is-invalid': submitted && !guest_last_name }"
              color="color"
          />
          <div v-show="submitted && !guest_last_name" class="invalid-feedback">
            Podaj nazwisko gościa
          </div>
        </div>
        <div class="col">
          <label for="guest_phone_number">Numer telefonu</label>
          <input
              type="text"
              v-model="guest_phone_number"
              name="guest_first_name"
              class="form-control"
              :class="{ 'is-invalid': submitted && !guest_phone_number }"
          />
          <div
              v-show="submitted && !guest_phone_number"
              class="invalid-feedback"
          >
            Podaj numer telefonu gościa
          </div>
        </div>
      </div>
      <div class="row form-group">
        <div class="col">
          <label for="guests_number">Ilosc gości</label>
          <input
              type="number"
              min="1"
              value="1"
              max="10"
              v-model="guests_number"
              name="guests_number"
              class="form-control"
              :class="{ 'is-invalid': submitted && !guests_number }"
          />
        </div>
        <div class="col">
          <label for="cottage_id">Domek</label>
          <b-form-select
              v-model="cottage_id"
              :options="options"
              class="form-control"
              :class="{ 'is-invalid': submitted && !cottage_id }"
              @change="changeColor()"
          />
          <div v-show="submitted && !cottage_id" class="invalid-feedback">
            Wybierz domek!
          </div>
        </div>
      </div>

      <div class="form-group">
        <b-form-checkbox
            id="advance_payment"
            v-model="advance_payment"
            name="advance_payment"
            switch
        >
          Opłata z góry
        </b-form-checkbox>
        <!--<label class="custom-checkbox" for="advance_payment">Advance payment?
                        <input type="checkbox" v-model="advance_payment" name="advance_payment"
                        class="custom-checkbox"
                        :class="{ 'is-invalid': submitted && !advance_payment }"/>

                    </label>-->
      </div>
      <div class="form-group">
        <label for="extra_info">Dodatkowe informacje</label>
        <textarea
            v-model="extra_info"
            name="extra_info"
            class="form-control"
        ></textarea>
      </div>

      <div class="form-group">
        <!-- SAVE - functionality suspended!
          
          <button class="btn btn-primary" :disabled="loading">
          Zapisz
        </button> -->
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
import {reservationService} from "../_services/reservation.service";

export default {
  name: "ReservationForm",
  data() {
    return {
      // childMessage: '',
      id: null,
      date_from: "",
      date_to: "",
      cottage_id: null,
      guest_first_name: "",
      guest_last_name: "",
      guest_phone_number: "",
      guests_number: 1,
      submitted: false,
      extra_info: null,
      advance_payment: false,
      returnUrl: "",
      errorNotify: "",
      loading: false,
      selected: null,
      options: [],
      selectedIndex: 0,
    };
  },
  mounted() {

    if (typeof this.editId != 'undefined' && this.editId != null) {
      this.getReservationById(this.editId);
    }

    if (
        typeof this.clickedStartDate != "undefined" &&
        this.clickedStartDate != null
    ) {
      this.date_from = this.formatClickedDate();
    }
    this.getCottages();
  },
  props: {
    editId: Number,
    clickedStartDate: String,
  },
  methods: {
    formatClickedDate() {
      // let changedDate = this.clickedStartDate.replace(/\./g, "-");
      let changedDate = this.clickedStartDate.replaceAll('/', '-');
      let parts = changedDate.split("-");
      let newDate =
          parts[2] + "-" + ("0" + parts[0]).slice(-2) + "-" + ("0" + parts[1]).slice(-2);
      return newDate;
    },

    handleSubmit() {
      this.submitted = true;
      let id = this.editId;
      const {date_from, date_to, cottage_id, guest_first_name, guest_last_name, guest_phone_number, guests_number, extra_info, advance_payment} = this;

      // stop here if form is invalid
      if (!(date_from && date_to && cottage_id) && !id) {
        return;
      }

      const data = {
        date_from,
        date_to,
        cottage_id,
        guest_first_name,
        guest_last_name,
        guest_phone_number,
        guests_number,
        extra_info,
        advance_payment,
        id
      };

      var self = this;

      reservationService.saveReservation(data).then(function () {
            self.editId = null
            self.$bvModal.hide("reservation-form-modal");
          }
      )
          .catch(function (error) {
            if (error) {
              self.errorNotify = error;
            }
          });
    },
    getCottages() {
      var self = this;
      cottageService.getCottages().then(function (response) {
            let list = [];
            response.map(function (value) {
              let text = value.name;
              let color = value.color;
              list.push({value: value.id, text: text, color: color});
            });
            self.options = list;
          }
      ).catch(function (error) {
        if (error) {
          self.errorNotify = error;
          self.loading = false;
        }
      });

    },
    getReservationById(id) {

      var self = this;
      reservationService.getReservation(id).then(function (data) {
            self.cottage_id = data.details.cottage_id;
            self.date_from = data.event.date_from;
            self.date_to = data.event.date_to;
            self.guests_number = data.details.guests_number;
            self.guest_first_name = data.details.guest_first_name;
            self.guest_last_name = data.details.guest_last_name;
            self.guest_phone_number = data.details.guest_phone_number;
            self.advance_payment = data.details.advance_payment;
            self.extra_info = data.details.extra_info;
            self.id = data.id;
          }
      )
          .catch(function (error) {
            if (error) {
              self.errorNotify = error;
            }
          });

    },
    changeColor: function () {
      var s = document.getElementById("square");
      s.style.color = this.scolor();
    },
    scolor: function () {
      if (this.cottage_id === null) {
        return "#fff";
      } else {
        let y = this.cottage_id;
        let x = this.options.findIndex((x) => x.value === y);
        return this.options[x].color;
      }
    },

  }
}
</script>


