<template>
  <div>
    <div class="calendar-controls">
      <b-button
          class="btn btn-info"
          href="#/"
      >Kalendarz szczegółowy
      </b-button
      >

      <div>
        <b-form-group id="mySelect" label="Wybierz typ zdarzenia" label-for="mySelect">
          <b-form-select id="mySelect" @change="filterCalendarEvents({ currentValue})" v-model="form.option"
                         :options="eventTypes"/>
        </b-form-group>
      </div>

    </div>
    <div class="toolbox">
      <!--      <button @click="updateFirstRow">Update first row</button>-->
      <!--      <button @click="changeZoomLevel">Change zoom level</button>-->
    </div>
    <div class="gstc-wrapper" ref="gstc"></div>
  </div>
</template>

<script>
import GSTC from 'gantt-schedule-timeline-calendar';
import {Plugin as TimelinePointer} from 'gantt-schedule-timeline-calendar/dist/plugins/timeline-pointer.esm.min.js';
import {Plugin as Selection} from 'gantt-schedule-timeline-calendar/dist/plugins/selection.esm.min.js';
import {Plugin as ItemResizing} from 'gantt-schedule-timeline-calendar/dist/plugins/item-resizing.esm.min.js';
// import {Plugin as ItemMovement} from 'gantt-schedule-timeline-calendar/dist/plugins/item-movement.esm.min.js';
import {Plugin as HighlightWeekends} from 'gantt-schedule-timeline-calendar/dist/plugins/highlight-weekends.esm.min';
import 'gantt-schedule-timeline-calendar/dist/style.css';
import {cottageService} from "@/_services/cottage.service";
import {reservationService} from "@/_services/reservation.service";

let gstc, state;


