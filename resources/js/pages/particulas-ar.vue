<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const rawData = ref(null)
const loading = ref(false)
const error = ref(null)

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

          <div v-else class="bg-var-theme-background rounded p-4 border" style="max-height: 500px; overflow-y: auto;">
             <pre v-if="rawData" class="text-caption" style="white-space: pre-wrap;">{{ JSON.stringify(rawData, null, 2) }}</pre>
             <div v-else class="text-center py-5 text-medium-emphasis">Nenhum dado recebido (null ou vazio).</div>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
