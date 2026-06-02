<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import axios from 'axios'
import VueApexCharts from 'vue3-apexcharts'

const macData = ref([])
const loading = ref(true)

const itemsPerPage = ref(15)
const currentPage = ref(1)

const searchQuery = ref('')
const filterStatus = ref('Todos')
const filterZone = ref('Todas')

const pinnedMac = ref(localStorage.getItem('pinnedMacAddress') || null)

const togglePin = (mac) => {
  pinnedMac.value = pinnedMac.value === mac ? null : mac
  if (pinnedMac.value) {
    localStorage.setItem('pinnedMacAddress', pinnedMac.value)
  } else {
    localStorage.removeItem('pinnedMacAddress')
  }
}

// Reseta paginação quando o filtro muda
watch([searchQuery, filterStatus, filterZone], () => {
  currentPage.value = 1
})

const registrarSaida = async (item) => {
  try {
    const baseUrl = import.meta.env.VITE_API_MACADDRESS_URL || 'https://apimacaddress.incubadoraifpr.com.br'
    await axios.put(`${baseUrl}/mac/${item.id}`, {
      status: 'saida'
    })
    fetchData(false)
  } catch (error) {
    console.error('Error registrar saída:', error)
  }
}

const showClearDialog = ref(false)
const clearing = ref(false)

const showEditDialog = ref(false)
const editingItem = ref(null)
const editName = ref('')
const saving = ref(false)

const openEditDialog = (item) => {
  editingItem.value = item
  editName.value = item.nome || ''
  showEditDialog.value = true
}

const saveName = async () => {
  if (!editingItem.value) return
  saving.value = true
  try {
    const baseUrl = import.meta.env.VITE_API_MACADDRESS_URL || 'https://apimacaddress.incubadoraifpr.com.br'
    await axios.put(`${baseUrl}/mac/${editingItem.value.id}`, {
      nome: editName.value
    })
    macData.value.forEach(m => {
      if (m.MAC === editingItem.value.MAC) {
        m.nome = editName.value
      }
    })
    showEditDialog.value = false
  } catch (error) {
    console.error('Error saving name:', error)
  } finally {
    saving.value = false
  }
}

const clearDatabase = async () => {
  clearing.value = true
  try {
    const baseUrl = import.meta.env.VITE_API_MACADDRESS_URL || 'https://apimacaddress.incubadoraifpr.com.br'
    await axios.delete(`${baseUrl}/mac/limpar/todos`)
    macData.value = []
    currentPage.value = 1
    showClearDialog.value = false
  } catch (error) {
    console.error('Error clearing MAC Address data:', error)
  } finally {
    clearing.value = false
  }
}

const groupedData = computed(() => {
  const map = new Map()
  macData.value.forEach(item => {
    const key = item.MAC
    if (!map.has(key)) {
      map.set(key, {
        id: item.id,
        MAC: item.MAC,
        nome: item.nome,
        fabricante: item.fabricante,
        primeiraCaptura: new Date(item.data_hora_captura),
        ultimaCaptura: new Date(item.data_hora_captura),
        dispositivo: item.macAddress_esp ? item.macAddress_esp.nome : 'Não especificado',
        status: item.status || 'permanente',
        data_hora_saida: item.data_hora_saida,
        count: 1
      })
    } else {
      const g = map.get(key)
      const dataHora = new Date(item.data_hora_captura)
      if (dataHora < g.primeiraCaptura) g.primeiraCaptura = dataHora
      if (dataHora > g.ultimaCaptura) {
        g.ultimaCaptura = dataHora
        g.dispositivo = item.macAddress_esp ? item.macAddress_esp.nome : 'Não especificado'
        g.id = item.id
        g.status = item.status || 'permanente'
        g.data_hora_saida = item.data_hora_saida
      }
      g.count++
    }
  })
  return Array.from(map.values()).sort((a, b) => b.ultimaCaptura - a.ultimaCaptura)
})

const getStatusObj = (item) => {
  if (item.status === 'saida') {
    return { label: 'Saída', color: 'error', pulseClass: 'pulse-red' }
  }
  const diffMs = new Date() - item.ultimaCaptura
  const diffMin = diffMs / 60000
  if (diffMin <= 5) {
    return { label: 'Presente', color: 'success', pulseClass: 'pulse-green' }
  } else if (diffMin <= 15) {
    return { label: 'Ausente', color: 'warning', pulseClass: 'pulse-warning' }
  }
  return { label: 'Offline', color: 'error', pulseClass: 'pulse-red' }
}

