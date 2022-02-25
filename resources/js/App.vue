<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">CRUD Users</div>

          <div class="card-body">
            <input
              v-model="form.email"
              type="text"
              placeholder="Email"
              class="form-control"
              :class="{ 'is-invalid': form.errors.has('email') }"
              name="email"
            />

            <input
              v-model="form.password"
              type="text"
              placeholder="password"
              class="form-control"
              :class="{ 'is-invalid': form.errors.has('password') }"
              name="password"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
