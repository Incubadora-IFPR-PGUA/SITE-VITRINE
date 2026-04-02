import axios from "axios";

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) {
    let val = parts.pop().split(";").shift();
    if (val) {
      try {
        val = decodeURIComponent(val);
      } catch (_) {}
    }
    return val || null;
  }
  return null;
}

axios.interceptors.request.use((config) => {
  const token = getCookie("accessToken");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default function (app) {
  const router = app.config.globalProperties?.$router;
  if (!router) return;

  axios.interceptors.response.use(
    response => response,
    error => {
      const status = error.response?.status;
      const message = error.response?.data?.message ?? '';
      const isUnauthenticated = status === 401
        || (typeof message === 'string' && message.toLowerCase().includes('não autenticado'))
        || (typeof message === 'string' && message.toLowerCase().includes('unauthenticated'));

      if (isUnauthenticated) {
        const isLoginRequest = error.config?.url?.includes?.('/api/auth/login');
        if (!isLoginRequest) {
          router.push({ name: 'login' });
        }
      }
      return Promise.reject(error);
    },
  );
}
