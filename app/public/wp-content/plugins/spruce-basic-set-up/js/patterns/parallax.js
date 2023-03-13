export function useParallax() {
  function Parallax(par) {
    if (!(par instanceof Element)) {
      return;
    }

    function setStyle() {
      const imgUrl = par.dataset.img;
      let offset = 33.3333;
      const winHeight = window.innerHeight;
      const parHeight = par.offsetHeight;
      const winOffset = par.getBoundingClientRect().bottom;
      if (winOffset > 0 && winOffset <= winHeight + parHeight) {
        offset += (1 - winOffset / (winHeight + parHeight)) * 33.3333;
      }

      const style = `--bgImage: url(${imgUrl}); --offset: translateY(-${offset}%);`;
      par.setAttribute("style", style);
    }

    setStyle();

    window.addEventListener("scroll", setStyle);
  }
  const parBanners = document.querySelectorAll(".parallax");
  parBanners.forEach((ban) => {
    Parallax(ban);
  });
}
