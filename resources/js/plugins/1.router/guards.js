import { canNavigate } from '@layouts/plugins/casl'

export const setupGuards = router => {
  router.beforeEach((to, from) => {
    const allowedRoutes = ['logout', 'login']

    if (localStorage.getItem('isFormDirty') === 'true' && !allowedRoutes.includes(to.name)) {
      return false;
    }

    if (to.meta.public) {
      localStorage.setItem('isFormDirty', 'false');
      return
    }

    const isLoggedIn = !!(useCookie('userData').value && useCookie('accessToken').value)

    if (to.meta.unauthenticatedOnly) {
      return isLoggedIn ? '/' : undefined
    }

    // necessario pois as vzs buga ao iniciar onde nao posso navegar e estou logado dandod assim tela de nao autorizado
    // console.log(
    //   '[GUARD]',
    //   'Rota:', to.name,
    //   '| Meta.public:', to.meta?.public,
    //   '| Pode navegar (CASL):', canNavigate(to),
    //   '| Logado:', isLoggedIn
    // )

    // RODAR ISSO NO CONSOLE
    // document.cookie = "userData=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    // document.cookie = "accessToken=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

    // localStorage.clear()

    if (!isLoggedIn) {
      return {
        name: 'login',
        query: {
          ...to.query,
          to: to.fullPath !== '/' ? to.path : undefined,
        },
      }
    }

    if (!canNavigate(to) && to.matched.length) {
      return isLoggedIn
        ? { name: 'not-authorized' }
        : {
          name: 'login',
          query: {
            ...to.query,
            to: to.fullPath !== '/' ? to.path : undefined,
          },
        }
    }
  })
  if (localStorage.getItem('isFormDirty') === 'true') {
    // Por exemplo, depois de um recarregamento, resetar para 'false'
    localStorage.setItem('isFormDirty', 'false');
  }
}
