import { body } from "../elements";

export function useTabbing() {
  function activateTabbing(e) {
    const keys = ["Tab", "ArrowUp", "ArrowRight", "ArrowDown", "ArrowLeft", 32];
    if (keys.includes(e.key) || keys.includes(e.keyCode)) {
      body.classList.add("user-is-tabbing");
    }
  }

  function deactivateTabbing() {
    body.classList.remove("user-is-tabbing");
  }

  window.addEventListener("keydown", activateTabbing);
  window.addEventListener("click", deactivateTabbing);
}
