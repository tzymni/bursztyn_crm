<template>
  <div>
{{title}} dnia {{date}}
    <br/>
    Lista domków do sprzątania:

  </div>
</template>

<script>
import {cleaningEventServices} from "@/_services/cleaning_event_service";

export default {
  name: "CleaningForm",
  data() {
    return {
      // childMessage: '',
      id: null,
      title: "",
      date: "",
    };
  },
  mounted() {
    if (typeof this.editId != 'undefined' && this.editId != null) {
      this.getCleaningEvent(this.editId);
    }
  },
  props: {
    editId: Number,
  },
  methods: {
    getCleaningEvent(id) {

      var self = this;
      cleaningEventServices.getCleaningEvent(id).then(function (data) {

            self.title= data.name;
            self.date = data.date;
            self.id = data.id;

          }
      )
          .catch(function (error) {
            if (error) {
              self.errorNotify = error;
            }
          });

    },

  }
}
</script>

<style scoped>

</style>