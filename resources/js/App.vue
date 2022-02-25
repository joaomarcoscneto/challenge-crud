<template>
  <div class="card">
    <div class="card-header">E-Commerce Brasil</div>

    <div class="card-body">
      <example-component></example-component>
    </div>
  </div>
</template>

<script>
import ExampleComponent from "./components/ExampleComponent.vue";

export default {
  components: { ExampleComponent },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