const availableZones = computed(() => {
  const zones = new Set()
  groupedData.value.forEach(i => zones.add(i.dispositivo))
  return ['Todas', ...Array.from(zones).sort()]
})

const pinnedDevice = computed(() => {
  if (!pinnedMac.value) return null
  return groupedData.value.find(item => item.MAC === pinnedMac.value)
})

const filteredData = computed(() => {
  let data = [...groupedData.value]
  
  if (searchQuery.value) {
    const term = searchQuery.value.toLowerCase()
    data = data.filter(i => 
      i.MAC.toLowerCase().includes(term) || 
      (i.nome && i.nome.toLowerCase().includes(term)) || 
      (i.fabricante && i.fabricante.toLowerCase().includes(term))
    )
  }
  
  if (filterStatus.value !== 'Todos') {
    data = data.filter(i => getStatusObj(i).label.includes(filterStatus.value))
  }
  
  if (filterZone.value !== 'Todas') {
    data = data.filter(i => i.dispositivo === filterZone.value)
  }

  // Remove o item pinado da tabela para que não fique duplicado
  if (pinnedMac.value) {
    data = data.filter(i => i.MAC !== pinnedMac.value)
  }

  return data
})

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredData.value.slice(start, end)
})

const totalPages = computed(() => Math.ceil(filteredData.value.length / itemsPerPage.value) || 1)

const totalDispositivos = computed(() => groupedData.value.length)
const totalOnline = computed(() => groupedData.value.filter(i => i.status !== 'saida' && getStatusObj(i).label === 'Presente').length)
const totalOffline = computed(() => groupedData.value.filter(i => i.status !== 'saida' && getStatusObj(i).label !== 'Presente').length)
const activeZonesCount = computed(() => availableZones.value.length - 1)

const chartBarSeries = computed(() => {
  const zonesCount = {}
  groupedData.value.forEach(item => {
    const zone = item.dispositivo || 'Não especificado'
    zonesCount[zone] = (zonesCount[zone] || 0) + 1
  })
  return [{ name: 'Dispositivos', data: Object.values(zonesCount) }]
})

const chartBarOptions = computed(() => {
  const zonesCount = {}
  groupedData.value.forEach(item => {
    const zone = item.dispositivo || 'Não especificado'
    zonesCount[zone] = (zonesCount[zone] || 0) + 1
  })
  
  return {
    chart: { type: 'bar', toolbar: { show: false }, fontFamily: 'inherit' },
    plotOptions: { bar: { borderRadius: 4, columnWidth: '45%', distributed: true } },
    dataLabels: { enabled: false },
    colors: ['#7367F0', '#00CFE8', '#28C76F', '#FF9F43', '#EA5455', '#FF4C51'],
    xaxis: { 
      categories: Object.keys(zonesCount),
      labels: { style: { colors: 'rgba(var(--v-theme-on-surface), 0.8)' } }
    },
    yaxis: {
      labels: { style: { colors: 'rgba(var(--v-theme-on-surface), 0.8)' } }
    },
    grid: { borderColor: 'rgba(var(--v-theme-on-surface), 0.12)' },
    title: { text: 'Dispositivos por Zona', align: 'left', style: { color: 'rgba(var(--v-theme-on-surface), 0.8)', fontWeight: 600 } },
    legend: { show: false }
  }
})

const chartLineData = computed(() => {
  const timeMap = {}
  groupedData.value.forEach(item => {
    if (!item.primeiraCaptura) return
    const d = new Date(item.primeiraCaptura)
    d.setMinutes(0, 0, 0)
    const time = d.getTime()
    timeMap[time] = (timeMap[time] || 0) + 1
  })
  
  const sortedTimes = Object.keys(timeMap).sort().map(Number)
  const categories = sortedTimes.map(t => {
    const d = new Date(t)
    return `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth()+1).padStart(2, '0')} ${String(d.getHours()).padStart(2, '0')}:00`
  })
  const data = sortedTimes.map(t => timeMap[t])
  
  return { categories, data }
})

const chartLineSeries = computed(() => {
  return [{ name: 'Chegadas', data: chartLineData.value.data }]
})

