<template>
    <div class="app-container">
      <div class="header">
        Load Details
      </div>

      <div class="header2" v-if="load">
        Load# {{ load['load_number'] }}
        <button @click="openEditLoadModal" class="edit-button bigger-button">
          <i class="fas fa-pencil-alt"></i>
        </button>
        <button @click="openDeleteLoadModal" class="delete-button bigger-button">
          <i class="fas fa-trash-alt"></i>
        </button>
      </div>
      
  
      <div class="load-details" v-if="load">
        <div><strong>Load Number:</strong> {{ load['load_number'] }}</div>
        <div><strong>Load Status:</strong> {{ load['load_status'] }}</div>
        <div><strong>Load Mode:</strong> {{ load['load_mode'] }}</div>
        <div><strong>Customer Quote Total:</strong> {{ load['customer_quote_total'] }}</div>
        <div><strong>Customer Invoice Total:</strong> {{ load['customer_invoice_total'] }}</div>
        <div><strong>Total Weight:</strong> {{ load['total_weight'] }}</div>
      </div>

      <div v-if="showEditLoadModal" class="edit-modal">
        <div class="edit-modal-content">
          <div class="edit-modal-header">
            <h2>Edit Load</h2>
          </div>
          <div class="edit-modal-body">
            <div>
              <strong>Load Number:</strong>
              <input :value="editedLoad['load_number']" readonly />
            </div>
            <div>
              <strong>Load Status:</strong>
              <input v-model="editedLoad['load_status']" />
            </div>
            <div>
              <strong>Load Mode:</strong>
              <input v-model="editedLoad['load_mode']" />
            </div>
            <div>
              <strong>Customer Quote Total:</strong>
              <input v-model="editedLoad['customer_quote_total']" />
            </div>
            <div>
              <strong>Customer Invoice Total:</strong>
              <input v-model="editedLoad['customer_invoice_total']" />
            </div>
            <div>
              <strong>Total Weight:</strong>
              <input v-model="editedLoad['total_weight']" />
            </div>
          </div>
          <div class="edit-modal-footer">
            <button @click="saveEditedLoad">Save</button>
            <button @click="showEditLoadModal = false">Cancel</button>
          </div>
        </div>
      </div>
  
      <div v-if="showDeleteLoadModal" class="delete-modal">
        <div class="delete-modal-content">
          <div class="delete-modal-header">
            <h2>Confirm Load Deletion</h2>
          </div>
          <div class="delete-modal-body">
            <p>Are you sure you want to delete this load?</p>
          </div>
          <div class="delete-modal-footer">
            <button @click="deleteLoad">Yes</button>
            <button @click="showDeleteLoadModal = false">Cancel</button>
          </div>
        </div>
      </div>

      <InvoiceListLoadDetails v-if="load" :invoices="load.invoices" />

      <div v-else>
        Loading...
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import InvoiceListLoadDetails from './InvoiceListLoadDetails.vue';

  
  export default {
    
  data() {
    return {
      load: null,
      showDeleteLoadModal: false,
      showEditLoadModal: false,
      editedLoad: {}, 
    };
  },
  mounted() {
    this.fetchLoadDetails();
  },
  components: {
    InvoiceListLoadDetails
  },
  methods: {
    fetchLoadDetails() {
      const segments = window.location.pathname.split('/');
      const loadNumber = segments[segments.length - 1];

      if (loadNumber) {
      axios.get(`/get/load/${loadNumber}`)
        .then(response => {
          this.load = response.data.load;
          axios.get(`/get/load/${loadNumber}/invoices`)
            .then(response => {
              this.load.invoices = response.data.invoices;
            })
            .catch(error => {
              console.error(error);
            });
        })
        .catch(error => {
        });
    } else {
      console.error("Load number is not available in the URL.");
    }
    },

    openDeleteLoadModal() {
      this.showDeleteLoadModal = true;
    },

    deleteLoad() {
      const loadNumber = this.load['load_number'];

      axios.delete(`/delete/load/${loadNumber}`)
        .then(() => {
          this.showDeleteLoadModal = false;
          window.location.href = '/loads';
        })
        .catch(error => {
          console.error(error);
          this.showDeleteLoadModal = false;
        });
    },

    openEditLoadModal() {
      this.showEditLoadModal = true;
      this.editedLoad = { ...this.load };
    },

    saveEditedLoad() {
      const loadNumber = this.load['load_number'];

      axios.put(`/edit/load/${loadNumber}`, this.editedLoad)
        .then(() => {
          this.showEditLoadModal = false;
        })
        .catch(error => {
          console.error(error);
          this.showEditLoadModal = false;
        });
    },
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

  .header2 {
    text-align: center;
    margin-top: 30px;
    margin-right: 1210px;
    font-size: 1.7em;
    color: #fffffe;
    font-weight: bold;
  }
  
  .load-details {
    width: 90%;
    margin: 40px auto;
    background-color: #ffc0ad;
    color: #271c19;
    padding: 10px 15px;
    border: 1px solid #271C19;
  }
  
  .load-details div {
    margin-bottom: 10px;
  }

  .delete-button {
    cursor: pointer;
    color: red;
    margin-left: 10px;
}

.delete-modal-content {
    background-color: #f2f2f2;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 50%;
    max-width: 400px;
    margin: auto;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.delete-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.delete-modal-header h2 {
    text-align: center;
}

.delete-modal-footer {
    text-align: right;
}

.delete-modal-footer button {
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

.delete-modal-footer button:hover {
    background-color: #0056b3;
}

.delete-modal-body {
    margin-bottom: 10px;
}

.edit-modal-content {
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

.edit-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.edit-modal-header h2 {
    text-align: center;
}

.edit-modal-footer {
    text-align: right;
}

.edit-modal-footer button {
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

.edit-modal-footer button:hover {
    background-color: #0056b3;
}

.edit-modal-body textarea {
    width: 95%;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    min-height: 120px;
}

.bigger-button {
  font-size: 0.6em;
  padding: 3px 5px;
}
  </style>