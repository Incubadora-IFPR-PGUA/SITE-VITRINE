<script setup>
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import misc404 from '@images/pages/404.png'
import miscMaskDark from '@images/pages/misc-mask-dark.png'
import miscMaskLight from '@images/pages/misc-mask-light.png'

const authThemeMask = useGenerateImageVariant(miscMaskLight, miscMaskDark)

definePage({
  alias: '/pages/misc/not-found/:error(.*)',
  meta: {
    layout: 'blank',
    public: true,
  },
})
</script>

<template>
  <div class="misc-wrapper d-flex align-center justify-center min-h-screen px-4 py-10">
    <v-container class="text-center">
      <v-row justify="center">
        <v-col cols="12" md="8">
          <h1 class="text-h2 font-weight-bold mb-4">404 - Página não encontrada ⚠️</h1>
          <p class="text-body-1 mb-6">
            Ops! Parece que você se perdeu. A página que você procura não existe ou foi movida.
          </p>

          <VBtn to="/" color="primary" variant="flat" size="large" class="mb-8" prepend-icon="tabler-arrow-left">
            Voltar para o início
          </VBtn>

          <div class="misc-avatar w-100 text-center">
            <VImg :src="misc404" alt="error 404" :max-height="$vuetify.display.smAndDown ? 350 : 380"
              class="mx-auto floaty-image" />
          </div>
        </v-col>
      </v-row>
    </v-container>

    <img class="misc-footer-img d-none d-md-block position-absolute bottom-0 start-0 w-100" :src="authThemeMask"
      alt="misc-footer-img" height="320">
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/misc.scss";

.misc-wrapper {
  position: relative;
  background-color: #f9fafb;
}

.floaty-image {
  animation: float 3s ease-in-out infinite;
}

@keyframes float {

  0%,
  100% {
    transform: translateY(0px);
  }

  50% {
    transform: translateY(-10px);
  }
}
</style>
