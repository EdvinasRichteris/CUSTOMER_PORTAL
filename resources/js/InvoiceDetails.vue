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
          <div><strong>Invoice Number:</strong> {{ editedInvoice['invoice_number'] }}</div>
          <div><strong>Load Number:</strong> {{ editedInvoice['load_number'] }}</div>
          <div><strong>Invoice Status:</strong> <input v-model="editedInvoice['invoice_status']" /></div>
          <div><strong>Invoice Date:</strong> <input v-model="editedInvoice['invoice_date']" /></div>
          <div><strong>Invoice Due Date:</strong> <input v-model="editedInvoice['invoice_due_date']" /></div>
          <div><strong>Billing Contact:</strong> <input v-model="editedInvoice['billing_contact']" /></div>
          <div><strong>Freight Charges:</strong> <input v-model="editedInvoice['freight_charges']" /></div>
          <div><strong>Fuel Surcharge:</strong> <input v-model="editedInvoice['fuel_surcharge']" /></div>
        </div>
  
        <div class="comments-section" v-if="commentsInitialized">
          <div class="add-comment">
            <textarea v-model="newComment" placeholder="Add a comment..."></textarea>
            <button @click="addComment">Submit</button>
          </div>
          <div v-for="comment in comments" :key="comment.id">
            <div>
              <strong>{{ comment.user.name }}:</strong> {{ comment['comment'] }} - {{ comment['date'] }}
              <span @click="editComment(comment.id)" class="edit-button">
                <i class="fas fa-pencil-alt" style="color: rgb(95, 95, 163);"></i>
              </span>
              <span @click="deleteComment(comment.id)" class="delete-button">
                <i class="fas fa-trash-alt"></i>
              </span>
            </div>
          </div>
        </div>
  
        <div v-else>
          Loading...
        </div>
      </div>
  
      <div v-if="showEditModal" class="edit-modal">
        <div class="edit-modal-content">
          <div class="edit-modal-header">
            <h2>Edit Comment</h2>
          </div>
          <div class="edit-modal-body">
            <textarea v-model="editedCommentText" placeholder="Edit your comment..."></textarea>
          </div>
          <div class="edit-modal-footer">
            <button @click="confirmEdit">Save</button>
            <button @click="showEditModal = false">Cancel</button>
          </div>
        </div>
      </div>
  
      <div v-if="showDeleteModal" class="delete-modal">
        <div class="delete-modal-content">
          <div class="delete-modal-header">
            <h2>Confirm Deletion</h2>
          </div>
          <div class="delete-modal-body">
            <p>Are you sure you want to delete this comment?</p>
          </div>
          <div class="delete-modal-footer">
            <button @click="confirmDelete">Yes</button>
            <button @click="showDeleteModal = false">Cancel</button>
          </div>
        </div>
      </div>
  
      <div v-if="showEditInvoiceModal" class="edit-modal">
        <div class="edit-modal-content">
          <div class="edit-modal-header">
            <h2>Edit Invoice</h2>
          </div>
          <div class="edit-modal-body">
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
              <input v-model="editedInvoice['invoice_status']" />
            </div>
            <div>
              <strong>Invoice Date:</strong>
              <input v-model="editedInvoice['invoice_date']" />
            </div>
            <div>
              <strong>Invoice Due Date:</strong>
              <input v-model="editedInvoice['invoice_due_date']" />
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
          <div class="edit-modal-footer">
            <button @click="saveEditedInvoice">Save</button>
            <button @click="showEditInvoiceModal = false">Cancel</button>
          </div>
        </div>
      </div>
      <div v-if="showDeleteInvoiceModal" class="delete-modal">
        <div class="delete-modal-content">
            <div class="delete-modal-header">
            <h2>Confirm Invoice Deletion</h2>
            </div>
            <div class="delete-modal-body">
            <p>Are you sure you want to delete this invoice?</p>
            </div>
            <div class="delete-modal-footer">
            <button @click="deleteInvoice">Yes</button>
            <button @click="showDeleteInvoiceModal = false">Cancel</button>
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

              axios.get(`/get/load/${loadNumber}/invoice/${invoiceNumber}`)
              .then(response => {
                  this.invoice = { ...response.data.invoice };
                  this.editedInvoice = { ...response.data.invoice };
                  console.log(response.data);
              })
              .catch(error => {
                  console.error(error);
              });
              } 
              else {
                  console.error("Invoice number is not available in the URL.");
              }
          } 
          else {
            if (invoiceNumber) {
            axios.get(`/api/invoice/${invoiceNumber}`)
            .then(response => {
                this.invoice = { ...response.data };
                this.editedInvoice = { ...response.data };
            })
            .catch(error => {
                console.error(error);
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
            axios.get(`/get/load/${loadNumber}/invoice/${invoiceNumber}/comments`)
            .then(response => {
              this.comments = response.data.comments;
              this.commentsInitialized = 'initialized';
            })
            .catch(error => {
              console.error(error);
            });
          }
        }
        else
        {
          if (invoiceNumber) {
          axios.get(`/api/comments/${invoiceNumber}`)
            .then(response => {
              this.comments = response.data;
              this.commentsInitialized = 'initialized';
            })
            .catch(error => {
              console.error(error);
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
          })
          .then(() => {
            this.newComment = '';
            this.fetchComments();
          })
          .catch(error => {
            console.error(error);
          });
        }
      },
  
      deleteComment(commentId) {
        this.commentToDeleteId = commentId;
        this.showDeleteModal = true;
      },
  
      confirmDelete() {
        if (this.commentToDeleteId) {
          axios.delete(`/delete/load/${this.editedInvoice['load_number']}/invoice/${this.editedInvoice['invoice_number']}/comment/${this.commentToDeleteId}`)
            .then(() => {
              this.fetchComments();
              this.showDeleteModal = false;
            })
            .catch(error => {
              console.error('kazkas blg');
              this.showDeleteModal = false;
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
          })
          .then(() => {
            this.fetchComments();
            this.showEditModal = false;
          })
          .catch(error => {
            console.error(error);
            this.showEditModal = false;
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
        })
        .then(() => {
          this.showEditInvoiceModal = false;
          this.fetchInvoiceDetails();
        })
        .catch(error => {
          console.error(error);
          this.showEditInvoiceModal = false;
        });
      },
      openDeleteInvoiceModal() {
            this.showDeleteInvoiceModal = true;
      },

      deleteInvoice() {
        const invoiceNumber = this.editedInvoice['invoice_number'];

        axios.delete(`/api/comments/invoice/delete/${invoiceNumber}`)
        .then(() => {
            axios.delete(`/delete/load/${this.editedInvoice['load_number']}/invoice/${this.editedInvoice['invoice_number']}`)
            .then(() => {
                this.showDeleteModal = false;
                window.location.href = '/invoices';
            })
            .catch(error => {
                console.error(error);
                this.showDeleteModal = false;
            });
        })
        .catch(error => {
            console.error(error);
            this.showDeleteModal = false;
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

.header2 {
  text-align: center;
  margin-top: 30px;
  margin-right: 1235px;
  font-size: 1.7em;
  color: #fffffe;
  font-weight: bold;
}

.invoice-details {
  width: 80%;
  margin: 40px auto;
  margin-right: 80px;
  background-color: #ffc0ad;
  color: #271c19;
  padding: 10px 15px;
  border: 1px solid #0b0a09;
}

.invoice-details div {
  margin-bottom: 10px;
}

.comments-section {
    background-color: #f2f2f2;
    padding: 15px;
    border: 1px solid #e0e0e0;
    margin-top: 40px;
    margin-left: 80px;
    border-radius: 5px;
    width: 80%;
}

.comments-section .header {
    font-weight: bold;
    font-size: 1.2em;
    margin-bottom: 10px;
}

.comments-section div {
    padding: 10px;
    background-color: #ffffff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px;
}

.comments-section div img {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    vertical-align: middle;
    margin-right: 10px;
}

.add-comment {
    margin-top: 20px;
}

.add-comment textarea {
    width: 90%;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    min-height: 80px;
}

.add-comment button {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 20px;
    background-color: #007bff;
    color: #ffffff;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.add-comment button:hover {
    background-color: #0056b3;
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