// main component
export default {
  name: 'TimelineCalendar',
  data: function () {
    return {
      state: null,
      cottages: {},
      form: {
        option: 'RESERVATION',
      },
      eventTypes: [
        {text: 'Tylko rezerwacje', value: 'RESERVATION'},
        {text: 'Tylko zmiany', value: 'CLEANING'}
      ],
      config: {

        licenseKey: '====BEGIN LICENSE KEY====\nXOfH/lnVASM6et4Co473t9jPIvhmQ/l0X3Ewog30VudX6GVkOB0n3oDx42NtADJ8HjYrhfXKSNu5EMRb5KzCLvMt/pu7xugjbvpyI1glE7Ha6E5VZwRpb4AC8T1KBF67FKAgaI7YFeOtPFROSCKrW5la38jbE5fo+q2N6wAfEti8la2ie6/7U2V+SdJPqkm/mLY/JBHdvDHoUduwe4zgqBUYLTNUgX6aKdlhpZPuHfj2SMeB/tcTJfH48rN1mgGkNkAT9ovROwI7ReLrdlHrHmJ1UwZZnAfxAC3ftIjgTEHsd/f+JrjW6t+kL6Ef1tT1eQ2DPFLJlhluTD91AsZMUg==||U2FsdGVkX1/SWWqU9YmxtM0T6Nm5mClKwqTaoF9wgZd9rNw2xs4hnY8Ilv8DZtFyNt92xym3eB6WA605N5llLm0D68EQtU9ci1rTEDopZ1ODzcqtTVSoFEloNPFSfW6LTIC9+2LSVBeeHXoLEQiLYHWihHu10Xll3KsH9iBObDACDm1PT7IV4uWvNpNeuKJc\npY3C5SG+3sHRX1aeMnHlKLhaIsOdw2IexjvMqocVpfRpX4wnsabNA0VJ3k95zUPS3vTtSegeDhwbl6j+/FZcGk9i+gAy6LuetlKuARjPYn2LH5Be3Ah+ggSBPlxf3JW9rtWNdUoFByHTcFlhzlU9HnpnBUrgcVMhCQ7SAjN9h2NMGmCr10Rn4OE0WtelNqYVig7KmENaPvFT+k2I0cYZ4KWwxxsQNKbjEAxJxrzK4HkaczCvyQbzj4Ppxx/0q+Cns44OeyWcwYD/vSaJm4Kptwpr+L4y5BoSO/WeqhSUQQ85nvOhtE0pSH/ZXYo3pqjPdQRfNm6NFeBl2lwTmZUEuw==\n====END LICENSE KEY====',
        plugins: [TimelinePointer(), Selection(), ItemResizing(), HighlightWeekends({
          weekdays: [6, 0],
          className: "test-weekend"
        })],
        list: {
          columns: {
            data: {
              [GSTC.api.GSTCID('label')]: {
                id: GSTC.api.GSTCID('label'),
                width: 200,
                data: 'label',
                header: {
                  content: 'Label'
                }
              }
            }
          },
          rows: this.generateRows(),
          weekdays: [2, 3],
        },
        chart: {
          items: this.generateItems(),
          time: {
            zoom: 21
          }
        }
      }
    };
  },
  mounted() {
    this.setCottages()
    this.setReservations()
    /**
     * @type { import("gantt-schedule-timeline-calendar").Config }
     */
    state = GSTC.api.stateFromConfig(this.config);
    gstc = GSTC({
      element: this.$refs.gstc,
      state,
    });
  },
  beforeUnmount() {
    if (gstc) gstc.destroy();
  },
  methods: {
    updateFirstRow() {
      state.update(`config.list.rows.${GSTC.api.GSTCID('0')}`, row => {
        row.label = 'Changed dynamically';
        return row;
      });
    },
    filterCalendarEvents(selected) {
      let type = selected.currentValue.value
      console.log(type)
      if (type == 'RESERVATION') {
        this.setReservations()
      } else {
        this.setCottages()
      }

      // this.setEvents(type)
    },
    generateRows() {

      const rows = {};
      for (let i = 0; i < 10; i++) {
        const id = GSTC.api.GSTCID(i.toString());
        rows[id] = {
          id,
          label: `Row ${i}`
        }
      }
      return rows;
    },

    generateItems() {

      const items = {};
      let start = GSTC.api.date().startOf('day').subtract(6, 'day');
      for (let i = 0; i < 200; i++) {
        const id = GSTC.api.GSTCID(i.toString());
        const rowId = GSTC.api.GSTCID((Math.floor(10 * 10)).toString());
        start = start.add(1, 'day');
        items[id] = {
          id,
          label: `Rezerwacja ${i}`,
          rowId,
          time: {
            start: start.valueOf(),
            end: start.add(1, 'day').endOf('day').valueOf()
          }
        }

      }
      return items;

    },
    setReservations() {
      var self = this;
      reservationService.getEvents('RESERVATION').then(function (response) {

            let list = []
            const itemsNew = {}

            response.map(function (value) {

              let id = GSTC.api.GSTCID(value.id);
              let rowId = GSTC.api.GSTCID(value.cottage_id);

              itemsNew[id] = {
                id,
                label: value.title,
                rowId,
                time: {
                  start: value.date_from * 1000,
                  end: value.date_to * 1000
                },
                style: {background: value.color},
              }

            });

            state.update('config.chart.items', itemsNew)
            self.items = list
          }
      ).catch(function (error) {
        if (error) {
          self.errorNotify = error;
          self.loading = false;
        }
      });
    }, setCleaningEvents() {
      var self = this;
      reservationService.getEvents('RESERVATION').then(function (response) {

            let list = []
            const itemsNew = {}

            response.map(function (value) {

              let id = GSTC.api.GSTCID(value.id);
              let rowId = GSTC.api.GSTCID(value.cottage_id);

              itemsNew[id] = {
                id,
                label: value.title,
                rowId,
                time: {
                  start: value.date_from * 1000,
                  end: value.date_to * 1000
                },
                style: {background: value.color},
              }

            });

            state.update('config.chart.items', itemsNew)
            self.items = list
          }
      ).catch(function (error) {
        if (error) {
          self.errorNotify = error;
          self.loading = false;
        }
      });
    },
    setCottages() {
      var self = this;
      cottageService
          .getCottages()
          .then(function (response) {

            const rows = {};

            response.map(function (cottage) {

              const id = GSTC.api.GSTCID(cottage.id);
              rows[id] = {
                id: cottage.id,
                label: cottage.name
              }

            });

            state.update('config.list.rows', rows)
          })

          .catch(function (error) {
            if (error) {
              self.errorNotify = error;
              self.loading = false;
            }
          });
    },
  },
  computed: {
    currentValue() {
      return this.eventTypes.find(option => option.value === this.form.option)
    },
  }
};
</script>
<style>
.test-weekend {
  background: #cedde2;
}

.gstc-component {
  margin: 0;
  padding: 0;
}

.toolbox {
  padding: 10px;
}
</style>