const chartLineOptions = computed(() => {
  return {
    chart: { type: 'line', toolbar: { show: false }, fontFamily: 'inherit' },
    stroke: { curve: 'smooth', width: 3 },
    markers: { size: 6, strokeWidth: 2, strokeColors: 'rgb(var(--v-theme-surface))', colors: ['#00CFE8'], hover: { size: 8 } },
    colors: ['#00CFE8'],
    xaxis: { 
      categories: chartLineData.value.categories,
      labels: { style: { colors: 'rgba(var(--v-theme-on-surface), 0.8)' } }
    },
    yaxis: {
      labels: { style: { colors: 'rgba(var(--v-theme-on-surface), 0.8)' } }
    },
    grid: { borderColor: 'rgba(var(--v-theme-on-surface), 0.12)' },
    title: { text: 'Chegadas por Hora', align: 'left', style: { color: 'rgba(var(--v-theme-on-surface), 0.8)', fontWeight: 600 } }
  }
})

const getDuration = (primeira, ultima) => {
  const diffMs = ultima - primeira
  if (diffMs < 60000) return 'Menos de 1m'
  const diffMin = Math.floor(diffMs / 60000)
  const hours = Math.floor(diffMin / 60)
  const mins = diffMin % 60
  if (hours > 0) return `${hours}h ${mins}m`
  return `${mins}m`
}

const getOfflineDuration = (ultima) => {
  const diffMs = new Date() - ultima
  const diffMin = Math.floor(diffMs / 60000)
  if (diffMin < 5) return null
  const hours = Math.floor(diffMin / 60)
  const mins = diffMin % 60
  if (hours > 0) return `${hours}h ${mins}m`
  return `${mins}m`
}

const fetchData = async (showLoading = true) => {
  const shouldShowLoading = showLoading !== false
  if (shouldShowLoading) loading.value = true
  try {
    const baseUrl = import.meta.env.VITE_API_MACADDRESS_URL || 'https://apimacaddress.incubadoraifpr.com.br'
    const response = await axios.get(`${baseUrl}/mac`)
    macData.value = response.data
  } catch (error) {
    console.error('Error fetching MAC Address data:', error)
  } finally {
    if (shouldShowLoading) loading.value = false
  }
}

