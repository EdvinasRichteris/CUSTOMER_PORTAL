<template>
  <div class="app-container">
    <div class="header">
      My Invoices
    </div>

    <button @click="showCreateInvoiceModal = true" class="create-button">
      Create Invoice
    </button>

    <table>
      <thead>
        <tr>
          <th>Invoice Number</th>
          <th>Load Number</th>
          <th>Invoice Status</th>
          <th>Invoice Date</th>
          <th>Invoice Due Date</th>
          <th>Billing Contact</th>
          <th>Freight Charges</th>
          <th>Fuel Surcharge</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="invoice in invoices" :key="invoice['invoice_number']">
          <td>
            <a :href="'/invoices/' + invoice['invoice_number']">
              {{ invoice['invoice_number'] }}
            </a>
          </td>
          <td>{{ invoice['load_number'] }}</td>
          <td>{{ invoice['invoice_status'] }}</td>
          <td>{{ invoice['invoice_date'] }}</td>
          <td>{{ invoice['invoice_due_date'] }}</td>
          <td>{{ invoice['billing_contact'] }}</td>
          <td>{{ invoice['freight_charges'] }}</td>
          <td>{{ invoice['fuel_surcharge'] }}</td>
        </tr>
      </tbody>
    </table>
    <div v-if="showCreateInvoiceModal" class="custom-modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Create Invoice</h2>
        </div>
        <div class="modal-body">
          <div>
            <strong>Load Number:</strong>
            <select v-model="newInvoice['load_number']">
              <option v-for="load in loadNumbers" :key="load" :value="load">
                {{ load }}
              </option>
            </select>
          </div>
          <div>
            <strong>Invoice Status:</strong>
            <input v-model="newInvoice['invoice_status']" />
          </div>
          <div>
            <strong>Invoice Date:</strong>
            <input v-model="newInvoice['invoice_date']" />
          </div>
          <div>
            <strong>Invoice Due Date:</strong>
            <input v-model="newInvoice['invoice_due_date']" />
          </div>
          <div>
            <strong>Billing Contact:</strong>
            <input v-model="newInvoice['billing_contact']" />
          </div>
          <div>
            <strong>Freight Charges:</strong>
            <input v-model="newInvoice['freight_charges']" />
          </div>
          <div>
            <strong>Fuel Surcharge:</strong>
            <input v-model="newInvoice['fuel_surcharge']" />
          </div>
        </div>
        <div class="edit-modal-footer">
          <button @click="saveNewInvoice">Save</button>
          <button @click="showCreateInvoiceModal = false">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      invoices: [],
      showCreateInvoiceModal: false,
      newInvoice: {
        'load_number': '',
        'invoice_status': '',
        'invoice_date': '',
        'invoice_due_date': '',
        'billing_contact': '',
        'freight_charges': '',
        'fuel_surcharge': '',
      },
      loadNumbers: [],
    };
  },
  created() {
    this.fetchInvoices();
    this.fetchLoadNumbers();
  },
  methods: {
    fetchInvoices() {
      axios.get('/api/invoices', { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
        .then(response => {
          this.invoices = response.data;
        })
        .catch(error => {
          console.error(error);
          if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
            window.location.href = '/';
          }
        });
    },
    fetchLoadNumbers() {
      axios.get('/api/loads', { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
        .then(response => {
          this.loadNumbers = response.data.map(load => load['load_number']);
        })
        .catch(error => {
          console.error(error);
          if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
            window.location.href = '/';
          }
        });
    },
    openCreateInvoiceModal() {
      this.showCreateInvoiceModal = true;
    },
    saveNewInvoice() {
      axios.post('/api/invoices/create', this.newInvoice, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
        .then(response => {
          this.invoices.push(response.data);
          this.showCreateInvoiceModal = false;
        })
        .catch(error => {
          console.error(error);
          if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
            window.location.href = '/';
          }
        });
    },
  },
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

.create-button {
  background-color: #007bff;
  color: #ffffff;
  border: none;
  border-radius: 5px;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
}

.create-button:hover {
  background-color: #0056b3;
}

.custom-modal {
  background-color: #f2f2f2;
  padding: 10px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  width: 60%;
  max-width: 600px;
  margin: auto;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.modal-header h2 {
  text-align: center;
}

.modal-footer {
  text-align: right;
}

.modal-footer button {
  display: inline-block;
  margin: 0 5px;
  padding: 10px 20px;
  background-color: #007bff;
  color: #ffffff;
  border-radius: 5px;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.modal-footer button:hover {
  background-color: #0056b3;
}

.modal-body textarea {
  width: 95%;
  padding: 10px;
  border: 1px solid #e0e0e0;
  border-radius: 5px;
  min-height: 120px;
}
</style>