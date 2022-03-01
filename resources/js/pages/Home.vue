<template>
  <div>
    <h4 class="text-center">All users</h4>
    <br />
    <div id="app">
      <button type="button" class="btn btn-success mb-2" @click="showModal">
        Add User
      </button>

      <UserForm
        v-if="openForm"
        @close="closeModal"
        @refresh="refresh"
        :userUpdate="user"
      />

      <SendEmail
        v-if="openSendEmail"
        @close="closeModal"
        @refresh="refresh"
        :userSendEmail="user"
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
              <button class="btn btn-info" @click="sendEmailUser(user)">
                Send e-mail
              </button>
              <button class="btn btn-warning" @click="updateUser(user)">
                Update
              </button>
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
import SendEmail from "../components/SendEmail.vue";
export default {
  components: {
    UserForm,
    SendEmail,
  },
  data() {
    return {
      users: [],
      user: {},
      openForm: false,
      openSendEmail: false,
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
      if (confirm("Do you really want to delete?")) {
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
      }
    },
    updateUser: async function (user) {
      this.user = user;
      this.showModal();
    },

    sendEmailUser: async function (user) {
      this.user = user;
      this.showSendEmail();
    },
    showModal() {
      this.openForm = true;
    },

    showSendEmail() {
      this.openSendEmail = true;
    },
    closeModal() {
      this.user = {};
      this.openForm = false;
      this.openSendEmail = false;
    },

    refresh() {
      window.location.reload();
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
