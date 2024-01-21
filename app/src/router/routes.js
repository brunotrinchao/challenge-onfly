const routes = [
  {
    path: "/",
    component: () => import("layouts/LoginLayout.vue"),
    children: [
      {
        path: "",
        name: "login",
        component: () => import("pages/login/index.vue"),
      },
      {
        path: "/signup",
        name: "signup",
        component: () => import("pages/signup/index.vue"),
      },
    ],
  },
  {
    path: "/dashboard",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "dashboard",
        component: () => import("pages/dashboard/index.vue"),
      },
      {
        path: "/expenses",
        name: "expenses",
        component: () => import("pages/expenses/index.vue"),
      },
      {
        path: "/users",
        name: "users",
        component: () => import("pages/users/index.vue"),
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "*",
    component: () => import("pages/Error404.vue"),
  },
];

export default routes;
