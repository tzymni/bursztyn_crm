<template>
  <div>
{{editId}}
  </div>
</template>

<script>
export default {
  name: "CleaningForm",
  data() {
    return {
      // childMessage: '',
      id: null,
    };
  },
  props: {
    editId: Number,
  },
}
</script>

<style scoped>

</style>