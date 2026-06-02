<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import axios from 'axios'
import VueApexCharts from 'vue3-apexcharts'

const rawData = ref(null)
const loading = ref(false)
const error = ref(null)
let intervalId = null

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  }).format(date)
}

// Fetch Data
const fetchData = async (isBackground = false) => {
  if (!isBackground) loading.value = true
  error.value = null
  try {
    const response = await axios.get('https://apiparticula.incubadoraifpr.com.br/api/data')
    rawData.value = response.data
  } catch (err) {
    console.error('Erro ao buscar dados das partículas do ar:', err)
    if (!isBackground) error.value = err.message || 'Erro desconhecido'
  } finally {
    if (!isBackground) loading.value = false
  }
}

onMounted(() => {
  fetchData()
  // Atualiza em tempo real a cada 10s
  intervalId = setInterval(() => {
    fetchData(true)
  }, 10000)
})

onUnmounted(() => {
  if (intervalId) clearInterval(intervalId)
})

// Chart Options
const chartOptions = computed(() => {
  if (!rawData.value || rawData.value.length === 0) return {}

  const dates = [...rawData.value].reverse().map(item => {
    const d = new Date(item.createdAt)
    return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth()+1).toString().padStart(2, '0')} ${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`
  })

  return {
    chart: {
      type: 'area',
      height: 350,
      zoom: { enabled: false },
      toolbar: { show: false },
      animations: {
        enabled: true,
        easing: 'linear',
        dynamicAnimation: { speed: 1000 }
      }
    },
    colors: ['#00e396', '#ff4560'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 3 },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.1,
        stops: [0, 90, 100]
      }
    },
    xaxis: {
      categories: dates,
      labels: {
        style: { colors: '#9e9e9e' }
      }
    },
    yaxis: {
      title: { text: 'Concentração (µg/m³)' },
      labels: {
        style: { colors: '#9e9e9e' }
      }
    },
    tooltip: { theme: 'dark' },
    legend: {
      position: 'top',
      horizontalAlign: 'right'
    }
  }
})

const chartSeries = computed(() => {
  if (!rawData.value || rawData.value.length === 0) return []

  return [
    {
      name: 'PM 2.5',
      data: [...rawData.value].reverse().map(item => Number(item.pm25).toFixed(2))
    },
    {
      name: 'PM 10',
      data: [...rawData.value].reverse().map(item => Number(item.pm10).toFixed(2))
    }
  ]
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex align-center justify-space-between mb-4">
        <div>
          <h2 class="text-2xl font-weight-bold">Partículas do Ar</h2>
          <p class="text-medium-emphasis">Monitoramento de qualidade do ar em tempo real</p>
        </div>
        <VBtn color="primary" @click="fetchData(false)" :loading="loading" prepend-icon="tabler-refresh">
          Atualizar Dados
        </VBtn>
      </div>
    </VCol>

    <!-- Chart Card -->
    <VCol cols="12">
      <VCard title="Concentração de Partículas" subtitle="Variação de PM2.5 e PM10 no ar">
        <VCardText>
          <div v-if="loading && (!rawData || rawData.length === 0)" class="d-flex justify-center align-center" style="height: 350px;">
            <VProgressCircular indeterminate color="primary"></VProgressCircular>
          </div>
          <VueApexCharts
            v-else-if="rawData && rawData.length > 0"
            type="area"
            height="350"
            :options="chartOptions"
            :series="chartSeries"
          />
          <div v-else-if="!loading" class="text-center py-5">Nenhum dado encontrado.</div>
        </VCardText>
      </VCard>
    </VCol>

    <!-- Table Card -->
    <VCol cols="12">
      <VCard title="Registros Detalhados" subtitle="Retorno da API apiparticulas.incubadoraifpr.com.br">
        <VCardText>
          <div v-if="loading && (!rawData || rawData.length === 0)" class="d-flex justify-center align-center py-10">
            <VProgressCircular indeterminate color="primary"></VProgressCircular>
          </div>
          
          <VAlert v-else-if="error" type="error" variant="tonal" class="mb-4">
            Erro ao conectar com a API: {{ error }}
          </VAlert>

          <div v-else class="rounded border">
             <VTable v-if="rawData && rawData.length > 0" fixed-header height="500px" hover>
               <thead>
                 <tr>
                   <th class="text-left font-weight-bold text-uppercase">ID</th>
                   <th class="text-left font-weight-bold text-uppercase">Data / Hora</th>
                   <th class="text-left font-weight-bold text-uppercase">PM2.5</th>
                   <th class="text-left font-weight-bold text-uppercase">Qualidade PM2.5</th>
                   <th class="text-left font-weight-bold text-uppercase">PM10</th>
                   <th class="text-left font-weight-bold text-uppercase">Qualidade PM10</th>
                 </tr>
               </thead>
               <tbody>
                 <tr v-for="item in rawData" :key="item.id">
                   <td>{{ item.id }}</td>
                   <td>{{ formatDate(item.createdAt) }}</td>
                   <td>{{ Number(item.pm25).toFixed(2) }}</td>
                   <td>
                     <VChip :color="item.qualityPm25 === 'Boa' ? 'success' : (item.qualityPm25 === 'Moderada' ? 'warning' : 'error')" size="small" class="font-weight-medium">
                       {{ item.qualityPm25 }}
                     </VChip>
                   </td>
                   <td>{{ Number(item.pm10).toFixed(2) }}</td>
                   <td>
                     <VChip :color="item.qualityPm10 === 'Boa' ? 'success' : (item.qualityPm10 === 'Moderada' ? 'warning' : 'error')" size="small" class="font-weight-medium">
                       {{ item.qualityPm10 }}
                     </VChip>
                   </td>
                 </tr>
               </tbody>
             </VTable>
             <div v-else class="text-center py-5 text-medium-emphasis">Nenhum dado recebido ou lista vazia.</div>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
