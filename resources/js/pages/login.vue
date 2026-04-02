<script setup>
import { VForm } from "vuetify/components/VForm";
import { onMounted } from "vue";
import axios from "axios";

const isPasswordVisible = ref(false);
const isLoading = ref(false);
const route = useRoute();
const router = useRouter();
const ability = useAbility();
const refVForm = ref();
const error = ref(false);
const errorMessage = ref("");
const credentials = ref({ NOME: "", SENHA: "" });

definePage({
  meta: {
    layout: "blank",
    unauthenticatedOnly: true,
  },
});

onMounted(() => {
  localStorage.setItem("isFormDirty", "false");

  const hasReloaded = sessionStorage.getItem("login_reloaded");

  if (!hasReloaded) {
    sessionStorage.setItem("login_reloaded", "true");
    window.location.reload();
  } else {
    sessionStorage.removeItem("login_reloaded");
  }
});

const isFormValid = computed(() => {
  return (
    credentials.value.NOME.trim() !== "" &&
    credentials.value.SENHA.trim() !== ""
  );
});

const login = async () => {
  isLoading.value = true;
  
  // Simulação de login com qualquer dado
  const res = {
    token: "mock-token",
    userData: credentials.value.NOME || "Usuário",
    userEmail: "admin@admin.com",
    userProfile: "",
    userId: "1",
    userAbilityRules: [{ action: "manage", subject: "all" }]
  };

  const userData = {
    nome: res.userData,
    email: res.userEmail,
    avatar: res.userProfile,
    codigo: res.userId,
  };

  useCookie("accessToken").value = res.token;
  useCookie("userAbilityRules").value = res.userAbilityRules;
  useCookie("userData").value = userData;
  useCookie("userId").value = userData;
  ability.update(res.userAbilityRules);

  await nextTick(() => {
    router.replace(route.query.to ? String(route.query.to) : "/");
  });

  isLoading.value = false;
};

const onSubmit = () => {
  refVForm.value?.validate().then(({ valid: isValid }) => {
    if (isValid) login();
  });
};

const closeAndReset = () => {
  credentials.value.NOME = "";
  credentials.value.SENHA = "";
  error.value = false;
};
</script>

<template>
  <VRow no-gutters class="auth-wrapper bg-surface">
    <VCol md="8" class="d-none d-md-flex">
      <div class="position-relative auth-bg w-100 me-0">
        <div
          class="d-flex align-center justify-center w-100"
          style="padding-inline: 6.25rem; min-height: 90vh">
<!--
          <img
            src="@/assets/infotech500x500.png"
            alt="Logotipo LAP"
            class="auth-illustration mt-16 mb-2" />
          -->
        </div>
      </div>
    </VCol>

    <VCol
      cols="12"
      md="4"
      class="d-flex align-center justify-center"
      style="min-height: 100vh">
      <VCard flat :max-width="450" class="pa-6">
        <VCardText class="text-center">
          <h4 class="text-h4 mb-2">Bem-vindo ao Sistema da Vitrine Helice!</h4>
          <p class="mb-4">Faça o login para acessar a plataforma!</p>
        </VCardText>

        <VForm ref="refVForm" @submit.prevent="onSubmit">
          <VRow>
            <VCol cols="12">
              <AppTextField
                v-model="credentials.NOME"
                label="Usuário"
                type="text" />
            </VCol>
            <VCol cols="12">
              <AppTextField
                v-model="credentials.SENHA"
                label="Senha"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="
                  isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'
                "
                @click:append-inner="isPasswordVisible = !isPasswordVisible" />
            </VCol>
            <VCol cols="12">
              <VBtn
                block
                type="submit"
                class="mt-4"
                :disabled="!isFormValid || isLoading">
                <template v-if="isLoading">
                  <VProgressCircular indeterminate color="white" size="24" />
                </template>
                <template v-else>Login</template>
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCard>
    </VCol>
  </VRow>

  <VDialog v-model="error" max-width="400px" @click:outside="closeAndReset">
    <template #default="{ close }">
      <VCard>
        <VCardText class="text-center">
          <h4 class="mb-2">Opa, Houve um Problema!</h4>
          <p>{{ errorMessage }}</p>
          <VBtn color="primary" @click="closeAndReset"> Fechar </VBtn>
        </VCardText>
      </VCard>
    </template>
  </VDialog>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth";

.auth-bg {
  position: relative;
  width: 100%;
  min-height: 100vh;
  background-image: url("https://folhadolitoral.com.br/wp-content/uploads/2022/07/o795ksn.jpg");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;

  &::before {
    content: "";
    position: absolute;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
  }

  > * {
    position: relative;
    z-index: 1;
  }
}
</style>
