export async function asyncForEach(array, callback) {
  for (let index = 0; index < array.length; index++) {
    await callback(array[index], index, array);
  }
}

export async function wait(ms = 0) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export function mq(size) {
  const sizes = {
    phones: 30,
    tablet: 48,
    lg_tablet: 53,
    desktop: 62,
    lg_desktop: 75,
    widescreen: 90,
    lg_widescreen: 100,
  };

  const { matches } = window.matchMedia(`(min-width: ${sizes[size]}em)`);
  return matches;
}

export function prefersReducedMotion() {
  const { matches } = window.matchMedia(`(prefers-reduced-motion)`);
  return matches;
}
