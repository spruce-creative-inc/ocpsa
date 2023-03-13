import { prefersReducedMotion } from "../helpers";

export function useLogoBar() {
  function LogoBar(lb) {
    if (!(lb instanceof Element)) {
      return;
    }

    let offLeft = lb.querySelector(".off-left") || null;
    let slot1 = lb.querySelector(".slot-1") || null;
    let slot2 = lb.querySelector(".slot-2") || null;
    let slot3 = lb.querySelector(".slot-3") || null;
    let slot4 = lb.querySelector(".slot-4") || null;
    let offRight = lb.querySelector(".off-right") || null;

    const btnPrev = lb.querySelector(".logo-bar__button--prev");
    const btnNext = lb.querySelector(".logo-bar__button--next");

    const logoWrap = lb.querySelector(".logo-bar__wrap");

    function applyClasses() {
      offLeft && offLeft.classList.add("off-left");
      if (slot1) {
        slot1.classList.add("slot-1");
        slot1.setAttribute("tabindex", 0);
      }
      if (slot2) {
        slot2.classList.add("slot-2");
        slot2.setAttribute("tabindex", 0);
      }
      if (slot3) {
        slot3.classList.add("slot-3");
        slot3.setAttribute("tabindex", 0);
      }
      if (slot4) {
        slot4.classList.add("slot-4");
        slot4.setAttribute("tabindex", 0);
      }
      offRight && offRight.classList.add("off-right");
    }

    let autoPlayInterval = 5000;

    function resetAutoPlayInterval() {
      autoPlayInterval = 5000;
    }

    function move(dir) {
      const classesToRemove = [
        "off-left",
        "slot-1",
        "slot-2",
        "slot-3",
        "slot-4",
        "off-right",
      ];

      [offLeft, slot1, slot2, slot3, slot4, offRight].forEach((logo) => {
        if (logo) {
          logo.classList.remove(...classesToRemove);
          logo.setAttribute("tabindex", -1);
        }
      });

      if (dir === "back") {
        [offLeft, slot1, slot2, slot3, slot4, offRight] = [
          offLeft.previousElementSibling || logoWrap.lastElementChild,
          offLeft,
          slot1,
          slot2,
          slot3,
          slot4,
        ];
      } else {
        [offLeft, slot1, slot2, slot3, slot4, offRight] = [
          slot1,
          slot2,
          slot3,
          slot4,
          offRight,
          offRight?.nextElementSibling || logoWrap.firstElementChild,
        ];
      }

      applyClasses();
      resetAutoPlayInterval();
    }

    function countDown() {
      autoPlayInterval -= 1000;
      if (autoPlayInterval === 0) {
        move();
      }
    }

    !prefersReducedMotion() && setInterval(countDown, 1000);

    btnPrev.addEventListener("click", () => move("back"));
    btnNext.addEventListener("click", () => move());

    logoWrap.addEventListener("focusin", () => {
      autoPlayInterval = 10000000000;
    });
    logoWrap.addEventListener("focusout", () => {
      resetAutoPlayInterval();
    });
    logoWrap.addEventListener("mouseover", () => {
      autoPlayInterval = 10000000000;
    });
    logoWrap.addEventListener("mouseout", () => {
      resetAutoPlayInterval();
    });
  }

  const logoBar = document.querySelector(".logo-bar--slider");

  logoBar && LogoBar(logoBar);
}
