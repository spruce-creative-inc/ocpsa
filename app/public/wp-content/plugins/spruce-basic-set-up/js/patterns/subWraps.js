import { body, masthead } from "../elements";
import { mq } from "../helpers";
import { getMastheadHeight, getMastheadPreBarHeight } from "../setup/elHeights";

export function useSubWraps() {
  function SubWraps(subWrap) {
    const btn = subWrap.querySelector(".masthead__sub-menu__toggle");
    const backBtn = subWrap.querySelector(".masthead__sub-menu__back-btn");
    const subMenu = subWrap.querySelector(".masthead__sub-menu");
    const dropWrapLink = subWrap.querySelector(".masthead__drop-wrap a");

    if (!subMenu || !btn) {
      return;
    }

    // let subMenuLevel = 1;
    const subMenuLevel = subMenu.classList[1][subMenu.classList[1].length - 1];

    const subLinks = subMenu.querySelectorAll("a");

    let subHeight = 0;

    subLinks.forEach((link) => {
      const parentMenu = link.closest(".masthead__sub-menu");
      const parentMenuLevel =
        parentMenu.classList[1][subMenu.classList[1].length - 1];
      if (parentMenuLevel === subMenuLevel) {
        subHeight += link.offsetHeight;
      }
    });

    function setSubMenuHeight() {
      subMenu.setAttribute(
        "style",
        `--subHeight: calc(1rem + ${
          subHeight + getMastheadHeight(false) - getMastheadPreBarHeight(false)
        }px);`
      );
    }

    const mastheadResizeObserver = new ResizeObserver((entries) => {
      entries.forEach(() => {
        setSubMenuHeight();
      });
    });
    mastheadResizeObserver.observe(masthead);

    function openSubNav() {
      btn.setAttribute("aria-expanded", "true");
      subMenu.setAttribute("aria-expanded", "true");
      subWrap.classList.add("open");
    }

    function closeSubNav() {
      btn.setAttribute("aria-expanded", "false");
      subMenu.setAttribute("aria-expanded", "false");
      subWrap.classList.remove("open");
    }

    // function closeAllSubNavs() {
    //   subWraps.forEach((sw) => {
    //     const subWrapBtn = sw.querySelector(".masthead__sub-menu__toggle");
    //     const subSubMenu = sw.querySelector(".masthead__sub-menu");
    //     subWrapBtn && subWrapBtn.setAttribute("aria-expanded", "false");
    //     subSubMenu.setAttribute("aria-expanded", "false");
    //     sw.classList.remove("open");
    //   });
    // }

    function toggleSubNav() {
      if (btn.getAttribute("aria-expanded") === "false") {
        // closeAllSubNavs();
        openSubNav();
      } else {
        closeSubNav();
      }
    }

    btn &&
      btn.addEventListener("click", () => {
        toggleSubNav();
      });

    backBtn &&
      backBtn.addEventListener("click", () => {
        toggleSubNav();
        btn.focus();
      });

    function revealSubLevelTwo() {
      if (subMenuLevel === "2" && mq("desktop")) {
        subWrap
          .closest(".masthead__sub-menu")
          .classList.add("show-sub-menu-level-2");
      }
    }

    async function hideSubLevelTwo() {
      if (subMenuLevel === "2") {
        subWrap
          .closest(".masthead__sub-menu")
          .classList.remove("show-sub-menu-level-2");
      }
    }

    dropWrapLink.addEventListener("mouseover", revealSubLevelTwo);
    dropWrapLink.addEventListener("focus", revealSubLevelTwo);

    dropWrapLink.addEventListener("mouseleave", hideSubLevelTwo);
    dropWrapLink.addEventListener("blur", hideSubLevelTwo);

    subMenu.addEventListener("focusin", revealSubLevelTwo);
    subMenu.addEventListener("mouseover", revealSubLevelTwo);
    subMenu.addEventListener("focusout", hideSubLevelTwo);
    subMenu.addEventListener("mouseleave", hideSubLevelTwo);
    // subLinks.forEach((link) => {
    //   link.addEventListener("focus", openSubNav);
    //   link.addEventListener("blur", closeSubNav);
    // });

    const btnResizeObserver = new ResizeObserver((entries) => {
      entries.forEach(() => {
        if (mq("desktop")) {
          btn.setAttribute("tabindex", -1);
        } else {
          btn.setAttribute("tabindex", 0);
        }
      });
    });
    btnResizeObserver.observe(body);
  }

  const subWraps = document.querySelectorAll(".menu-item-has-children");

  subWraps.forEach((subWrap) => SubWraps(subWrap));
}
