<template>
  <AdminLayout>
    <div class="container mx-auto">
      <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">
        Sistem Ayarları
      </h1>
      
      <!-- Settings Navigation Tabs -->
      <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px">
          <li class="mr-2">
            <button 
              @click="activeTab = 'general'"
              class="flex px-4 py-2 font-medium text-sm rounded-t-lg items-center"
              :class="activeTab === 'general' ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Genel Ayarlar
            </button>
          </li>
          <li class="mr-2">
            <button 
              @click="activeTab = 'smtp'"
              class="flex px-4 py-2 font-medium text-sm rounded-t-lg items-center"
              :class="activeTab === 'smtp' ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              E-posta Ayarları
            </button>
          </li>
          <li class="mr-2">
            <button 
              @click="activeTab = 'payment'"
              class="flex px-4 py-2 font-medium text-sm rounded-t-lg items-center"
              :class="activeTab === 'payment' ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
              Ödeme Ayarları
            </button>
          </li>
          <li>
            <button 
              @click="activeTab = 'security'"
              class="flex px-4 py-2 font-medium text-sm rounded-t-lg items-center"
              :class="activeTab === 'security' ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
              Güvenlik
            </button>
          </li>
        </ul>
      </div>
      
      <!-- Settings Content -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- General Settings Tab -->
        <div v-if="activeTab === 'general'" class="p-6">
          <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-5 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Genel Ayarlar
          </h2>
          
          <form @submit.prevent="saveGeneralSettings">
            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
              <div>
                <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site Adı</label>
                <input 
                  id="site_name" 
                  type="text" 
                  v-model="generalSettings.site_name"
                  class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                >
              </div>
              
              <div>
                <label for="site_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site URL</label>
                <input 
                  id="site_url" 
                  type="url" 
                  v-model="generalSettings.site_url"
                  class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                >
              </div>
              
              <div>
                <label for="admin_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Admin E-posta</label>
                <input 
                  id="admin_email" 
                  type="email" 
                  v-model="generalSettings.admin_email"
                  class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                >
              </div>
              
              <div>
                <label for="date_format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tarih Formatı</label>
                <select 
                  id="date_format" 
                  v-model="generalSettings.date_format"
                  class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                >
                  <option value="d.m.Y">DD.MM.YYYY (31.12.2025)</option>
                  <option value="d/m/Y">DD/MM/YYYY (31/12/2025)</option>
                  <option value="Y-m-d">YYYY-MM-DD (2025-12-31)</option>
                  <option value="m/d/Y">MM/DD/YYYY (12/31/2025)</option>
                </select>
              </div>
            </div>
            
            <div class="mb-6">
              <label for="site_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site Açıklaması</label>
              <textarea 
                id="site_description" 
                v-model="generalSettings.site_description"
                rows="3"
                class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
              ></textarea>
            </div>
            
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center">
                <input 
                  id="maintenance_mode" 
                  type="checkbox"
                  v-model="generalSettings.maintenance_mode"
                  class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
                >
                <label for="maintenance_mode" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Bakım Modu</label>
              </div>
              
              <div class="flex items-center">
                <input 
                  id="enable_registration" 
                  type="checkbox"
                  v-model="generalSettings.enable_registration"
                  class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
                >
                <label for="enable_registration" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Kayıt Olmayı Etkinleştir</label>
              </div>
            </div>
            
            <div class="flex justify-end">
              <button 
                type="submit"
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
              >
                Ayarları Kaydet
              </button>
            </div>
          </form>
        </div>
        
        <!-- Email Settings Tab -->
        <div v-if="activeTab === 'smtp'" class="p-6">
          <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-5 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            E-posta Ayarları
          </h2>
          
          <form @submit.prevent="saveSmtpSettings">
            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
              <div>
                <label for="smtp_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SMTP Host</label>
                <input 
                  id="smtp_host" 
                  type="text" 
                  v-model="smtpSettings.host"
                  class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                >
              </div>
              
              <div>
                <label for="smtp_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SMTP Port</label>
                <input 
                  id="smtp_port" 
                  type="number" 
                  v-model="smtpSettings.port"
                  class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                >
              </div>
              
              <div>
                <label for="smtp_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SMTP Kullanıcı Adı</label>
                <input 
                  id="smtp_username" 
                  type="text" 
                  v-model="smtpSettings.username"
                  class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                >
              </div>
              
              <div>
                <label for="smtp_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SMTP Şifre</label>
                <input 
                  id="smtp_password" 
                  type="password" 
                  v-model="smtpSettings.password"
                  class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                >
              </div>
              
              <div>
                <label for="smtp_encryption" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Şifreleme</label>
                <select 
                  id="smtp_encryption" 
                  v-model="smtpSettings.encryption"
                  class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                >
                  <option value="tls">TLS</option>
                  <option value="ssl">SSL</option>
                  <option value="none">Yok</option>
                </select>
              </div>
              
              <div>
                <label for="from_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gönderen E-posta</label>
                <input 
                  id="from_address" 
                  type="email" 
                  v-model="smtpSettings.from_address"
                  class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                >
              </div>
            </div>
            
            <div class="flex items-center mb-6">
              <input 
                id="smtp_enabled" 
                type="checkbox"
                v-model="smtpSettings.enabled"
                class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
              >
              <label for="smtp_enabled" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">SMTP Gönderimi Etkinleştir</label>
            </div>
            
            <div class="flex justify-between">
              <button 
                type="button"
                @click="testSmtpConnection"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
              >
                Bağlantıyı Test Et
              </button>
              
              <button 
                type="submit"
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
              >
                Ayarları Kaydet
              </button>
            </div>
          </form>
        </div>
        
        <!-- Payment Settings Tab -->
        <div v-if="activeTab === 'payment'" class="p-6">
          <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-5 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            Ödeme Ayarları
          </h2>
          
          <form @submit.prevent="savePaymentSettings">
            <div class="mb-6">
              <h3 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                </svg>
                Ödeme Yöntemleri
              </h3>
              <div class="space-y-3">
                <div class="flex items-center">
                  <input 
                    id="credit_card" 
                    type="checkbox"
                    v-model="paymentSettings.methods.credit_card"
                    class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
                  >
                  <label for="credit_card" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Kredi Kartı</label>
                </div>
                
                <div class="flex items-center">
                  <input 
                    id="bank_transfer" 
                    type="checkbox"
                    v-model="paymentSettings.methods.bank_transfer"
                    class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
                  >
                  <label for="bank_transfer" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Banka Havalesi</label>
                </div>
                
                <div class="flex items-center">
                  <input 
                    id="pay_at_office" 
                    type="checkbox"
                    v-model="paymentSettings.methods.pay_at_office"
                    class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
                  >
                  <label for="pay_at_office" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Ofiste Ödeme</label>
                </div>
              </div>
            </div>
            
            <div class="mb-6">
              <h3 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Sanal POS Ayarları
              </h3>
              
              <div class="grid grid-cols-1 gap-6 mb-4 md:grid-cols-2">
                <div>
                  <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Para Birimi</label>
                  <select 
                    id="currency" 
                    v-model="paymentSettings.currency"
                    class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                  >
                    <option value="TRY">Türk Lirası (₺)</option>
                    <option value="USD">Amerikan Doları ($)</option>
                    <option value="EUR">Euro (€)</option>
                    <option value="GBP">İngiliz Sterlini (£)</option>
                  </select>
                </div>
                
                <div>
                  <label for="pos_provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">POS Sağlayıcı</label>
                  <select 
                    id="pos_provider" 
                    v-model="paymentSettings.pos_provider"
                    class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                  >
                    <option value="iyzico">Iyzico</option>
                    <option value="paytr">PayTR</option>
                    <option value="payu">PayU</option>
                  </select>
                </div>
                
                <div>
                  <label for="api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">API Key</label>
                  <input 
                    id="api_key" 
                    type="text" 
                    v-model="paymentSettings.api_key"
                    class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                  >
                </div>
                
                <div>
                  <label for="secret_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Secret Key</label>
                  <input 
                    id="secret_key" 
                    type="password" 
                    v-model="paymentSettings.secret_key"
                    class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                  >
                </div>
              </div>
              
              <div class="flex items-center mb-4">
                <input 
                  id="test_mode" 
                  type="checkbox"
                  v-model="paymentSettings.test_mode"
                  class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
                >
                <label for="test_mode" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Test Modu</label>
              </div>
            </div>
            
            <div class="flex justify-end">
              <button 
                type="submit"
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
              >
                Ayarları Kaydet
              </button>
            </div>
          </form>
        </div>
        
        <!-- Security Settings Tab -->
        <div v-if="activeTab === 'security'" class="p-6">
          <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-5 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            Güvenlik Ayarları
          </h2>
          
          <form @submit.prevent="saveSecuritySettings">
            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
              <div>
                <label for="session_lifetime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Oturum Süresi (dakika)</label>
                <input 
                  id="session_lifetime" 
                  type="number" 
                  v-model="securitySettings.session_lifetime"
                  class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                >
              </div>
              
              <div>
                <label for="password_min_length" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Minimum Şifre Uzunluğu</label>
                <input 
                  id="password_min_length" 
                  type="number" 
                  v-model="securitySettings.password_min_length"
                  class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                >
              </div>
            </div>
            
            <div class="mb-6">
              <h3 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Şifre Gereksinimleri
              </h3>
              <div class="space-y-3">
                <div class="flex items-center">
                  <input 
                    id="require_uppercase" 
                    type="checkbox"
                    v-model="securitySettings.password_requirements.uppercase"
                    class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
                  >
                  <label for="require_uppercase" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Büyük harf gerekli</label>
                </div>
                
                <div class="flex items-center">
                  <input 
                    id="require_number" 
                    type="checkbox"
                    v-model="securitySettings.password_requirements.number"
                    class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
                  >
                  <label for="require_number" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Rakam gerekli</label>
                </div>
                
                <div class="flex items-center">
                  <input 
                    id="require_special_char" 
                    type="checkbox"
                    v-model="securitySettings.password_requirements.special_char"
                    class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
                  >
                  <label for="require_special_char" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Özel karakter gerekli</label>
                </div>
              </div>
            </div>
            
            <div class="mb-6">
              <div class="flex items-center">
                <input 
                  id="enable_recaptcha" 
                  type="checkbox"
                  v-model="securitySettings.enable_recaptcha"
                  class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
                >
                <label for="enable_recaptcha" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Google reCAPTCHA Etkinleştir</label>
              </div>
              
              <div v-if="securitySettings.enable_recaptcha" class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                  <label for="recaptcha_site_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">reCAPTCHA Site Key</label>
                  <input 
                    id="recaptcha_site_key" 
                    type="text" 
                    v-model="securitySettings.recaptcha_site_key"
                    class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                  >
                </div>
                
                <div>
                  <label for="recaptcha_secret_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">reCAPTCHA Secret Key</label>
                  <input 
                    id="recaptcha_secret_key" 
                    type="text" 
                    v-model="securitySettings.recaptcha_secret_key"
                    class="px-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                  >
                </div>
              </div>
            </div>
            
            <div class="flex justify-end">
              <button 
                type="submit"
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
              >
                Ayarları Kaydet
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();
const activeTab = ref('general');

