<template>
  <div>
    <form @submit.prevent="handleSubmit">
      <div id="square" class="square mb-3">
        <i class="fas fa-home fa-5x"></i>
      </div>
      <div class="row form-group">
        <div class="col">
          <label for="date_from">Date from</label>
          <input
            type="date"
            v-model="date_from"
            name="date_from"
            class="form-control"
            :class="{ 'is-invalid': submitted && !date_from }"
          />
          <div v-show="submitted && !date_from" class="invalid-feedback">
            Date from is required
          </div>
        </div>

        <div class="col">
          <label for="date_to">Date to</label>
          <input
            type="date"
            v-model="date_to"
            name="date_from"
            class="form-control"
            :class="{ 'is-invalid': submitted && !date_to }"
          />
          <div v-show="submitted && !date_to" class="invalid-feedback">
            Date to is required
          </div>
        </div>
      </div>
      <div class="row form-group">
        <div class="col">
          <label for="cottage_id">Cottage</label>
          <b-form-select
            v-model="cottage_id"
            :options="options"
            class="form-control"
            :class="{ 'is-invalid': submitted && !cottage_id }"
            @change="changeColor()"
          />
          <div v-show="submitted && !cottage_id" class="invalid-feedback">
            Cottage is required
          </div>
        </div>
      </div>

      <div class="form-group">
        <button class="btn btn-primary" :disabled="loading">
          Saarch
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
import { cottageService } from "../_services/cottage.service";

export default {
  name: "CheckAvaliabilityForm",
  data() {
    return {
      // childMessage: '',
      id: null,
      date_from: "",
      date_to: "",
      cottage_id: null,
      submitted: false,
      returnUrl: "",
      errorNotify: "",
      loading: false,
      selected: null,
      options: [],
      selectedIndex: 0,
    };
  },
  mounted() {
    this.getCottages();
  },
  methods: {
    formatClickedDate() {
      let changedDate = this.clickedStartDate.replace(/\./g, "-");
      var parts = changedDate.split("-");
      let newDate =
        parts[2] + "-" + parts[1] + "-" + ("0" + parts[0]).slice(-2);
      return newDate;
    },

    handleSubmit() {},
    getCottages() {
      var self = this;
      cottageService
        .getCottages()
        .then(function(response) {
          let list = [];
          response.map(function(value) {
            let text = value.name;
            let color = value.color;
            list.push({ value: value.id, text: text, color: color });
          });
          self.options = list;
        })
        .catch(function(error) {
          if (error) {
            self.errorNotify = error;
            self.loading = false;
          }
        });
    },
    changeColor: function() {
      var s = document.getElementById("square");
      s.style.color = this.scolor();
    },
    scolor: function() {
      if (this.cottage_id === null) {
        return "#fff";
      } else {
        let y = this.cottage_id;
        let x = this.options.findIndex((x) => x.value === y);

        return this.options[x].color;
      }
    },
  },
  computed: {},
};
</script>
<style scoped>
.square {
  text-align: center;
}
</style>
