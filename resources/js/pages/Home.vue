<template>
  <div>
    <h4 class="text-center">All users</h4>
    <br />
    <div id="app">
      <button type="button" class="btn btn-success mb-2" @click="showModal">
        Add User
      </button>

      <UserForm
        v-show="isModalVisible"
        @close="closeModal"
        @refresh="refresh"
      />
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>E-mail</th>
          <th>City</th>
          <th>State</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.city }}</td>
          <td>{{ user.state }}</td>
          <td>
            <div class="btn-group" role="group">
              <button class="btn btn-danger" @click="deleteUser(user.id)">
                Delete
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import UserForm from "../components/UserForm.vue";

export default {
  components: {
    UserForm,
  },
  data() {
    return {
      users: [],
      user: {},
      isModalVisible: false,
    };
  },
  created() {
    this.$axios.get("/sanctum/csrf-cookie").then((response) => {
      this.$axios
        .get("/api/users")
        .then((response) => {
          this.users = response.data.users;
        })
        .catch(function (error) {
          console.error(error);
        });
    });
  },
  methods: {
    deleteUser(id) {
      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .delete(`/api/users/${id}`)
          .then((response) => {
            let i = this.users.map((item) => item.id).indexOf(id); // find index of your object
            this.users.splice(i, 1);
          })
          .catch(function (error) {
            console.error(error);
          });
      });
    },
    showModal() {
      this.isModalVisible = true;
    },
    closeModal() {
      this.isModalVisible = false;
    },

    refresh() {
      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .get("/api/users")
          .then((response) => {
            this.users = response.data.users;
          })
          .catch(function (error) {
            console.error(error);
          });
      });
    },
  },
  beforeRouteEnter(to, from, next) {
    if (!window.Laravel.isLoggedin) {
      window.location.href = "/";
    }
    next();
  },
};
</script>
