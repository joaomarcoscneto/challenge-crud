<template>
  <div class="modal-backdrop">
    <div class="modal">
      <header class="modal-header">
        <slot name="header"> Add User </slot>
        <button type="button" class="btn-close" @click="close">x</button>
      </header>

      <section class="modal-body">
        <slot name="body">
          <validation-errors :errors="validationErrors" v-if="validationErrors">
            <div v-if="validationErrors">
              <ul class="alert alert-danger">
                <li v-for="(value, index) in validationErrors" :key="index">
                  {{ value }}
                </li>
              </ul>
            </div></validation-errors
          >

          <form @submit.prevent="submit">
            <div class="form-group">
              <label>Name</label>
              <input type="text" class="form-control" v-model="user.name" />
            </div>
            <div class="form-group">
              <label>E-mail</label>
              <input
                type="email"
                class="form-control"
                v-model="user.email"
                name="email"
              />
            </div>
            <div class="form-group">
              <label>City</label>
              <input type="text" class="form-control" v-model="user.city" />
            </div>
            <div class="form-group">
              <label>State</label>
              <input
                type="text"
                class="form-control"
                v-model="user.state"
                name="state"
              />
            </div>
            <div class="form-group">
              <label>Password<small v-if="this.user.id">*</small></label>
              <input
                type="password"
                class="form-control"
                v-model="user.password"
              />
            </div>
            <div class="form-group">
              <label>Password confirmation</label>
              <input
                type="password"
                class="form-control"
                v-model="user.password_confirmation"
              />
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit</button>
          </form>
        </slot>
      </section>

      <footer class="modal-footer" v-if="this.user.id">
        <div>*Do not fill in the password if you do not want to update</div>
      </footer>
    </div>
  </div>
</template>


<script>
export default {
  name: "UserForm",
  props: {
    userUpdate: Object,
  },
  data() {
    return {
      user: this.userUpdate,
      validationErrors: "",
    };
  },
  methods: {
    close() {
      this.clear();
      this.$emit("close");
    },
    refresh() {
      this.$emit("refresh");
    },

    submit() {
      if (!(this.user.password && this.user.password_confirmation)) {
        delete this.user.password;
        delete this.user.password_confirmation;
      }
      this.user.id ? this.updateUser() : this.addUser();
    },
    updateUser() {
      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .put("/api/users/" + this.user.id, this.user)
          .then((response) => {
            this.close();
            this.refresh();
          })
          .catch((error) => {
            if (error.response.status == 422) {
              this.validationErrors = error.response.data.errors;
            }
          });
      });
    },
    addUser() {
      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .post("/api/users", this.user)
          .then((response) => {
            this.close();
            this.refresh();
          })
          .catch((error) => {
            if (error.response.status == 422) {
              this.validationErrors = error.response.data.errors;
            }
          });
      });
    },
    clear() {
      this.user = {};
    },
  },
};
</script>

<style>
.modal-backdrop {
  background-color: rgba(0, 0, 0, 0.3);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal {
  background: #ffffff;
  box-shadow: 2px 2px 20px 1px;
  display: flex;
  flex-direction: column;
  position: relative;
  margin-left: auto;
  margin-right: auto;
  max-width: 500px;
  max-height: 500px;
}

.modal-header,
.modal-footer {
  padding: 15px;
  display: flex;
}

.modal-header {
  position: relative;
  border-bottom: 1px solid #eeeeee;
  color: #4aae9b;
  justify-content: space-between;
}

.modal-footer {
  border-top: 1px solid #eeeeee;
  flex-direction: column;
  justify-content: flex-end;
}

.modal-body {
  position: relative;
  padding: 20px 10px;
}

.btn-close {
  position: absolute;
  top: 0;
  right: 0;
  border: none;
  font-size: 20px;
  padding: 10px;
  cursor: pointer;
  font-weight: bold;
  color: #4aae9b;
  background: transparent;
}

.btn-green {
  color: white;
  background: #4aae9b;
  border: 1px solid #4aae9b;
  border-radius: 2px;
}
</style>

