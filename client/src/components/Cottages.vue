<template>
  <div class="cottages">
    <h1>
      <font-awesome-icon icon="home"/>
      || {{ header }}
    </h1>

    <div class="container">
      <div>
        <b-button class="btn btn-info" id="show-modal" @click="showModal()"
        >Nowy domek
        </b-button
        >
        <b-modal
            @hide="setCottages()"
            id="cottage-form-modal"
            title="Domek"
            hide-footer
        >
          <CottageForm :editId="$data.editId" v-on:childToParent="showModal"/>
        </b-modal>
      </div>
      <div class="col extra-info" style="display: none">
        <h3 id="desc-title">{{ selected_title }}</h3>
        <p id="extra-info" class="description">{{ selected_info }}</p>
      </div>
      <div class="row">
        <div class="table-wrap col">
          <b-table
              id="Cottages"
              ref="cottages"
              :per-page="perPage"
              :current-page="currentPage"
              selectable
              :select-mode="selectMode"
              @row-selected="onRowSelected"
              small
              class="table-cottages text-uppercase"
              :fields="fields"
              :items="cottages"
              thead-class="thead-dark"
          >
            <template v-slot:head(id)="data">
              <p class="hide">{{ data.field.id }}</p>
            </template>
            <template v-slot:cell(id)="data">
              <p class="hide">{{ data.item.id }}</p>
              <p class="text-center">
                <font-awesome-icon class="icon" icon="home"/>
              </p>
            </template>
            <template v-slot:cell(color)="data">
              <p class="hide">{{ data.item.color }}</p>
              <v-btn
                  inactive
                  block
                  small
                  tile
                  depressed
                  :color="data.item.color"
                  class="color-rectangle"
              ></v-btn>
            </template>
            <template v-slot:cell(extra_info)="data">
              <p class>{{ typeof data.item.extra_info == 'string' ? data.item.extra_info.substr(0, 50) : '' }}</p>
            </template>
            <template v-slot:head(name)="data">
              <p class="hide">{{ data.field.name }}</p>
              <p>Nazwa</p>
            </template>
            <template v-slot:head(color)="data">
              <p class="hide">{{ data.field.color }}</p>
              <p>Kolor</p>
            </template>
            <template v-slot:head(extra_info)="data">
              <p class="hide">{{ data.field.extra_info }}</p>
              <p>Dodatkowe Informacje</p>
            </template>
            <template v-slot:head(max_guests_number)="data">
              <p class="hide">{{ data.field.max_guests_number }}</p>
              <p>Pojemność</p>
            </template>
            <template v-slot:head(is_active)="data">
              <p class="hide">{{ data.field.is_active }}</p>
              <p>Opcje</p>
            </template>
            <template v-slot:cell(is_active)="data">
              <p class="hide">{{ data.item.is_active }}</p>
              <a @click="deleteCottage(data.item.id)" class="btn btn-danger">
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
              aria-controls="Cottages"
          ></b-pagination>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import CottageForm from "./CottageForm";
import {cottageService} from "../_services/cottage.service";

export default {
  name: "Cottages",
  components: {CottageForm},
  data: function () {
    return {
      header: "Domki",
      cottages: [],
      perPage: 10,
      currentPage: 1,
      fields: [],
      selectMode: "single",
      selected: [
        {name: "Extra Information", extra_info: "About your cottage..."},
      ],
      selected_title: "Extra Information",
      selected_info: "About your cottage...",
      editId: null,
    };
  },
  mounted() {
    this.setCottages()
  },
  methods: {
    showModal(id = null) {
      this.editId = id
      this.$bvModal.show("cottage-form-modal")
    },
    setCottages() {
      var self = this;
      cottageService
          .getCottages()
          .then(function (response) {
            self.cottages = response
          })
          .catch(function (error) {
            if (error) {
              self.errorNotify = error
              self.loading = false
            }
          });
    },
    deleteCottage(id) {
      this.$confirm(
          "Czy na pewno chcesz usunąć domek?",
          "Usuń",
          "error"
      ).then(() => {
        var self = this;
        cottageService
            .deleteCottage(id)
            .then(function () {
              self.setCottages()
            })
            .catch(function (error) {
              if (error) {
                self.errorNotify = error
                self.loading = false
              }
            });
      });
    },
    onRowSelected(items) {
      this.selected = items
      this.displaySelected()
    },
    displaySelected: function () {
      let x = document.getElementById("desc-title")
      let y = document.getElementById("extra-info")

      if (this.selected.length === 0) {
        x.innerHTML = "Extra information"
        y.innerHTML = "About your cottage..."
      } else {
        x.innerHTML = this.selected[0].name
        y.innerHTML = this.selected[0].extra_info
      }
    },
  },
  computed: {
    rows() {
      return this.cottages.length;
    },
  },
};
export {CottageForm};
</script>
<style scoped>
.cottages {
  width: 100%;
  height: 100%;
  max-width: 1300px;
}

.table-wrap {
  margin-top: 1rem;
}

.table-cottages {
  text-align: left;
}

.table-btn {
  width: 15px;
  height: 15px;
}

.extra-info {
  font-size: 1.2em;
}

.hide {
  width: 0px;
  height: 0px;
  padding: 0;
  margin: 0;
  display: none;
}

.icon {
  width: 20px;
  height: 20px;
  padding: 0px;
  margin: 0 auto;
}

.color-rectangle {
  padding: 0;
  margin: 0;
  width: 100%;
  max-width: 1em !important;
  height: 1em;
  cursor: default !important;
}
</style>
