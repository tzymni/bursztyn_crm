<template>
  <div>
    <div id="calendar">
      <div class="calendar-controls">
        <!--        <b-button-->
        <!--            class="btn btn-info"-->
        <!--            id="show-reservation-form-modal"-->
        <!--            @click="showReservationFormModal()"-->
        <!--        >-->
        <!--          Add reservation-->
        <!--        </b-button>-->
        <b-modal
            @hide="setEvents()"
            id="reservation-form-modal"
            title="Reservation form"
            hide-footer
        >
          <ReservationForm
              :clickedStartDate="$data.clickedStartDate"
              :editId="$data.editId"
          />
        </b-modal>
        <b-modal
            @hide="setEvents()"
            id="cleaning-form-modal"
            title="Sprzątanie domków"
            hide-footer
        >
          <CleaningForm
              :clickedStartDate="$data.clickedStartDate"
              :editId="$data.editId"
          />
        </b-modal>

        <b-button
            class="btn btn-info"
            id="check-avaliability"
            @click="checkAvaliabilityFormModal()"
        >Sprawdź dostępność
        </b-button
        >

        <div>
          <b-form-group id="mySelect" label="Wybierz typ zdarzenia" label-for="mySelect">
            <b-form-select id="mySelect" @change="filterCalendarEvents({ currentValue})" v-model="form.option"
                           :options="eventTypes"/>
          </b-form-group>
        </div>


        <b-modal
            @hide="setEvents()"
            id="check-form-modal"
            title="Sprawdź dostępność"
            hide-footer
        >
          <CheckAvaliabilityForm/>
        </b-modal>
      </div>

      <div id="trial-of-options" style="display: none">
        <div v-if="message" class="notification is-success">{{ message }}</div>

        <div class="box">
          <h4 class="title is-5">Play with the options!</h4>


          <div class="field">
            <label class="label">Period Count</label>
            <div class="control">
              <div class="select">
                <select v-model="displayPeriodCount">
                  <option :value="1">1</option>
                  <option :value="2">2</option>
                  <option :value="3">3</option>
                </select>
              </div>
            </div>
          </div>

          <div class="field">
            <label class="label">Starting day of the week</label>
            <div class="control">
              <div class="select">
                <select v-model="startingDayOfWeek">
                  <option
                      v-for="(d, index) in dayNames"
                      :key="index"
                      :value="index"
                  >
                    {{ d }}
                  </option>
                </select>
              </div>
            </div>
          </div>

          <div class="field">
            <label class="label">Today Button</label>
            <label class="checkbox">
              <input v-model="useTodayIcons" type="checkbox"/>
              Icons
            </label>
          </div>

          <div class="field">
            <label class="label">Themes</label>
            <label class="checkbox">
              <input v-model="useDefaultTheme" type="checkbox"/>
              Default
            </label>
          </div>

          <div class="field">
            <label class="checkbox">
              <input v-model="useHolidayTheme" type="checkbox"/>
              Holidays
            </label>
          </div>
        </div>

        <div class="box">
          <div class="field">
            <label class="label">Title</label>
            <div class="control">
              <input v-model="newItemTitle" class="input" type="text"/>
            </div>
          </div>

          <div class="field">
            <label class="label">Start date</label>
            <div class="control">
              <input v-model="newItemStartDate" class="input" type="date"/>
            </div>
          </div>

          <div class="field">
            <label class="label">End date</label>
            <div class="control">
              <input v-model="newItemEndDate" class="input" type="date"/>
            </div>
          </div>

          <button class="button is-info" @click="clickTestAddItem">
            Add Item
          </button>
        </div>
      </div>

      <div class="calendar-parent">
        <calendar-view
            :events="items"
            :show-date="showDate"
            :time-format-options="{ hour: 'numeric', minute: '2-digit' }"
            :enable-drag-drop="false"
            :disable-past="disablePast"
            :disable-future="disableFuture"
            :display-period-uom="displayPeriodUom"
            :display-period-count="displayPeriodCount"
            :starting-day-of-week="startingDayOfWeek"
            :class="themeClasses"
            :period-changed-callback="periodChanged"
            :current-period-label="useTodayIcons ? 'icons' : ''"
            @drop-on-date="onDrop"
            @click-date="onClickDay"
            @click-event="onClickItem"
        >
          <calendar-view-header
              slot="header"
              slot-scope="{ headerProps }"
              :header-props="headerProps"
              @input="setShowDate"
          />
        </calendar-view>
      </div>
    </div>
  </div>
</template>
<script>
// Load CSS from the published version
import ReservationForm from "./ReservationForm";
import CleaningForm from "./CleaningForm.vue";
import {reservationService} from "../_services/reservation.service";
import CheckAvaliabilityForm from "./CheckAvaliabilityForm";

require("vue-simple-calendar/static/css/default.css")
require("vue-simple-calendar/static/css/holidays-us.css")

import {
  CalendarView,
  CalendarViewHeader,
  CalendarMathMixin,
} from "vue-simple-calendar"

