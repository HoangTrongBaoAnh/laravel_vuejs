import Vue from "vue";
import App from "./App.vue";
import vuetify from "./plugins/vuetify";
import VueRouter from "vue-router";
import Vuex from "vuex";
import "../node_modules/bootstrap/dist/css/bootstrap.css";

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    products: [],
    cart: [],
    order: {},
    drawerState: false,
  },
  mutations: {
    sidebar(state, data) {
      state.drawerState = data;
    },
    updateProducts(state, products) {
      state.products = products;
    },
    addToCart(state, product) {
      let productInCartIndex = state.cart.findIndex(
        (item) => item.id == product.id
      );
      if (productInCartIndex != -1) {
        state.cart[productInCartIndex].quantity++;
        return;
      }
      product.quantity = 1;
      state.cart.push(product);
    },
    removeFromCart(state, index) {
      state.cart.splice(index, 1);
    },
    updateOrder(state, order) {
      state.order = order;
    },
    updateCart(state, cart) {
      state.cart = cart;
    },
  },
  actions: {
    clearCart({ commit }) {
      commit("updateCart", []);
    },
  },
  getters: {
    drawerState: (state) => state.drawerState,
  },
});

Vue.use(VueRouter);
Vue.config.productionTip = false;

import login from "./components/login.vue";
import profile from "./components/profile.vue";
import shop from "./components/shop.vue";
import product_detail from "./components/shoe_detail.vue";
import checkout from "./components/checkout.vue";
import admin from "./components/admin/admin.vue";
import category from "./components/admin/datatable_category.vue";
import product from "./components/admin/product.vue";
import product_image from "./components/product_image.vue";
import Nav from "./components/nav_header";

const routes = [
  {
    path: "/login",
    component: login,
    name: "Login",
  },
  {
    path: "/Nav",
    component: Nav,
    name: "Nav",
  },
  {
    path: "/shop",
    component: shop,
    name: "shop",
  },
  {
    path: "/product_detail/:id",
    component: product_detail,
    name: "product_detail",
  },
  {
    path: "/checkout",
    component: checkout,
    name: "Checkout",
  },
  {
    path: "/admin",
    component: admin,
    name: "admin",
    children: [
      {
        // UserProfile will be rendered inside User's <router-view>
        // when /user/:id/profile is matched
        path: "category",
        component: category,
      },
      {
        // UserProfile will be rendered inside User's <router-view>
        // when /user/:id/profile is matched
        path: "product",
        component: product,
      },
      {
        // UserProfile will be rendered inside User's <router-view>
        // when /user/:id/profile is matched
        path: "profile",
        component: profile,
        name: "profile",
      },
      {
        // UserProfile will be rendered inside User's <router-view>
        // when /user/:id/profile is matched
        path: "product_image/:id",
        component: product_image,
        name: "Product_image",
      },
    ],
  },
];

const router = new VueRouter({
  mode: "history",
  routes, // short for `routes: routes`
});

new Vue({
  vuetify,
  router,
  store,
  render: (h) => h(App),
}).$mount("#app");
