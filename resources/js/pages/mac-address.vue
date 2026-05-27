<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const macData = ref([])
const loading = ref(true)

// Fetch Data
const fetchData = async () => {
  loading.value = true
  try {
    const baseUrl = import.meta.env.VITE_API_MACADDRESS_URL || 'https://apimacaddress.incubadoraifpr.com.br'
    const response = await axios.get(`${baseUrl}/mac`)
    macData.value = response.data
  } catch (error) {
    console.error('Error fetching MAC Address data:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchData()
})

// Table Headers
const headers = [
  { title: 'DATA/HORA CAPTURA', key: 'dataHora' },
  { title: 'MAC ADDRESS', key: 'mac' },
  { title: 'FABRICANTE', key: 'fabricante' },
  { title: 'DISPOSITIVO (LOCAL)', key: 'dispositivo' },
]

// Formatting helper
const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeStyle: 'short' }).format(date)
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex align-center justify-space-between mb-4">
        <div>
          <h2 class="text-2xl font-weight-bold">Dashboard - Mac Address</h2>
          <p class="text-medium-emphasis">Monitoramento de captura de endereços MAC</p>
        </div>
        <VBtn color="primary" @click="fetchData" :loading="loading" prepend-icon="tabler-refresh">
          Atualizar Dados
        </VBtn>
      </div>
    </VCol>

    <!-- Table Card -->
    <VCol cols="12">
      <VCard title="Registros de Captura">
        <VCardText v-if="loading" class="d-flex justify-center align-center py-10">
          <VProgressCircular indeterminate color="primary"></VProgressCircular>
        </VCardText>
        
        <VTable v-else hover>
          <thead>
            <tr>
              <th v-for="header in headers" :key="header.key" class="text-left font-weight-bold text-uppercase">
                {{ header.title }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="macData.length === 0">
              <td colspan="4" class="text-center text-medium-emphasis py-5">Nenhum dado encontrado.</td>
            </tr>
            <tr v-for="item in macData" :key="item.id">
              <td>{{ formatDate(item.data_hora_captura) }}</td>
              <td>
                <span class="font-weight-medium">{{ item.MAC }}</span>
              </td>
              <td>{{ item.fabricante || 'Desconhecido' }}</td>
              <td>
                <div v-if="item.macAddress_esp">
                  <span class="font-weight-bold d-block">{{ item.macAddress_esp.nome }}</span>
                  <span v-if="item.macAddress_esp.descricao" class="text-caption text-medium-emphasis d-block">{{ item.macAddress_esp.descricao }}</span>
                </div>
                <div v-else class="text-medium-emphasis text-caption">Não especificado</div>
              </td>
            </tr>
          </tbody>
        </VTable>
      </VCard>
    </VCol>
  </VRow>
</template>
