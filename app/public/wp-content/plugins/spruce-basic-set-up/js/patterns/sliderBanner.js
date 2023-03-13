import { prefersReducedMotion, wait } from "../helpers";

export function useSliderBanner() {
  async function SliderBanner(sb, interval) {
    if (!(sb instanceof Element)) return;

    function getSlides() {
      return Array.from(sb.querySelectorAll(".banner-slider__item"));
    }

    function getControls() {
      return Array.from(sb.querySelectorAll(".banner-slider__control"));
    }

    let autoPlayInterval = interval;

    function resetAutoPlayInterval() {
      autoPlayInterval = interval;
    }

    async function changeSlide(index, autoChange) {
      const slides = getSlides();
      const controls = getControls();
      const selectedSlide = slides[index];

      for (const s of slides) {
        s.classList.add("transitioned-out");
      }
      selectedSlide.classList.add("on-deck");

      await wait(300);

      for (const s of slides) {
        s.setAttribute("aria-hidden", true);
        s.setAttribute("aria-current", false);

        const btns = s.querySelectorAll(".btn");
        btns.forEach((btn) => {
          btn.setAttribute("tabindex", -1);
        });
      }

      for (const c of controls) {
        c.setAttribute("aria-current", false);
        c.setAttribute("tabindex", -1);
      }

      const btns = selectedSlide.querySelectorAll(".btn");

      selectedSlide.setAttribute("aria-hidden", false);
      selectedSlide.setAttribute("aria-current", true);
      btns.forEach((btn) => {
        btn.setAttribute("tabindex", 0);
      });

      const selectedControl = controls[index];

      !autoChange && selectedControl.focus();
      selectedControl.setAttribute("aria-current", true);
      selectedControl.setAttribute("tabindex", 0);

      await wait(10);

      selectedSlide.classList.remove("transitioned-out", "on-deck");
      resetAutoPlayInterval();
    }

    const slideControls = sb.querySelector(".banner-slider__controls");

    slideControls.addEventListener("click", (e) => {
      e.preventDefault();
      // Get all controls
      const controls = getControls();
      // Find out which control was clicked
      const isDot = e.target.closest("a");

      if (isDot?.closest(".banner-slider__controls")) {
        // Get control index
        const index = controls.indexOf(isDot);
        // Change slide
        changeSlide(index);
      }
    });

    sb.addEventListener("keydown", (e) => {
      const slides = getSlides();

      const currentIndex = slides.indexOf(
        // find the selected tab based on aria-select
        slides.find((slide) => slide.matches('[aria-current="true"]'))
      );

      if (e.key === "ArrowLeft") {
        // Previous Slide
        const newIndex =
          currentIndex === 0 ? slides.length - 1 : currentIndex - 1;
        changeSlide(newIndex);
      } else if (e.key === "ArrowRight") {
        // Next Slide
        const newIndex =
          currentIndex === slides.length - 1 ? 0 : currentIndex + 1;
        changeSlide(newIndex);
      }
    });

    const startingSlide = sb.querySelector(
      '.banner-slider__item[aria-current="true"]'
    );

    await wait(10);

    startingSlide.classList.remove("transitioned-out");

    function autoMove() {
      const slides = getSlides();
      const currentIndex = slides.indexOf(
        // find the selected tab based on aria-select
        slides.find((slide) => slide.matches('[aria-current="true"]'))
      );
      // Next Slide
      const newIndex =
        currentIndex === slides.length - 1 ? 0 : currentIndex + 1;
      changeSlide(newIndex, true);
    }

    function countDown() {
      autoPlayInterval -= 1000;
      if (autoPlayInterval === 0) {
        autoMove();
      }
    }

    interval && !prefersReducedMotion() && setInterval(countDown, 1000);

    const slides = getSlides();

    slides.forEach((slide) => {
      slide.addEventListener("focusin", () => {
        autoPlayInterval = 10000000000;
      });
      slide.addEventListener("focusout", () => {
        resetAutoPlayInterval();
      });
      slide.addEventListener("mouseover", () => {
        autoPlayInterval = 10000000000;
      });
      slide.addEventListener("mouseout", () => {
        resetAutoPlayInterval();
      });
    });
  }

  const sliderBanner = document.querySelector(".banner-slider");
  if (sliderBanner) {
    const interval = parseInt(`${sliderBanner.dataset.interval}000`) || null;
    SliderBanner(sliderBanner, interval);
  }
}