// Genel Ayarlar
const generalSettings = ref({
  site_name: 'Tur Projesi',
  site_url: 'https://turprojesi.com',
  admin_email: 'admin@turprojesi.com',
  date_format: 'd.m.Y',
  site_description: 'En iyi tur deneyimleri için Tur Projesi! Kapadokya, Pamukkale, Efes ve daha fazlası.',
  maintenance_mode: false,
  enable_registration: true
});

// SMTP Ayarları
const smtpSettings = ref({
  enabled: true,
  host: 'smtp.example.com',
  port: 587,
  username: 'noreply@turprojesi.com',
  password: '********',
  encryption: 'tls',
  from_address: 'noreply@turprojesi.com'
});

// Ödeme Ayarları
const paymentSettings = ref({
  methods: {
    credit_card: true,
    bank_transfer: true,
    pay_at_office: false
  },
  currency: 'TRY',
  pos_provider: 'iyzico',
  api_key: 'sandbox-xxxxxxx',
  secret_key: '********',
  test_mode: true
});

// Güvenlik Ayarları
const securitySettings = ref({
  session_lifetime: 120,
  password_min_length: 8,
  password_requirements: {
    uppercase: true,
    number: true,
    special_char: false
  },
  enable_recaptcha: false,
  recaptcha_site_key: '',
  recaptcha_secret_key: ''
});

// Ayarları Kaydet (Genel)
const saveGeneralSettings = () => {
  // Burada API isteği yapılacak
  toast.success('Genel ayarlar başarıyla kaydedildi!');
};

// Ayarları Kaydet (SMTP)
const saveSmtpSettings = () => {
  // Burada API isteği yapılacak
  toast.success('E-posta ayarları başarıyla kaydedildi!');
};

// SMTP Bağlantı Testi
const testSmtpConnection = () => {
  // Burada API isteği yapılacak
  toast.info('SMTP bağlantı testi başlatıldı...');
  
  // Test başarılı varsayalım
  setTimeout(() => {
    toast.success('SMTP bağlantı testi başarılı!');
  }, 1500);
};

// Ayarları Kaydet (Ödeme)
const savePaymentSettings = () => {
  // Burada API isteği yapılacak
  toast.success('Ödeme ayarları başarıyla kaydedildi!');
};

// Ayarları Kaydet (Güvenlik)
const saveSecuritySettings = () => {
  // Burada API isteği yapılacak
  toast.success('Güvenlik ayarları başarıyla kaydedildi!');
};
</script> 