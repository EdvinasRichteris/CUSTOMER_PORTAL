<template>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  
    <div class="app-container">
      <div class="header">
        Comment Details
      </div>
  
      <div class="flex-container">
        <div class="invoice-details" v-if="comment">
          <div><strong>Comment Id:</strong> {{ comment.comment['id'] }}</div>
          <div><strong>Comment:</strong> {{ comment.comment['comment'] }}</div>
        </div>
  
        <div v-else>
          Loading...
        </div>
      </div>
  
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        comment: null,
      };
    },
    mounted() {
      this.fetchCommentDetails();
    },
    methods: {
      fetchCommentDetails() {
        const segments = window.location.pathname.split('/');
        const loadNumber = segments[segments.length - 3];
        const invoiceNumber = segments[segments.length - 2];
        const commentId = segments[segments.length - 1];

        if (commentId) {
            axios.get(`/get/${loadNumber}/${invoiceNumber}/${commentId}`, { headers: { 'Authorization': `Bearer ${localStorage.getItem('access_token')}` } })
            .then(response => {
                this.comment = { ...response.data};
                console.log(this.comment);
            })
            .catch(error => {
                console.error(error);
                if ((error.response && error.response.status === 401) || (error.response && error.response.status === 500)) {
                  window.location.href = '/';
                }
            });
        } else {
            console.error("Comment Id is not available in the URL.");
        }
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