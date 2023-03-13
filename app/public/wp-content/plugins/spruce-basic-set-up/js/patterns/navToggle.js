import { asyncForEach, wait } from "../helpers";

export function useNavToggle() {
  function NavToggle(navToggle, animateLinks) {
    if (!(navToggle instanceof Element)) {
      return;
    }

    const targetRef = navToggle.getAttribute("href");
    const targetNav = document.querySelector(targetRef);
    const togMiddle = navToggle.querySelector(".middle");
    const targetAnimateLinks = targetNav.querySelectorAll(
      ".primary-menu > li > .animate, .social-menu > li > .animate"
    );
    const links = targetNav.querySelectorAll("a");

    async function open() {
      navToggle.classList.add("transition");
      await wait(200);
      navToggle.classList.add("open");
      navToggle.classList.remove("transition");
      togMiddle.classList.add("hidden");
      targetNav.classList.add("open");
      if (animateLinks) {
        asyncForEach(targetAnimateLinks, async (link) => {
          await wait(100);
          link.classList.remove("out");
        });
      }
    }

    async function close() {
      navToggle.classList.add("transition");
      navToggle.classList.remove("open");
      await wait(200);
      navToggle.classList.remove("transition");
      togMiddle.classList.remove("hidden");
      targetNav.classList.remove("open");
      if (animateLinks) {
        targetAnimateLinks.forEach((link) => {
          link.classList.add("out");
        });
      }

      const subWraps = document.querySelectorAll(".menu-item-has-children");
      subWraps.forEach((sw) => {
        const subWrapBtn = sw.querySelector(".masthead__sub-menu__toggle");
        const subSubMenu = sw.querySelector(".masthead__sub-menu");
        subWrapBtn && subWrapBtn.setAttribute("aria-expanded", "false");
        subSubMenu.setAttribute("aria-expanded", "false");
        sw.classList.remove("open");
      });
    }

    navToggle.addEventListener("click", (e) => {
      e.preventDefault();
      navToggle.classList.contains("open") ? close() : open();
    });

    links.forEach((link) => {
      link.addEventListener("click", close);
    });
  }

  const navToggles = document.querySelectorAll(".nav-toggle");
  navToggles.forEach((navToggle) => {
    let animateLinks = false;
    if (navToggle.dataset.animateLinks === "true") {
      animateLinks = true;
    }
    NavToggle(navToggle, animateLinks);
  });
}
