import ReactDOM from "react-dom/client";
import React from "react";

import App from "./App.jsx";
// import { initI18n } from "./utils/i18nUtils";

// Ensure that locales are loaded before rendering the app
// initI18n().then(() => {
//   ReactDOM.render(<App />, document.getElementById("app"));
// });

const root = ReactDOM.createRoot(document.getElementById("app"));
root.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);