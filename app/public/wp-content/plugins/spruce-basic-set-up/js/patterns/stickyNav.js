import { masthead, toTop } from "../elements";
import { getMastheadHeight } from "../setup/elHeights";

export function useStickyNav() {
  const mHeight = getMastheadHeight(false);

  function stickyNav() {
    if (window.scrollY > mHeight) {
      masthead.classList.add("sticky");
      toTop && toTop.classList.add("visible");
    } else {
      masthead.classList.remove("sticky");
      toTop && toTop.classList.remove("visible");
    }
  }

  stickyNav();
  window.addEventListener("scroll", stickyNav);
}
