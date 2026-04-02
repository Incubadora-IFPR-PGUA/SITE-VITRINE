<script setup>
import { PerfectScrollbar } from "vue3-perfect-scrollbar";

const router = useRouter();
const ability = useAbility();
const userData = useCookie("userData");

const logout = async () => {
  useCookie("accessToken").value = null;
  useCookie("userAbilityRules").value = null;
  userData.value = null;
  localStorage.clear();
  ability.update([]);
  await router.push("/login");
};

const goToProfile = () => {
  router.push("/profile");
};
</script>

<template>
  <h4 class="mx-2">
    {{ userData.nome || "Usuário" }}
  </h4>

  <VAvatar
    size="38"
    class="cursor-pointer"
    :color="!(userData && userData.avatar) ? 'primary' : undefined"
    :variant="!(userData && userData.avatar) ? 'tonal' : undefined">
    <template v-if="userData && userData.avatar">
      <img :src="userData.avatar" alt="User Avatar" class="avatar-image" />
    </template>
    <template v-else>
      <VIcon icon="tabler-user" />
    </template>

    <VMenu activator="parent" width="240" location="bottom end" offset="12px">
      <VList>
        <VListItem>
          <div class="d-flex gap-2 align-center">
            <div class="email-container">
              <h6 class="text-h6 font-weight-medium">
                {{ userData?.email || "Email não disponível" }}
              </h6>
            </div>
          </div>
        </VListItem>

        <PerfectScrollbar :options="{ wheelPropagation: false }">
          <div class="px-4 py-2">
            <VBtn
              block
              size="small"
              color="primary"
              append-icon="tabler-edit"
              @click="goToProfile">
              Editar Perfil
            </VBtn>
          </div>
          <div class="px-4 py-2">
            <VBtn
              block
              size="small"
              color="error"
              append-icon="tabler-logout"
              @click="logout">
              Sair
            </VBtn>
          </div>
        </PerfectScrollbar>
      </VList>
    </VMenu>
  </VAvatar>
</template>

<style scoped>
.avatar-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}

.email-container h6 {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 150px;
}
</style>