let fetchInterval = null
onMounted(() => {
  fetchData(true)
  fetchInterval = setInterval(() => {
    fetchData(false)
  }, 5000)
})
onUnmounted(() => {
  if (fetchInterval) clearInterval(fetchInterval)
})

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
          <h2 class="text-2xl font-weight-bold">Monitoramento de Dispositivos</h2>
          <p class="text-medium-emphasis">Tabela Inteligente - Acompanhamento em tempo real</p>
        </div>
        <div class="d-flex align-center">
          <VBtn color="error" variant="tonal" class="mr-3" @click="showClearDialog = true" :disabled="loading || clearing" prepend-icon="tabler-trash">
            Limpar Banco
          </VBtn>
          <VBtn color="primary" @click="fetchData(false)" :loading="loading" :disabled="clearing" prepend-icon="tabler-refresh">
            Atualizar Dados
          </VBtn>
        </div>
      </div>
    </VCol>
  </VRow>

  <!-- Widgets (Top Analytics) -->
  <VRow class="mb-4">
    <VCol cols="12" sm="6" md="3">
      <VCard class="glass-card text-center py-4 rounded-xl" elevation="0">
        <VCardTitle class="text-h4 font-weight-bold text-primary">{{ totalDispositivos }}</VCardTitle>
        <VCardSubtitle>Dispositivos Lidos</VCardSubtitle>
      </VCard>
    </VCol>
    <VCol cols="12" sm="6" md="3">
      <VCard class="glass-card text-center py-4 rounded-xl" elevation="0">
        <VCardTitle class="text-h4 font-weight-bold text-success d-flex justify-center align-center">
          <span class="pulse-dot pulse-green mr-3"></span>{{ totalOnline }}
        </VCardTitle>
        <VCardSubtitle>Online Agora</VCardSubtitle>
      </VCard>
    </VCol>
    <VCol cols="12" sm="6" md="3">
      <VCard class="glass-card text-center py-4 rounded-xl" elevation="0">
        <VCardTitle class="text-h4 font-weight-bold text-error d-flex justify-center align-center">
          <span class="pulse-dot pulse-red mr-3"></span>{{ totalOffline }}
        </VCardTitle>
        <VCardSubtitle>Offline / Ausentes</VCardSubtitle>
      </VCard>
    </VCol>
    <VCol cols="12" sm="6" md="3">
      <VCard class="glass-card text-center py-4 rounded-xl" elevation="0">
        <VCardTitle class="text-h4 font-weight-bold text-info">{{ activeZonesCount }}</VCardTitle>
        <VCardSubtitle>Zonas Ativas</VCardSubtitle>
      </VCard>
    </VCol>
  </VRow>

  <!-- Gráficos -->
  <VRow class="mb-4">
    <VCol cols="12" md="6">
      <VCard class="rounded-xl glass-card pa-4" elevation="0">
        <VueApexCharts type="bar" height="300" :options="chartBarOptions" :series="chartBarSeries" />
      </VCard>
    </VCol>
    <VCol cols="12" md="6">
      <VCard class="rounded-xl glass-card pa-4" elevation="0">
        <VueApexCharts type="line" height="300" :options="chartLineOptions" :series="chartLineSeries" />
      </VCard>
    </VCol>
  </VRow>

  <!-- PINNED CARD FIXO NO TOPO -->
  <VRow v-if="pinnedDevice" class="mb-4">
    <VCol cols="12">
      <VCard class="rounded-xl pa-5" style="border: 1px solid rgba(var(--v-theme-primary), 0.5); background-color: rgba(var(--v-theme-primary), 0.05);" elevation="2">
        <div class="d-flex justify-space-between align-center flex-wrap gap-4">
          <div class="d-flex align-center">
            <span :class="['pulse-dot', getStatusObj(pinnedDevice).pulseClass, 'mr-4']" style="width: 16px; height: 16px;"></span>
            <div>
              <div class="text-h5 font-weight-bold mb-1" :class="pinnedDevice.nome ? 'text-info' : 'text-primary'">
                <template v-if="pinnedDevice.nome">
                  <VIcon size="24" class="mr-1 pb-1">tabler-id-badge</VIcon>
                  {{ pinnedDevice.nome }}
                </template>
                <template v-else>
                  {{ pinnedDevice.fabricante || 'Desconhecido' }}
                </template>
                <span class="text-subtitle-1 text-medium-emphasis ml-2 font-mono">{{ pinnedDevice.MAC }}</span>
              </div>
              <div class="d-flex align-center text-body-1">
                <VIcon size="20" color="primary" class="mr-1">tabler-map-pin</VIcon>
                <span class="font-weight-medium mr-4">{{ pinnedDevice.dispositivo }}</span>
                <VIcon size="20" class="mr-1">tabler-clock</VIcon>
                <span class="mr-4">Perm: {{ getDuration(pinnedDevice.primeiraCaptura, pinnedDevice.ultimaCaptura) }}</span>
                <template v-if="getOfflineDuration(pinnedDevice.ultimaCaptura) && pinnedDevice.status !== 'saida'">
                  <VIcon size="20" class="mr-1 text-error">tabler-wifi-off</VIcon>
                  <span class="text-error font-weight-bold">Offline há: {{ getOfflineDuration(pinnedDevice.ultimaCaptura) }}</span>
                </template>
              </div>
            </div>
          </div>
          <div class="d-flex align-center">
             <VBtn v-if="pinnedDevice.status !== 'saida'" color="error" variant="tonal" class="mr-3" @click="registrarSaida(pinnedDevice)">Registrar Saída</VBtn>
             <VChip v-else size="large" color="error" variant="flat" class="mr-3 font-weight-bold">Saiu</VChip>
             <VBtn icon variant="tonal" color="warning" class="mr-2" @click="togglePin(pinnedDevice.MAC)">
               <VIcon>tabler-pin-filled</VIcon>
             </VBtn>
             <VBtn icon variant="tonal" color="primary" @click="openEditDialog(pinnedDevice)">
               <VIcon>tabler-edit</VIcon>
             </VBtn>
          </div>
        </div>
      </VCard>
    </VCol>
  </VRow>

  <!-- Barra de Filtros e Tabela -->
  <VRow>
    <VCol cols="12">
      <VCard class="rounded-xl glass-card pa-4" elevation="0">
        
        <!-- Filtros -->
        <VRow class="mb-2" align="center">
          <VCol cols="12" md="4">
            <VTextField
              v-model="searchQuery"
              label="Buscar MAC, Nome ou Fabricante"
              prepend-inner-icon="tabler-search"
              variant="outlined"
              density="compact"
              hide-details
              clearable
            ></VTextField>
          </VCol>
          <VCol cols="12" md="3">
            <VSelect
              v-model="filterStatus"
              :items="['Todos', 'Presente', 'Ausente', 'Offline', 'Saída']"
              label="Status"
              variant="outlined"
              density="compact"
              hide-details
            ></VSelect>
          </VCol>
          <VCol cols="12" md="3">
            <VSelect
              v-model="filterZone"
              :items="availableZones"
              label="Local / Zona"
              variant="outlined"
              density="compact"
              hide-details
            ></VSelect>
          </VCol>
          <VCol cols="12" md="2" class="text-right">
            <VChip color="primary" variant="tonal" class="font-weight-bold">
              Resultados: {{ filteredData.length }}
            </VChip>
          </VCol>
        </VRow>

        <VCardText v-if="loading && macData.length === 0" class="d-flex justify-center align-center py-10">
          <VProgressCircular indeterminate color="primary"></VProgressCircular>
        </VCardText>
        
        <VTable v-else hover class="bg-transparent mt-4">
          <thead>
            <tr>
              <th style="width: 120px;" class="font-weight-bold text-uppercase text-primary">AÇÕES</th>
              <th class="font-weight-bold text-uppercase text-primary">STATUS</th>
              <th class="font-weight-bold text-uppercase text-primary">IDENTIFICAÇÃO (MAC)</th>
              <th class="font-weight-bold text-uppercase text-primary">LOCAL ATUAL</th>
              <th class="font-weight-bold text-uppercase text-primary">CHEGADA</th>
              <th class="font-weight-bold text-uppercase text-primary">PERMANÊNCIA</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="filteredData.length === 0">
              <td colspan="6" class="text-center text-medium-emphasis py-8 text-body-1">Nenhum dispositivo atende aos filtros atuais.</td>
            </tr>
            <tr 
              v-for="item in paginatedData" 
              :key="item.MAC" 
              :class="item.status === 'saida' ? 'card-saida' : ''"
            >
              <!-- AÇÕES -->
              <td>
                <div class="d-flex">
                  <VBtn icon variant="text" size="small" :color="pinnedMac === item.MAC ? 'warning' : 'default'" @click="togglePin(item.MAC)">
                    <VIcon>{{ pinnedMac === item.MAC ? 'tabler-pin-filled' : 'tabler-pin' }}</VIcon>
                  </VBtn>
                  <VBtn icon variant="text" size="small" color="primary" @click="openEditDialog(item)">
                    <VIcon>tabler-edit</VIcon>
                  </VBtn>
                  <VBtn v-if="item.status !== 'saida'" icon variant="text" size="small" color="error" @click="registrarSaida(item)" title="Registrar Saída">
                    <VIcon>tabler-door-exit</VIcon>
                  </VBtn>
                </div>
              </td>
              
              <!-- STATUS -->
              <td>
                <div class="d-flex align-center">
                  <span :class="['pulse-dot', getStatusObj(item).pulseClass, 'mr-2']" style="flex-shrink: 0;"></span>
                  <span class="font-weight-bold text-caption" :class="`text-${getStatusObj(item).color}`">{{ getStatusObj(item).label }}</span>
                </div>
              </td>
              
              <!-- IDENTIFICAÇÃO -->
              <td>
                <template v-if="item.nome">
                  <div class="font-weight-bold text-body-1 text-info d-flex align-center">
                    <VIcon size="18" class="mr-1">tabler-id-badge</VIcon>
                    {{ item.nome }}
                  </div>
                </template>
                <template v-else>
                  <div class="font-weight-bold text-body-1">{{ item.fabricante || 'Desconhecido' }}</div>
                </template>
                <div class="text-caption text-medium-emphasis font-mono mt-1" style="letter-spacing: 1px;">{{ item.MAC }}</div>
              </td>
              
              <!-- LOCAL -->
              <td>
                <div class="d-flex align-center">
                  <VIcon size="18" color="primary" class="mr-1">tabler-map-pin</VIcon>
                  <span class="font-weight-medium">{{ item.dispositivo }}</span>
                </div>
              </td>
              
              <!-- CHEGADA -->
              <td>
                <div class="d-flex flex-column text-body-2">
                  <span>{{ formatDate(item.primeiraCaptura) }}</span>
                  <span class="text-caption text-medium-emphasis mt-1">Última: {{ formatDate(item.ultimaCaptura) }}</span>
                </div>
              </td>
              
              <!-- PERMANÊNCIA -->
              <td>
                <div class="d-flex flex-column">
                  <div class="font-weight-bold d-flex align-center">
                    <VIcon size="16" class="mr-1">tabler-clock</VIcon>
                    {{ getDuration(item.primeiraCaptura, item.ultimaCaptura) }}
                  </div>
                  <div v-if="getOfflineDuration(item.ultimaCaptura) && item.status !== 'saida'" class="text-caption text-error font-weight-bold mt-1 d-flex align-center">
                    <VIcon size="14" class="mr-1">tabler-wifi-off</VIcon>
                    Off há: {{ getOfflineDuration(item.ultimaCaptura) }}
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </VTable>
        
        <VCardText v-if="totalPages > 1" class="d-flex justify-center pt-6 pb-2">
          <VPagination v-model="currentPage" :length="totalPages" :total-visible="7"></VPagination>
        </VCardText>
        
      </VCard>
    </VCol>
  </VRow>

  <VDialog v-model="showClearDialog" max-width="500">
    <VCard class="rounded-xl">
      <VCardTitle class="text-h5 pt-6 px-6 font-weight-bold">Atenção!</VCardTitle>
      <VCardText class="px-6 text-body-1">
        Tem certeza que deseja limpar todos os registros de MAC Address do banco de dados? Esta ação excluirá todos os dados permanentemente.
      </VCardText>
      <VCardActions class="pb-6 px-6">
        <VSpacer></VSpacer>
        <VBtn color="secondary" variant="text" @click="showClearDialog = false" :disabled="clearing">Cancelar</VBtn>
        <VBtn color="error" variant="elevated" @click="clearDatabase" :loading="clearing">Sim, Limpar Banco</VBtn>
      </VCardActions>
    </VCard>
  </VDialog>

  <!-- Dialog de Edição de Nome -->
  <VDialog v-model="showEditDialog" max-width="500">
    <VCard class="rounded-xl">
      <VCardTitle class="text-h5 pt-6 px-6 font-weight-bold">Atribuir Nome</VCardTitle>
      <VCardText class="px-6">
        <p class="mb-4 text-body-1">Identifique o dispositivo com MAC: <strong class="font-mono">{{ editingItem?.MAC }}</strong></p>
        <VTextField
          v-model="editName"
          label="Nome do Dispositivo"
          variant="outlined"
          density="comfortable"
          hide-details
          autofocus
          @keyup.enter="saveName"
        ></VTextField>
      </VCardText>
      <VCardActions class="pb-6 px-6">
        <VSpacer></VSpacer>
        <VBtn color="secondary" variant="text" @click="showEditDialog = false" :disabled="saving">Cancelar</VBtn>
        <VBtn color="primary" variant="elevated" @click="saveName" :loading="saving">Salvar Alterações</VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
