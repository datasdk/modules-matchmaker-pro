<template>
  <section>

    <Loading v-if="loading" />

    <div v-else-if="users">


      <div v-if="users.length">
        
        <select v-model="input" id="user-select" class="form-control">
          <option value="0">Vælg kontaktperson</option>
          <option v-for="user in users" :key="user.id" :value="user.id">
            {{ user.first_name }} {{ user.last_name }}
          </option>
        </select>

      </div>

    </div>

  </section>
</template>

<script>
export default {
  props: {
    company_id: { required: true },
    value: {
      required: true
    }
  },

  data() {
    return {
      id: null,
      users: [],
      selectedUser: '',
      input: this.value,
      loading: true
    };
  },

  watch: {
    // Watcher for changes in company_id
    company_id(newCompanyId, oldCompanyId) {
      if (newCompanyId !== oldCompanyId) {
        this.fetchUsers(); // Fetch users again if company_id changes
      }
    },

    // Watcher for changes in input
    input(newInput, oldInput) {
      
      return this.$emit("input",newInput)
      
    }
  },

  methods: {
    async fetchUsers() {
      this.loading = true;
      try {
        const response = await axios.get(route("api.companies.companies.show", this.company_id), { params: { include: 'owners' } });
        let users = this.users = response.data.data.owners;

        if(users.length == 1){

          this.input = users[0]?.id

        }


      } catch (error) {
        console.error('Error fetching users:', error);
      } finally {
        this.loading = false;
      }
    }

  },

  created() {
    // Use created lifecycle hook to access $auth.user safely
    if (this.$auth && this.$auth.user) {
      this.id = this.$auth.user.id;
    } else {
      console.warn('User information is not available.');
    }
  },

  mounted() {
    this.fetchUsers();
  }
};
</script>

<style>
/* Add any custom styles here */
</style>
