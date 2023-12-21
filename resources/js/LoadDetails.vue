<template>
  <div class="app-container">
    <div class="header">Load Details</div>

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

    <transition name="fade">
      <div v-if="showEditLoadModal" class="modal-overlay">
        <div class="edit-modal-content">
          <div class="edit-modal-header">
            <h2>EDIT LOAD</h2>
          </div>
          <div class="edit-modal-body">
            <div>
              <strong>Load Number:</strong>
              <input :value="editedLoad['load_number']" readonly />
            </div>
            <div>
              <strong>Load Status:</strong>
              <select v-model="editedLoad['load_status']" class="load-status-select">
                <option value="Unassigned">Unassigned</option>
                <option value="Assigned">Assigned</option>
                <option value="In Transit">In Transit</option>
                <option value="Delivered">Delivered</option>
                <option value="Completed">Completed</option>
              </select>
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
            <button @click="showEditLoadModal = false" class="cancel-button">Cancel</button>
          </div>
        </div>
      </div>
    </transition>

    <transition name="drop">
      <div class="delete-modal" v-if="showDeleteLoadModal">
        <div class="delete-modal-content">
          <div class="delete-modal-body">
            <p>Are you sure you want to delete this load?</p>
          </div>
          <div class="delete-modal-footer">
            <button @click="deleteLoad" class="delete-modal-button">Yes</button>
            <button @click="showDeleteLoadModal = false" class="delete-modal-button">Cancel</button>
          </div>
        </div>
      </div>
    </transition>

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
        axios.get(`/get/load/${loadNumber}`, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
          .then(response => {
            this.load = response.data.load;
            axios.get(`/get/load/${loadNumber}/invoices`, {
              headers: {
                'Authorization': `Bearer ${localStorage.getItem('access_token')}`
              }
            })
              .then(response => {
                this.load.invoices = response.data.invoices;
              })
              .catch(error => {
                console.error(error);
                if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
                  window.location.href = '/';
                }
              });
          })
          .catch(error => {
            if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
              window.location.href = '/';
            }
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

      axios.delete(`/delete/load/${loadNumber}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('access_token')}`
        }
      })
        .then(() => {
          this.showDeleteLoadModal = false;
          window.location.href = '/loads';
        })
        .catch(error => {
          console.error(error);
          this.showDeleteLoadModal = false;
          if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
            window.location.href = '/';
          }
        });
    },

    openEditLoadModal() {
      this.showEditLoadModal = true;
      this.editedLoad = { ...this.load };
    },

    saveEditedLoad() {
      const loadNumber = this.load['load_number'];

      axios.put(`/edit/load/${loadNumber}`, this.editedLoad, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('access_token')}`
        }
      })
        .then(() => {
          this.showEditLoadModal = false;
          this.fetchLoadDetails();
        })
        .catch(error => {
          console.error(error);
          this.showEditLoadModal = false;
          if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
            window.location.href = '/';
          }
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
  margin-top: 30px;
  margin-left: 60px;
  font-size: 1.7em;
  color: #fffffe;
  font-weight: bold;
}

.load-details {
  width: 90%;
  margin: 40px auto;
  background-color: #ffc0ad;
  color: #271c19;
  padding: 20px;
  border: 1px solid #271C19;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

.load-details div {
  margin-bottom: 15px;
  font-size: 1.1em;
}

.load-details strong {
  font-weight: 600;
  color: #55423d;
  display: block;
  margin-bottom: 5px;
}

.edit-button, .delete-button {
  margin-left: 10px;
  border: none;
  background-color: #271C19;
  color: white;
  width: 30px;
  height: 30px;
  padding: 15px 30px;
  border-radius: 30px;
  font-size: 1.5em;
}

.delete-button {
  color: rgb(214, 70, 70);
}

.edit-button:hover, .delete-button:hover {
  background-color: #40312d;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.drop-enter-active {
  animation: drop-in 0.5s ease;
}

.drop-leave-active {
  transition: opacity 0.5s ease;
}

.drop-enter, .drop-leave-to {
  opacity: 0;
}

@keyframes drop-in {
  0% {
    opacity: 0;
    transform: translate(-50%, -30%);
  }
  100% {
    opacity: 1;
    transform: translate(-50%, -50%);
  }
}

.delete-modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
}

.delete-modal-content {
  background-color: #f2f2f2;
  color: #271c19;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  width: auto;
  max-width: 600px;
  text-align: center;
  transition: transform 0.3s ease-out, opacity 0.3s ease;
}

.delete-modal-header {
  margin-bottom: 20px;
}

.delete-modal-body {
  margin-bottom: 30px;
  font-size: 22px;
  font-weight: bold;
}

.delete-modal-footer {
  display: flex;
  justify-content: right;
}

.delete-modal-button {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s;
  outline: none;
  font-weight: 600;
  margin: 0 10px;
}

.delete-modal-button:hover {
  opacity: 0.9;
}

.delete-modal-button:nth-child(1) {
  background-color: #ff7861;
  color: white;
}

.delete-modal-button:nth-child(1):hover {
  background-color: #e56753;
}

.delete-modal-button:nth-child(2) {
  background-color: #ccc;
  color: #333;
}

.delete-modal-button:nth-child(2):hover {
  background-color: #bbb;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1050;
  display: flex;
  justify-content: center;
  align-items: center;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-leave-to {
  opacity: 0;
}

.edit-modal-content {
  background-color: #fff;
  color: #333;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  width: 50vw;
  max-width: 600px;
  text-align: left;
  transition: none;
  opacity: 0;
  animation: fadeIn 0.5s ease forwards;
  box-sizing: border-box;
}

.edit-modal-header h2 {
  margin-bottom: 30px;
  font-size: 1.75em;
  color: #55423d;
  font-weight: bold;
}

.edit-modal-body {
  margin-bottom: 40px;
  font-size: 1em;
}

.edit-modal-body div {
  margin-bottom: 20px;
}

.edit-modal-body strong {
  display: block;
  margin-bottom: 5px;
  color: #55423d;
  font-weight: normal;
}

.edit-modal-body input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-sizing: border-box;
}

.edit-modal-footer {
  display: flex;
  justify-content: right;
}

.edit-modal-footer button {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 600;
  margin-left: 10px;
  transition: background-color 0.2s, box-shadow 0.2s;
  background-color: #ff7861;
  color: white;
}

.edit-modal-footer button:hover {
  background-color: #e56753;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.edit-modal-footer button.cancel-button {
  background-color: #ccc;
  color: #333;
}

.edit-modal-footer button.cancel-button:hover {
  background-color: #bbb;
}

.load-status-select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: white;
  color: #333;
  font-size: 1em;
  box-sizing: border-box;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-image: url('data:image/svg+xml;utf8,<svg fill="%23333" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
  background-repeat: no-repeat;
  background-position: right 10px top 50%;
  cursor: pointer;
}

.load-status-select:hover {
  border-color: #bbb;
}

.load-status-select:focus {
  outline: none;
  border-color: #555;
}

.date-input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: white;
  color: #333;
  font-size: 1em;
  box-sizing: border-box;
}

.date-input:hover {
  border-color: #bbb;
}

.date-input:focus {
  outline: none;
  border-color: #555;
}

.bigger-button {
  font-size: 0.6em;
  padding: 3px 5px;
}
</style>