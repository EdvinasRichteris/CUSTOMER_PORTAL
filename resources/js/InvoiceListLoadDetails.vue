<template>
    <div class="app-container">
      <div class="header2">
        Load's Invoices
      </div>
  
      <table class="invoice-table">
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
              <a :href="'/load/' + invoice['load_number'] + '/invoice/' + invoice['invoice_number']">
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
    </div>
  </template>
  
  <script>
  export default {
    props: ['invoices']
  }
  </script>
  
  <style scoped>
  .app-container {
    background-color: #55423d;
    min-height: 100vh;
  }
  
  .header2 {
    text-align: center;
    margin-right: 543px;
    font-size: 1.4em;
    color: #fffffe;
    font-weight: bold;
  }
  
  .invoice-table {
    width: 60%;
    margin: 20px auto;
    border-collapse: collapse;
    margin-right: 543px;
    text-align: left;
  }
  
  table, th, td {
    border: 1px solid #271C19;
  }
  
  th, td {
    padding: 8px 12px;
    text-align: left;
    font-size: 0.9em;
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
  </style>