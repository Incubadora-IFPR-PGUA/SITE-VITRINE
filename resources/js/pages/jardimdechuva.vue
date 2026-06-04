<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import axios from 'axios'
import VueApexCharts from 'vue3-apexcharts'

const rawData = ref(null)
const loading = ref(false)
const error = ref(null)
let intervalId = null

const filterPeriod = ref('all')

const filteredData = computed(() => {
  if (!rawData.value) return []
  
  if (filterPeriod.value === 'all') return rawData.value

  const limitDate = new Date()

  if (filterPeriod.value === 'today') {
    limitDate.setHours(0, 0, 0, 0)
  } else if (filterPeriod.value === '7days') {
    limitDate.setDate(limitDate.getDate() - 7)
  } else if (filterPeriod.value === '30days') {
    limitDate.setDate(limitDate.getDate() - 30)
  }

  return rawData.value.filter(item => {
    const itemDate = new Date(item.data_hora)
    return itemDate >= limitDate
  })
})

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
    const response = await axios.get('https://apijardimdechuva.incubadoraifpr.com.br/api/v1//leituras')
    rawData.value = response.data
  } catch (err) {
    console.error('Erro ao buscar dados do jardim de chuva:', err)
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
  if (!filteredData.value || filteredData.value.length === 0) return {}

  const dates = [...filteredData.value].reverse().map(item => {
    const d = new Date(item.data_hora)
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
  if (!filteredData.value || filteredData.value.length === 0) return []

  return [
    {
      name: 'PM 2.5',
      data: [...filteredData.value].reverse().map(item => {
        return item.valor_json && item.valor_json.pm25 ? Number(item.valor_json.pm25).toFixed(2) : 0
      })
    },
    {
      name: 'PM 10',
      data: [...filteredData.value].reverse().map(item => {
        return item.valor_json && item.valor_json.pm10 ? Number(item.valor_json.pm10).toFixed(2) : 0
      })
    }
  ]
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex align-center justify-space-between mb-4">
        <div>
          <h2 class="text-2xl font-weight-bold">Jardim de Chuva</h2>
          <p class="text-medium-emphasis">Monitoramento em tempo real do Jardim de Chuva</p>
        </div>
        <VBtn color="primary" @click="fetchData(false)" :loading="loading" prepend-icon="tabler-refresh">
          Atualizar Dados
        </VBtn>
      </div>
    </VCol>

    <!-- Chart Card -->
    <VCol cols="12">
      <VCard>
        <VCardItem>
          <VCardTitle>Concentração de Partículas</VCardTitle>
          <VCardSubtitle>Variação de PM2.5 e PM10</VCardSubtitle>
          <template #append>
            <div style="width: 200px;">
              <VSelect
                v-model="filterPeriod"
                :items="[
                  { title: 'Tudo', value: 'all' },
                  { title: 'Hoje', value: 'today' },
                  { title: 'Últimos 7 dias', value: '7days' },
                  { title: 'Últimos 30 dias', value: '30days' },
                ]"
                density="compact"
                hide-details
                variant="outlined"
              />
            </div>
          </template>
        </VCardItem>
        <VCardText>
          <div v-if="loading && (!filteredData || filteredData.length === 0)" class="d-flex justify-center align-center" style="height: 350px;">
            <VProgressCircular indeterminate color="primary"></VProgressCircular>
          </div>
          <VueApexCharts
            v-else-if="filteredData && filteredData.length > 0"
            type="area"
            height="350"
            :options="chartOptions"
            :series="chartSeries"
          />
          <div v-else-if="!loading" class="text-center py-5">Nenhum dado encontrado para o período selecionado.</div>
        </VCardText>
      </VCard>
    </VCol>

    <!-- Table Card -->
    <VCol cols="12">
      <VCard title="Registros Detalhados" subtitle="Retorno da API apijardimdechuva.incubadoraifpr.com.br">
        <VCardText>
          <div v-if="loading && (!filteredData || filteredData.length === 0)" class="d-flex justify-center align-center py-10">
            <VProgressCircular indeterminate color="primary"></VProgressCircular>
          </div>
          
          <VAlert v-else-if="error" type="error" variant="tonal" class="mb-4">
            Erro ao conectar com a API: {{ error }}
          </VAlert>

          <div v-else class="rounded border">
             <VTable v-if="filteredData && filteredData.length > 0" fixed-header height="500px" hover>
               <thead>
                 <tr>
                   <th class="text-left font-weight-bold text-uppercase">ID Leitura</th>
                   <th class="text-left font-weight-bold text-uppercase">Data / Hora</th>
                   <th class="text-left font-weight-bold text-uppercase">PM2.5</th>
                   <th class="text-left font-weight-bold text-uppercase">PM10</th>
                 </tr>
               </thead>
               <tbody>
                 <tr v-for="item in filteredData" :key="item.id_leitura">
                   <td>{{ item.id_leitura }}</td>
                   <td>{{ formatDate(item.data_hora) }}</td>
                   <td>
                     <VChip color="primary" size="small" variant="outlined" class="font-weight-medium">
                       {{ item.valor_json && item.valor_json.pm25 ? Number(item.valor_json.pm25).toFixed(2) : '-' }}
                     </VChip>
                   </td>
                   <td>
                     <VChip color="secondary" size="small" variant="outlined" class="font-weight-medium">
                       {{ item.valor_json && item.valor_json.pm10 ? Number(item.valor_json.pm10).toFixed(2) : '-' }}
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
