<template>
  <div class="app-container">
    <div class="header">
      My Loads
    </div>

    <table>
      <thead>
        <tr>
          <th>Load Number</th>
          <th>Load Status</th>
          <th>Load Mode</th>
          <th>Customer Quote Total</th>
          <th>Customer Invoice Total</th>
          <th>Total Weight</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="load in loads" :key="load['load_number']">
          <td>
            <a :href="'/load/' + load['load_number']">{{ load['load_number'] }}</a>
          </td>
          <td>{{ load['load_status'] }}</td>
          <td>{{ load['load_mode'] }}</td>
          <td>{{ load['customer_quote_total'] }}</td>
          <td>{{ load['customer_invoice_total'] }}</td>
          <td>{{ load['total_weight'] }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';
import { useRouter } from 'vue-router'; 


export default {
  data() {
    return {
      loads: []
    };
  },
  created() {
    this.fetchLoads();
  },
  goToLoadDetails(loadNumber) {
      this.$router.push({ name: 'LoadDetails', params: { loadNumber } });
    },
  methods: {
    fetchLoads() {
      axios.get('/api/loads')
        .then(response => {
          this.loads = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    }
  }
};
</script>

<style scoped>
.app-container {
  background-color: #55423d;
  min-height: 100vh;
}

.header {
  background-color: #271C19;
  color: #fffffe;
  padding: 20px 0;
  text-align: center;
  font-size: 2em;
  font-weight: bold;
}

table {
  width: 90%;
  margin: 40px auto;
  border-collapse: collapse;
}

table, th, td {
  border: 1px solid #271C19;
}

th, td {
  padding: 10px 15px;
  text-align: left;
}

th {
  background-color: #271C19;
  color: #fffffe;
}

tr {
  background-color: #ffc0ad;
  color: #271c19;
  transition: background-color 0.3s ease;
}

tr:hover {
  background-color: rgba(255, 192, 173, 0.7);
}

router-link, router-link:visited {
  color: blue;
  text-decoration: underline;
  cursor: pointer;
}

router-link:hover {
  color: darkerblue;
  text-decoration: none;
}
</style>