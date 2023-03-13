export function usePopUp() {
  function PopUp(pop) {
    if (!(pop instanceof Element)) {
      return;
    }

    const btnClose = pop.querySelector(".btn--close");
    const btnOpen = document.querySelector(`[href="#${pop.id}"]`);

    function open(e) {
      e.preventDefault();
      pop.classList.add("active");
    }

    function close() {
      pop.classList.remove("active");
    }

    window.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        close();
      }
    });

    btnClose.addEventListener("click", close);
    btnOpen.addEventListener("click", (e) => open(e));
  }

  const popUps = document.querySelectorAll(".pop-up--wrap");
  popUps.forEach((pop) => {
    PopUp(pop);
  });
}
