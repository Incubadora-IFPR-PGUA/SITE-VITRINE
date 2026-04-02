export const redirects = [
  {
    path: "/",
    name: "index",
    redirect: (to) => {
      const userData = useCookie("userData");
      if (userData) return { name: "home" };

      return { name: "login", query: to.query };
    },
  },
];
export const routes = [
  {
    path: "/",
    name: "home",
    component: () => import("@/pages/home.vue"),
  },
];
