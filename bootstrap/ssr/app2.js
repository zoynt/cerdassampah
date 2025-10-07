import { jsx } from "react/jsx-runtime";
import { createInertiaApp } from "@inertiajs/react";
import { createRoot } from "react-dom/client";
import "react";
import "@dnd-kit/core";
import "@fortawesome/react-fontawesome";
createInertiaApp({
  resolve: (name) => import(`./Pages/${name}`),
  // Sesuaikan dengan nama halaman yang ada
  setup({ el, App, props }) {
    const root = createRoot(el);
    root.render(/* @__PURE__ */ jsx(App, { ...props }));
  }
});
