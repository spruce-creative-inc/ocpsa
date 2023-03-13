import { useBodySetUp } from "./setup/body";
import { useStickyNav } from "./patterns/stickyNav";
import { useNavToggle } from "./patterns/navToggle";
import { useSubWraps } from "./patterns/subWraps";
import { useSliderBanner } from "./patterns/sliderBanner";
import { useParallax } from "./patterns/parallax";
import { usePopUp } from "./patterns/popup";
import { useSearchModal } from "./patterns/searchModal";
import { useLogoBar } from "./patterns/logoBar";

function init() {
  console.log("Spruce Plugin Loaded");

  // Body Setup
  useBodySetUp();

  // Sticky Nav
  useStickyNav();

  // Nav Toggles
  useNavToggle();

  // SubWraps
  useSubWraps();

  // Slider Banner
  useSliderBanner();

  // Parallax
  useParallax();

  // Pop Up
  usePopUp();

  // Search Modal
  useSearchModal();

  // Logo Bar
  useLogoBar();
}

document.addEventListener("DOMContentLoaded", init);