</template>

<style scoped>
.glass-card {
  background: rgba(var(--v-theme-surface), 0.6) !important;
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(var(--v-theme-on-surface), 0.08);
}

.card-saida {
  opacity: 0.55;
  filter: grayscale(0.8);
}
.card-saida:hover {
  opacity: 0.8;
}

.pulse-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  display: inline-block;
  position: relative;
}

.pulse-green {
  background-color: #4CAF50;
  box-shadow: 0 0 0 rgba(76, 175, 80, 0.4);
  animation: pulse-green 2s infinite;
}

.pulse-warning {
  background-color: #FF9800;
  box-shadow: 0 0 0 rgba(255, 152, 0, 0.4);
  animation: pulse-warning 2s infinite;
}

.pulse-red {
  background-color: #F44336;
}

@keyframes pulse-green {
  0% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.4); }
  70% { box-shadow: 0 0 0 10px rgba(76, 175, 80, 0); }
  100% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0); }
}

@keyframes pulse-warning {
  0% { box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.4); }
  70% { box-shadow: 0 0 0 10px rgba(255, 152, 0, 0); }
  100% { box-shadow: 0 0 0 0 rgba(255, 152, 0, 0); }
}

.font-mono {
  font-family: 'Fira Code', 'Courier New', Courier, monospace;
}
</style>
