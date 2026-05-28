<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const rawData = ref(null)
const loading = ref(false)
const error = ref(null)

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
const fetchData = async () => {
  loading.value = true
  error.value = null
  try {
    const response = await axios.get('https://apiparticula.incubadoraifpr.com.br/api/data')
    rawData.value = response.data
  } catch (err) {
    console.error('Erro ao buscar dados das partículas do ar:', err)
    error.value = err.message || 'Erro desconhecido'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchData()
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <div class="d-flex align-center justify-space-between mb-4">
        <div>
          <h2 class="text-2xl font-weight-bold">Partículas do Ar</h2>
          <p class="text-medium-emphasis">Visualização de dados brutos da API</p>
        </div>
        <VBtn color="primary" @click="fetchData" :loading="loading" prepend-icon="tabler-refresh">
          Atualizar Dados
        </VBtn>
      </div>
    </VCol>

    <VCol cols="12">
      <VCard title="Dados Brutos" subtitle="Retorno da API apiparticulas.incubadoraifpr.com.br">
        <VCardText>
          <div v-if="loading" class="d-flex justify-center align-center py-10">
            <VProgressCircular indeterminate color="primary"></VProgressCircular>
          </div>
          
          <VAlert v-else-if="error" type="error" variant="tonal" class="mb-4">
            Erro ao conectar com a API: {{ error }}
          </VAlert>

          <div v-else class="rounded border">
             <VTable v-if="rawData && rawData.length > 0" fixed-header height="500px">
               <thead>
                 <tr>
                   <th class="text-left">ID</th>
                   <th class="text-left">Data / Hora</th>
                   <th class="text-left">PM2.5</th>
                   <th class="text-left">Qualidade PM2.5</th>
                   <th class="text-left">PM10</th>
                   <th class="text-left">Qualidade PM10</th>
                 </tr>
               </thead>
               <tbody>
                 <tr v-for="item in rawData" :key="item.id">
                   <td>{{ item.id }}</td>
                   <td>{{ formatDate(item.createdAt) }}</td>
                   <td>{{ Number(item.pm25).toFixed(2) }}</td>
                   <td>
                     <VChip :color="item.qualityPm25 === 'Boa' ? 'success' : (item.qualityPm25 === 'Moderada' ? 'warning' : 'error')" size="small">
                       {{ item.qualityPm25 }}
                     </VChip>
                   </td>
                   <td>{{ Number(item.pm10).toFixed(2) }}</td>
                   <td>
                     <VChip :color="item.qualityPm10 === 'Boa' ? 'success' : (item.qualityPm10 === 'Moderada' ? 'warning' : 'error')" size="small">
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
