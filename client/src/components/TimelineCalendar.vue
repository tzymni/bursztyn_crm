<template>
  <div>
    <h1>
      <font-awesome-icon icon="calendar-alt"/>
      || {{ header }}
    </h1>
    <div class="calendar-controls">
      <b-modal
          id="reservation-form-modal"
          title="Reservation form"
          hide-footer
      >
        <ReservationForm
            :editId="$data.editId"
        />
      </b-modal>

      <b-modal
          id="cleaning-form-modal"
          title="Sprzątanie domków"
          hide-footer
      >
        <CleaningForm
            :editId="$data.editId"
        />
      </b-modal>

      <b-button
          class="btn btn-info"
          href="#/"
      >Kalendarz szczegółowy
      </b-button
      >
      <b-button
          class="btn btn-info"
          id="check-avaliability"
          @click="checkAvailabilityFormModal()"
      >Sprawdź dostępność
      </b-button
      >
      <b-modal
          id="check-form-modal"
          title="Sprawdź dostępność"
          hide-footer
      >
        <CheckAvaliabilityForm/>
      </b-modal>
      <div>
        <b-form-group id="mySelect" label="Wybierz typ zdarzenia" label-for="mySelect">
          <b-form-select id="mySelect" @change="filterCalendarEvents({ currentValue})" v-model="form.option"
                         :options="eventTypes"/>
        </b-form-group>
      </div>

    </div>
    <div class="gstc-wrapper" ref="gstc"></div>
  </div>
</template>

<script>
import GSTC from 'gantt-schedule-timeline-calendar';
import {Plugin as TimelinePointer} from 'gantt-schedule-timeline-calendar/dist/plugins/timeline-pointer.esm.min.js';
import {Plugin as Selection} from 'gantt-schedule-timeline-calendar/dist/plugins/selection.esm.min.js';
import {Plugin as HighlightWeekends} from 'gantt-schedule-timeline-calendar/dist/plugins/highlight-weekends.esm.min';
import 'gantt-schedule-timeline-calendar/dist/style.css';
import {cottageService} from "@/_services/cottage.service";
import {reservationService} from "@/_services/reservation.service";
import {cleaningEventServices} from "@/_services/cleaning_event.service";
import ReservationForm from "@/components/ReservationForm";
import CleaningForm from "@/components/CleaningForm";
import CheckAvaliabilityForm from "./CheckAvailabilityForm";
import {config} from "@/config";

let gstc, state;

// main component
export default {
  name: 'TimelineCalendar',
  components: {
    ReservationForm,
    CleaningForm,
    CheckAvaliabilityForm
  },
  data: function () {
    return {
      header: "Kalendarz liniowy",
      state: null,
      cottages: {},
      form: {
        option: config.event.reservationType,
      },
      eventTypes: [
        {text: 'Tylko rezerwacje', value: config.event.reservationType},
        {text: 'Tylko zmiany', value: config.event.cleaningType},
        {text: 'Tylko obecności', value: config.event.userPresencesType}

      ],
      editId: null,
      config: {
        actions: {
          'chart-timeline-items-row-item': [this.clickAction],
        },
        licenseKey: config.ganttScheduleTimelineCalendarKey,
        plugins: [TimelinePointer(), Selection(), HighlightWeekends({
          weekdays: [6, 0],
          className: "highlight-weekend"
        })],
        locale: {
          name: 'pl',
          weekdays: 'Niedziela_Poniedziałek_Wtorek_Środa_Czwartek_Piątek_Sobota'.split(
              '_'
          ),
          weekdaysShort: 'Niedz_Pon_Wt_Śr_Czw_Piąt_Sob'.split('_'),
          weekdaysMin: 'Ni_Po_Wt_Śr_Cz_Pi_So'.split('_'),
          months: 'Styczeń_Luty_Marzec_Kwiecień_Maj_Czerwiec_Lipiec_Sierpień_Wrzesień_Październik_Listopad_Grudzień'.split(
              '_'
          ),
          monthsShort: 'Sty_Lut_Mar_Kwi_Maj_Cze_Lip_Sie_Wrz_Paź_Lis_Gru'.split('_'),
          weekStart: 1,
          relativeTime: {
            future: 'w %s',
            past: '%s temu',
            s: 'a kilka sekund',
            m: 'minuta',
            mm: '%d minut',
            h: 'godzina',
            hh: '%d godziny',
            d: 'dzień',
            dd: '%d dni',
            M: 'miesiąc',
            MM: '%d miesiące',
            y: 'rok',
            yy: '%d lata',
          },
          formats: {
            LT: 'HH:mm',
            LTS: 'HH:mm:ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY HH:mm',
            LLLL: 'dddd, D MMMM YYYY HH:mm',
          },
        },
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
            zoom: 22
          }
        }
      }
    };
  },
  mounted() {
    this.editId = null
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
    clickAction(element, data) {

      const self = this;

      function showModal() {

        self.editId = data.item.event_id
        if (data.item.type === config.event.reservationType) {
          self.$bvModal.show("reservation-form-modal")
        } else {
          self.$bvModal.show("cleaning-form-modal")
        }
      }

      element.addEventListener('click', showModal);
      return {
        update(element, newData) {
          data = newData; // data from parent scope updated
        },

        destroy(element) {
          element.removeEventListener('click', showModal);
        },
      };

    },
    updateFirstRow() {
      state.update(`config.list.rows.${GSTC.api.GSTCID('0')}`, row => {
        row.label = 'Changed dynamically';
        return row;
      });
    },
    filterCalendarEvents(selected) {
      let type = selected.currentValue.value

      if (type === config.event.reservationType) {
        this.setReservations()
      } else {
        this.setCleaningEvents()
      }
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
      const self = this;
      reservationService.getEvents(config.event.reservationType).then(function (response) {

            let list = []
            const itemsNew = {}

            response.map(function (value) {

              let id = GSTC.api.GSTCID(value.id);
              let rowId = GSTC.api.GSTCID(value.cottage_id);

              itemsNew[id] = {
                id,
                event_id: value.id,
                label: value.title,
                rowId,
                time: {
                  start: value.date_from * 1000,
                  end: value.date_to * 1000
                },
                type: value.type,
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
      const self = this
      cleaningEventServices.getAllCleaningEvents().then(function (response) {

            let list = []
            const itemsNew = {}

            response.map(function (value) {

              let id = GSTC.api.GSTCID(value.id);
              let rowId = GSTC.api.GSTCID(value.cottage_id);

              itemsNew[id] = {
                id,
                type: value.type,
                label: value.title,
                event_id: value.event_id,
                rowId,
                time: {
                  start: value.date_from * 1000,
                  end: value.date_to * 1000
                },
                style: {background: value.cottage_color},
              }

            });

            state.update('config.chart.items', itemsNew)
            self.items = list
          }
      ).catch(function (error) {
        if (error) {
          self.errorNotify = error
          self.loading = false
        }
      });
    },
    setCottages() {
      const self = this;
      cottageService
          .getCottages()
          .then(function (response) {

            const rows = {}

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
              self.errorNotify = error
              self.loading = false
            }
          });
    },
    checkAvailabilityFormModal() {
      this.$bvModal.show("check-form-modal")
    }
  },
  computed: {
    currentValue() {
      return this.eventTypes.find(option => option.value === this.form.option)
    },
  }
};
</script>
<style>
.highlight-weekend {
  background: #cedde2;
}

.toolbox {
  padding: 10px;
}
</style>