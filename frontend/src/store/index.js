// import api from "@/axios";

// export default createStore({
//   state: {
//     user: null,
//     isAuthenticated: false,
//   },
//   getters: {
//     isAuthenticated: (state) => state.isAuthenticated,
//     user: (state) => state.user,
//   },
//   mutations: {
//     setAuth(state, { user, isAuthenticated }) {
//       state.user = user;
//       state.isAuthenticated = isAuthenticated;
//     },
//     clearAuth(state) {
//       state.user = null;
//       state.isAuthenticated = false;
//     },
//   },
//   actions: {
//     async checkAuth({ commit }) {
//       try {
//         const response = await api.get("/auth/check", {
//           Authorization: `Bearer ${localStorage.getItem("token")}`,
//         });
//         if (response.data.authenticated) {
//           commit("setAuth", {
//             user: response.data.user,
//             isAuthenticated: true,
//           });
//         } else {
//           commit("clearAuth");
//           throw new Error("Lỗi truy cập.");
//         }
//       } catch (error) {
//         commit("clearAuth");
//         localStorage.removeItem("token");
//         throw error;
//       }
//     },
//     async login({commit}, {email, password}){
//         try {
//             const response = await api.post('/login', {email, password});
//             const {token, user} = response.data;
//             localStorage.setItem('token', token);
//             commit('setAuth', {
//                 user: user,
//                 isAuthenticated: true
//             });
//             return response.data;
//         } catch (error) {
//             throw error.response?.data || {message: 'Đăng nhập thất bại'};
//         }
//     },
//     logout({commit}){
//         commit('clearAuth');
//         localStorage.removeItem('token');
//     },
//   },
// });
