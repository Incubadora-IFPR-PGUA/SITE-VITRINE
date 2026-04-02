<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import VueApexCharts from 'vue3-apexcharts'

const phData = ref([])
const loading = ref(true)

// Fetch Data
const fetchData = async () => {
  loading.value = true
  try {
    const response = await axios.get('https://apivitrineprototipos.incubadoraifpr.com.br/listarPh')
    phData.value = response.data
  } catch (error) {
    console.error('Error fetching pH data:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchData()
})

// Chart Options
const chartOptions = computed(() => {
  const dates = [...phData.value].reverse().map(item => {
    const d = new Date(item.data_hora_atualizacao)
    return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth()+1).toString().padStart(2, '0')} ${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`
  })

  return {
    chart: {
      type: 'line',
      height: 350,
      zoom: { enabled: false },
      toolbar: { show: false }
    },
    colors: ['#00e396'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 3 },
    xaxis: {
      categories: dates,
      labels: {
        style: { colors: '#9e9e9e' }
      }
    },
    yaxis: {
      title: { text: 'pH' },
      labels: {
        style: { colors: '#9e9e9e' }
      }
    },
    tooltip: { theme: 'dark' }
  }
})

const chartSeries = computed(() => {
  return [{
    name: 'pH',
    data: [...phData.value].reverse().map(item => parseFloat(item.ph).toFixed(2))
  }]
})

// Table Headers
const headers = [
  { title: 'DATA/HORA', key: 'dataHora' },
  { title: 'PH', key: 'ph' },
  { title: 'ESCALA', key: 'escala' },
  { title: 'DISPOSITIVO (LOCAL)', key: 'dispositivo' },
]

// Formatting helper
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeStyle: 'short' }).format(date)
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex align-center justify-space-between mb-4">
        <div>
          <h2 class="text-2xl font-weight-bold">Dashboard - Phmetro</h2>
          <p class="text-medium-emphasis">Monitoramento de pH em tempo real</p>
        </div>
        <VBtn color="primary" @click="fetchData" :loading="loading" prepend-icon="tabler-refresh">
          Atualizar Dados
        </VBtn>
      </div>
    </VCol>

    <!-- Chart Card -->
    <VCol cols="12">
      <VCard title="Variação de pH" subtitle="Últimas leituras registradas no sistema">
        <VCardText>
          <div v-if="loading" class="d-flex justify-center align-center" style="height: 350px;">
            <VProgressCircular indeterminate color="primary"></VProgressCircular>
          </div>
          <VueApexCharts
            v-else-if="phData.length"
            type="line"
            height="350"
            :options="chartOptions"
            :series="chartSeries"
          />
          <div v-else class="text-center py-5">Nenhum dado encontrado.</div>
        </VCardText>
      </VCard>
    </VCol>

    <!-- Table Card -->
    <VCol cols="12">
      <VCard title="Registros Detalhados">
        <VTable hover>
          <thead>
            <tr>
              <th v-for="header in headers" :key="header.key" class="text-left font-weight-bold text-uppercase">
                {{ header.title }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in phData" :key="item.id">
              <td>{{ formatDate(item.data_hora_atualizacao) }}</td>
              <td>
                <VChip :color="item.ph < 7 ? (item.ph < 0 ? 'error' : 'warning') : (item.ph > 7 ? 'info' : 'success')" size="small" class="font-weight-medium">
                  {{ parseFloat(item.ph).toFixed(2) }}
                </VChip>
              </td>
              <td>{{ item.escala }}</td>
              <td>
                <div v-if="item.macAddress">
                  <span class="font-weight-bold d-block">{{ item.macAddress.nome }}</span>
                  <span class="text-caption text-medium-emphasis d-block">{{ item.macAddress.descricao }}</span>
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