export default {
  name: "Calendar",
  components: {
    ReservationForm,
    CleaningForm,
    CalendarView,
    CalendarViewHeader,
    CheckAvaliabilityForm,
  },
  mixins: [CalendarMathMixin],
  data() {
    return {
      /* Show the current month, and give it some fake items to show */
      showDate: this.thisMonth(1),
      message: "",
      startingDayOfWeek: 1,
      disablePast: false,
      disableFuture: false,
      displayPeriodUom: "month",
      displayPeriodCount: 1,
      showEventTimes: true,
      newItemTitle: "",
      newItemStartDate: "",
      newItemEndDate: "",
      useDefaultTheme: true,
      useHolidayTheme: true,
      useTodayIcons: true,
      editId: null,
      form: {
        option: 'ALL',
      },
      eventTypes: [
        {text: 'Wszystkie', value: 'ALL'},
        {text: 'Tylko rezerwacje', value: 'RESERVATION'},
        {text: 'Tylko zmiany', value: 'CLEANING'}
      ],
      items: [
        // {
        //   id: "e4",
        //   startDate: this.thisMonth(20),
        //   title: "My Birthday!",
        //   classes: "birthday",
        //   url: "https://en.wikipedia.org/wiki/Birthday",
        // }
      ],
      clickedStartDate: null,
    }
  },
  computed: {
    currentValue() {
      return this.eventTypes.find(option => option.value === this.form.option)
    },
    userLocale() {
      return this.getDefaultBrowserLocale
    },
    dayNames() {
      return this.getFormattedWeekdayNames(this.userLocale, "long", 0)
    },
    themeClasses() {
      return {
        "theme-default": this.useDefaultTheme,
        "holiday-us-traditional": this.useHolidayTheme,
        "holiday-us-official": this.useHolidayTheme,
      }
    },
  },
  mounted() {
    this.newItemStartDate = this.isoYearMonthDay(this.today())
    this.newItemEndDate = this.isoYearMonthDay(this.today())
    this.setEvents()
    this.editId = null
    this.clickedStartDate = null
  },
  methods: {
    showReservationFormModal() {
      this.$bvModal.show("reservation-form-modal");
    },
    checkAvaliabilityFormModal() {
      this.$bvModal.show("check-form-modal");
    },
    filterCalendarEvents(selected) {
      let type = selected.currentValue.value
      this.setEvents(type)
    },
    setEvents(type) {
      this.clickedStartDate = null;
      var self = this;
      reservationService.getEvents(type).then(function (response) {

            let list = [];
            response.map(function (value) {

              let newEvent = {
                id: value.id,
                startDate: new Date(value.date_from * 1000),
                endDate: new Date(value.date_to * 1000),
                title: value.title,
                style: 'background-color: ' + value.color,
                type: value.type
              }
              list.push(newEvent)
            });

            self.items = list
          }
      ).catch(function (error) {
        if (error) {
          self.errorNotify = error;
          self.loading = false;
        }
      });
    },
    periodChanged() {
      // range, eventSource) {
      // Demo does nothing with this information, just including the method to demonstrate how
      // you can listen for changes to the displayed range and react to them (by loading items, etc.)
      //console.log(eventSource)
      //console.log(range)
    },
    thisMonth(d, h, m) {
      const t = new Date()
      return new Date(t.getFullYear(), t.getMonth(), d, h || 0, m || 0)
    },
    onClickDay(d) {
      this.editId = null
      this.clickedStartDate = d.toLocaleDateString()
      this.$bvModal.show("reservation-form-modal")
    },
    onClickItem(e) {

      this.editId = e.id
      if (e.originalEvent.type == 'RESERVATION') {
        this.$bvModal.show("reservation-form-modal")

      } else {
        this.$bvModal.show("cleaning-form-modal")

      }
    },
    setShowDate(d) {
      this.message = `Changing calendar view to ${d.toLocaleDateString()}`
      this.showDate = d
    },
    onDrop(item, date) {
      console.log("DROPPED")
      this.message = `You dropped ${item.id} on ${date.toLocaleDateString()}`
      // Determine the delta between the old start date and the date chosen,
      // and apply that delta to both the start and end date to move the item.
      const eLength = this.dayDiff(item.startDate, date)
      item.originalEvent.startDate = this.addDays(item.startDate, eLength)
      item.originalEvent.endDate = this.addDays(item.endDate, eLength)
    },
    clickTestAddItem() {
      this.items.push({
        startDate: this.newItemStartDate,
        endDate: this.newItemEndDate,
        title: this.newItemTitle,
        id:
            "e" +
            Math.random()
                .toString(36)
                .substr(2, 10),
      })
      this.message = "You added a calendar item!"
    },
  },
}
</script>

<style>
html,
body {
  height: 100%;
  margin: 0;
  background-color: #f7fcff;
}

.cv-event {
  height: 18%;
  padding: 2px;
}

.dow6 {
  background-color: #fefec8 !important;
}


.dow0 {
  background-color: #ffb2ae !important;
}

#calendar {
  display: flex;
  flex-direction: column;
  font-family: Calibri, sans-serif;
  width: 95vw;
  min-width: 30rem;
  max-width: 100%;
  min-height: 55rem;
  margin-left: auto;
  margin-right: auto;
}

.calendar-controls {
  display: flex;
}

.calendar-controls .btn {
  margin: 0 1rem 1rem 0;
}

.calendar-parent {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
  overflow-x: hidden;
  overflow-y: hidden;
  max-height: 80vh;
  background-color: white;
}

/* For long calendars, ensure each week gets sufficient height. The body of the calendar will scroll if needed */
.cv-wrapper.period-month.periodCount-2 .cv-week,
.cv-wrapper.period-month.periodCount-3 .cv-week,
.cv-wrapper.period-year .cv-week {
  min-height: 6rem;
}

/* These styles are optional, to illustrate the flexbility of styling the calendar purely with CSS. */
/* Add some styling for items tagged with the "birthday" class */
.theme-default .cv-event.birthday {
  background-color: #e0f0e0;
  border-color: #d7e7d7;
}

.theme-default .cv-event.birthday::before {
  content: "\1F382"; /* Birthday cake */
  margin-right: 0.5em;
}
</style>