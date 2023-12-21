<template>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  
    <div class="app-container">
      <div class="header">
        Invoice Details
      </div>
  
      <div class="header2" v-if="invoice">
        Invoice# {{ editedInvoice['invoice_number'] }}
        <button @click="editInvoice" class="edit-button bigger-button">
            <i class="fas fa-pencil-alt"></i>
        </button>
        <button @click="openDeleteInvoiceModal" class="delete-button bigger-button">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>
      <div class="flex-container">
        <div class="invoice-details" v-if="invoice">
        <div><strong>Invoice Number:</strong> {{ invoice['invoice_number'] }}</div>
        <div><strong>Load Number:</strong> {{ invoice['load_number'] }}</div>
        <div><strong>Invoice Status:</strong> {{ invoice['invoice_status'] }}</div>
        <div><strong>Invoice Date:</strong> {{ invoice['invoice_date'] }}</div>
        <div><strong>Invoice Due Date:</strong> {{ invoice['invoice_due_date'] }}</div>
        <div><strong>Billing Contact:</strong> {{ invoice['billing_contact'] }}</div>
        <div><strong>Freight Charges:</strong> {{ invoice['freight_charges'] }}</div>
        <div><strong>Fuel Surcharge:</strong> {{ invoice['fuel_surcharge'] }}</div>
      </div>
  
        <div class="comments-section" v-if="commentsInitialized">
          <div class="add-comment">
            <textarea v-model="newComment" placeholder="Add a comment..."></textarea>
            <button @click="addComment">Submit</button>
          </div>
          <div v-for="comment in comments" :key="comment.id">
            <div>
              <strong>{{ comment.user.name }}:</strong> {{ comment['comment'] }} - {{ comment['date'] }}
              <span @click="editComment(comment.id)" class="edit-button-comment">
                <i class="fas fa-pencil-alt" style="color: rgb(95, 95, 163);"></i>
              </span>
              <span @click="deleteComment(comment.id)" class="delete-button-comment">
                <i class="fas fa-trash-alt"></i>
              </span>
            </div>
          </div>
        </div>
  
        <div v-else>
          Loading...
        </div>
      </div>
  
      <transition name="fade">
        <div v-if="showEditModal" class="edit-modal">
          <div class="edit-modal-content-comment">
            <div class="edit-modal-header">
              <h2>Edit Comment</h2>
            </div>
            <div class="edit-modal-body">
              <textarea v-model="editedCommentText" placeholder="Edit your comment..."></textarea>
            </div>
            <div class="edit-modal-footer">
              <button @click="confirmEdit">Save</button>
              <button @click="showEditModal = false" class="delete-modal-button">Cancel</button>
            </div>
          </div>
        </div>
      </transition>
  
      <transition name="drop">
        <div class="delete-modal" v-if="showDeleteModal">
          <div class="delete-modal-content">
            <div class="delete-modal-body">
              <p>Are you sure you want to delete this comment?</p>
            </div>
            <div class="delete-modal-footer">
              <button @click="confirmDelete" class="delete-modal-button">Yes</button>
              <button @click="showDeleteModal = false" class="delete-modal-button">Cancel</button>
            </div>
          </div>
        </div>
      </transition>
  
      <transition name="fade">
        <div v-if="showEditInvoiceModal" class="modal-overlay">
          <div class="edit-modal-content-invoice">
              <div class="edit-modal-header-invoice">
                <h2>EDIT INVOICE</h2>
              </div>
              <div class="edit-modal-body-invoice">
                <div>
                  <strong>Invoice Number:</strong>
                  <input :value="editedInvoice['invoice_number']" readonly />
                </div>
                <div>
                  <strong>Load Number:</strong>
                  <input :value="editedInvoice['load_number']" readonly />
                </div>
                <div>
                  <strong>Invoice Status:</strong>
                  <select v-model="editedInvoice['invoice_status']" class="invoice-status-select">
                    <option value="Pending">Pending</option>
                    <option value="Invoiced">Invoiced</option>
                    <option value="Paid">Paid</option>
                  </select>
                </div>
                <div>
                  <strong>Invoice Date:</strong>
                  <input type="date" v-model="editedInvoice['invoice_date']" class="date-input" />
                </div>
                <div>
                  <strong>Invoice Due Date:</strong>
                  <input type="date" v-model="editedInvoice['invoice_due_date']" class="date-input" />
                </div>
                <div>
                  <strong>Billing Contact:</strong>
                  <input v-model="editedInvoice['billing_contact']" />
                </div>
                <div>
                  <strong>Freight Charges:</strong>
                  <input v-model="editedInvoice['freight_charges']" />
                </div>
                <div>
                  <strong>Fuel Surcharge:</strong>
                  <input v-model="editedInvoice['fuel_surcharge']" />
                </div>
              </div>
              <div class="edit-modal-footer-invoice">
                <button @click="saveEditedInvoice">Save</button>
                <button @click="showEditInvoiceModal = false" class="cancel-button-invoice">Cancel</button>
              </div>
            </div>
          </div>
      </transition>


      <transition name="drop">
        <div class="delete-modal" v-if="showDeleteInvoiceModal">
          <div class="delete-modal-content">
            <div class="delete-modal-body">
              <p>Are you sure you want to delete this invoice?</p>
            </div>
            <div class="delete-modal-footer">
              <button @click="deleteInvoice" class="delete-modal-button">Yes</button>
              <button @click="showDeleteInvoiceModal = false" class="delete-modal-button">Cancel</button>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        invoice: null,
        commentsInitialized: null,
        comments: [],
        newComment: '',
        showDeleteModal: false,
        commentToDeleteId: null,
        showEditModal: false,
        editedCommentId: null,
        editedCommentText: '',
        showEditInvoiceModal: false,
        editedInvoice: {},
        showDeleteInvoiceModal: false,
      };
    },
    mounted() {
      this.fetchInvoiceDetails();
      this.fetchComments();
    },
    methods: {
      fetchInvoiceDetails() {
          const segments = window.location.pathname.split('/');
          const invoiceNumber = segments[segments.length - 1];
          
          if (window.location.pathname.startsWith('/load')) {
              if (invoiceNumber) {
              const loadNumber = segments[segments.length - 3];

              axios.get(`/get/load/${loadNumber}/invoice/${invoiceNumber}`, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
              .then(response => {
                  this.invoice = { ...response.data.invoice };
                  this.editedInvoice = { ...response.data.invoice };
                  console.log(response.data);
              })
              .catch(error => {
                  console.error(error);
                  if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
                    window.location.href = '/';
                  }
              });
              } 
              else {
                  console.error("Invoice number is not available in the URL.");
              }
          } 
          else {
            if (invoiceNumber) {
            axios.get(`/api/invoice/${invoiceNumber}`, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
            .then(response => {
                this.invoice = { ...response.data };
                this.editedInvoice = { ...response.data };
            })
            .catch(error => {
                console.error(error);
                if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
                  window.location.href = '/';
                }
            });
            } 
            else {
                console.error("Invoice number is not available in the URL.");
            }
          }
        },
  
      fetchComments() {
        const segments = window.location.pathname.split('/');
        const invoiceNumber = segments[segments.length - 1];
  
        if (window.location.pathname.startsWith('/load')) 
        {
          const loadNumber = segments[segments.length - 3];

          if (invoiceNumber) {
            axios.get(`/get/load/${loadNumber}/invoice/${invoiceNumber}/comments`, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
            .then(response => {
              this.comments = response.data.comments;
              this.commentsInitialized = 'initialized';
            })
            .catch(error => {
              console.error(error);
              if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
                window.location.href = '/';
              }
            });
          }
        }
        else
        {
          if (invoiceNumber) {
          axios.get(`/api/comments/${invoiceNumber}`, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
            .then(response => {
              this.comments = response.data;
              this.commentsInitialized = 'initialized';
            })
            .catch(error => {
              console.error(error);
              if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
                window.location.href = '/';
              }
            });
          }
        }
      },
  
      addComment() {
        const segments = window.location.pathname.split('/');
        const invoiceNumber = segments[segments.length - 1];
  
        if (invoiceNumber) {
          axios.post(`/insert/load/${this.editedInvoice['load_number']}/invoice/${this.editedInvoice['invoice_number']}/comment`, {
            comment_text: this.newComment,
          }, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
          .then(() => {
            this.newComment = '';
            this.fetchComments();
          })
          .catch(error => {
            console.error(error);
            if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
              window.location.href = '/';
            }
          });
        }
      },
  
      deleteComment(commentId) {
        this.commentToDeleteId = commentId;
        this.showDeleteModal = true;
      },
  
      confirmDelete() {
        if (this.commentToDeleteId) {
          axios.delete(`/delete/load/${this.editedInvoice['load_number']}/invoice/${this.editedInvoice['invoice_number']}/comment/${this.commentToDeleteId}`, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
            .then(() => {
              this.fetchComments();
              this.showDeleteModal = false;
            })
            .catch(error => {
              console.error('kazkas blg');
              this.showDeleteModal = false;
              if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
                window.location.href = '/';
              }
            });
        }
      },
  
      editComment(commentId) {
        this.editedCommentId = commentId;
        this.editedCommentText = this.comments.find(comment => comment.id === commentId)['comment'];
        this.showEditModal = true;
      },
  
      confirmEdit() {
        if (this.editedCommentId) {
          axios.put(`/edit/load/${this.editedInvoice['load_number']}/invoice/${this.editedInvoice['invoice_number']}/comment/${this.editedCommentId}`, {
            comment_text: this.editedCommentText,
          }, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
          .then(() => {
            this.fetchComments();
            this.showEditModal = false;
          })
          .catch(error => {
            console.error(error);
            this.showEditModal = false;
            if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
              window.location.href = '/';
            }
          });
        }
      },
  
      editInvoice() {
        this.showEditInvoiceModal = true;
      },
  
      saveEditedInvoice() {
        const invoiceNumber = this.editedInvoice['invoice_number'];
        const loadNumber = this.editedInvoice['load_number'];

        axios.put(`/edit/load/${loadNumber}/invoice/${invoiceNumber}`, {
          'invoice_number': this.editedInvoice['invoice_number'],
          'load_number': this.editedInvoice['load_number'],
          'invoice_status': this.editedInvoice['invoice_status'],
          'invoice_date': this.editedInvoice['invoice_date'],
          'invoice_due_date': this.editedInvoice['invoice_due_date'],
          'billing_contact': this.editedInvoice['billing_contact'],
          'freight_charges': this.editedInvoice['freight_charges'],
          'fuel_surcharge': this.editedInvoice['fuel_surcharge']
        }, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
        .then(() => {
          this.showEditInvoiceModal = false;
          this.fetchInvoiceDetails();
        })
        .catch(error => {
          console.error(error);
          this.showEditInvoiceModal = false;
          if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
              window.location.href = '/';
          }
        });
      },
      openDeleteInvoiceModal() {
            this.showDeleteInvoiceModal = true;
      },

      deleteInvoice() {
        const invoiceNumber = this.editedInvoice['invoice_number'];

        axios.delete(`/api/comments/invoice/delete/${invoiceNumber}`, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
        .then(() => {
            axios.delete(`/delete/load/${this.editedInvoice['load_number']}/invoice/${this.editedInvoice['invoice_number']}`, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
            .then(() => {
                this.showDeleteModal = false;
                window.location.href = '/invoices';
            })
            .catch(error => {
                console.error(error);
                this.showDeleteModal = false;
                if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
                  window.location.href = '/';
                }
            });
        })
        .catch(error => {
            console.error(error);
            this.showDeleteModal = false;
            if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
              window.location.href = '/';
            }
        });
    },
    },
  };
  </script>
  
  <style scoped>
  @media (max-width: 768px) {
      .flex-container {
          flex-direction: column;
      }
  
      .invoice-details, .comments-section {
          width: 100%; 
          margin: 0;
          max-width: none;
      }
  }
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
  
  .invoice-details {
    width: 80%;
    margin-left: -15px !important;
    margin: 40px auto;
    background-color: #ffc0ad;
    color: #271c19;
    padding: 20px;
    border: 1px solid #271C19;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
  }
  
  .invoice-details div {
    margin-bottom: 15px;
    font-size: 1.1em;
  }
  
  .invoice-details strong {
    font-weight: 600;
    color: #55423d;
    display: block;
    margin-bottom: 5px;
  }
  
  .comments-section {
      background-color: #ffc0ad;
      padding: 15px;
      border: 1px solid #271C19;
      margin-top: 40px;
      margin-left: 80px;
      border-radius: 5px;
      width: 80%;
      color: #271c19;
  }
  
  .comments-section .header {
      font-weight: bold;
      font-size: 1.2em;
      color: #271c19;
      margin-bottom: 10px;
  }
  
  .comments-section div {
      padding: 10px;
      background-color: #ffd7cc;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      margin-bottom: 10px;
  }
  
  .add-comment {
      margin-top: 20px;
  }
  
  .add-comment textarea {
      width: 90%;
      padding: 10px;
      border: 1px solid #271C19;
      border-radius: 5px;
      min-height: 80px;
      background-color: #ffffff;
      color: #271c19;
  }
  
  .add-comment button {
      display: inline-block;
      margin-top: 10px;
      padding: 10px 20px;
      background-color: #9b3c22;
      color: #ffffff;
      border-radius: 5px;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
  }
  
  .add-comment button:hover {
      background-color: #d8907d;
  }
  
  .flex-container {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      padding: 0 5%;
      max-width: 1500px;
      margin: 0 auto; 
  }
  
  .invoice-details, .comments-section {
      flex: 1;
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
  
  .delete-button-comment {
      cursor: pointer;
      color: red;
      margin-left: 10px;
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
  
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.4s;
  }
  
  .fade-enter, .fade-leave-to  {
    opacity: 0;
  }
  
  @keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }
  
  .edit-modal-content{
    animation: fadeIn 0.5s ease forwards;
  }
    .edit-modal, .delete-modal {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 1060;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  
    .edit-modal-content, .delete-modal-content {
      background-color: #f2f2f2;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      width: 50vw;
      max-width: 600px;
  
    }
  
  
  .edit-modal-footer, .delete-modal-footer {
    display: flex;
    justify-content: right;
  }
  
  .edit-modal-footer button, .delete-modal-footer button {
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
  
  .edit-modal-footer button:hover, .delete-modal-footer button:hover {
    background-color: #e56753;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  }
  
  .edit-modal-content-comment{
    animation: fadeIn 0.5s ease forwards;
  }
  .edit-modal-content-comment {
    background-color: #f2f2f2;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    width: 50vw;
    max-width: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    animation: fadeIn 0.5s ease forwards;
  }
  
  .edit-modal-content-invoice {
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
  
  .edit-modal-header-invoice h2 {
    margin-bottom: 30px;
    font-size: 1.75em;
    color: #55423d;
    font-weight: bold;
  }
  
  .edit-modal-body-invoice {
    margin-bottom: 40px;
    font-size: 1em;
  }
  
  .edit-modal-body-invoice div {
    margin-bottom: 20px;
  }
  
  .edit-modal-body-invoice strong {
    display: block;
    margin-bottom: 5px;
    color: #55423d;
    font-weight: normal;
  }
  
  .edit-modal-body-invoice input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
  }
  
  .edit-modal-footer-invoice {
    display: flex;
    justify-content: right;
  }
  .edit-modal-footer-invoice button {
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
  
  .edit-modal-footer-invoice button:hover {
    background-color: #e56753;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  }
  
  .edit-modal-footer-invoice button.cancel-button-invoice {
    background-color: #ccc;
    color: #333;
  }
  
  .edit-modal-footer-invoice button.cancel-button-invoice:hover {
    background-color: #bbb;
  }
  
  .invoice-status-select {
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
  
  .invoice-status-select:hover {
    border-color: #bbb;
  }
  
  .invoice-status-select:focus {
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