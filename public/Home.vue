<template>
    <div class="app-container">
      <div class="tabs">
        <a href="javascript:void(0)" class="tab" :class="{ active: activeTab === 'home' }" @click="setActiveTab('home')">Home</a>
        <a href="/loads" class="tab" :class="{ active: activeTab === 'loads' }" @click="setActiveTab('loads')">My Loads</a>
        <a href="/invoices" class="tab" :class="{ active: activeTab === 'invoices' }" @click="setActiveTab('invoices')">My Invoices</a>
      </div>
      <div v-if="activeTab === 'home'">
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        activeTab: 'home', 
      };
    },
    methods: {
      setActiveTab(tab) {
        this.activeTab = tab;
      },
    },
  };
  </script>
  
  <style>
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
  
  .tabs {
    display: flex;
    justify-content: center;
    gap: 10px;
  }
  
  .tabs {
    background-color: #271C19;
    color: #fffffe;
    padding: 20px 0;
    text-align: center;
    font-size: 2em;
    font-weight: bold;

    display: flex;
    justify-content: center;
    gap: 10px;
  }
  
  .tab:hover {
    background-color: rgba(152, 97, 9, 0.7);
  }
  
  </style>