import { wait } from "../helpers";

export function useSearchModal() {
  const searchModal = document.querySelector(".search-modal");
  const searchModalOpener = document.querySelector(".masthead__search");
  const searchModalCloser = searchModal.querySelector(".search-modal__close");

  async function open() {
    searchModal.classList.add("active");
    await wait(20);
    searchModal.classList.add("transitioned");
    searchModal.focus();
  }

  async function close() {
    searchModal.classList.remove("transitioned");
    await wait(300);
    searchModal.classList.remove("active");
  }

  searchModalOpener.addEventListener("click", open);
  searchModalCloser.addEventListener("click", close);
  window.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      close();
    }
  });
}
