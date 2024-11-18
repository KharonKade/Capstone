const generalContentElement = document.querySelector(".general-content");
const carouselContainer = generalContentElement.querySelector(
  ".general-content-carouselContainer"
);
const generalSlider = generalContentElement.querySelector(".generalSlider");
const sliderItems = generalSlider.querySelectorAll(".generalSlider-card");

// Variables that will change states throughout the code
let isMouseOver = false;
let currentMousePosition = 0;
let lastMousePosition = 0;
let lastMouseMovement = 0;
let moveTo = 0;

const startSlider = () => {
  const sliderProperties = onResize();
  const totalSize = sliderItems.length; // Length of the array
  const sizeInDegrees = 360 / totalSize; // Degrees per item
  const gap = 30; // Space between items
  const tz = horizontalDistance(sliderProperties.w, totalSize, gap);

  const height = calculateHeight(tz);

  generalContentElement.style.width = tz * 2 + gap * totalSize + "px";
  generalContentElement.style.height = height + "px";

  sliderItems.forEach((item, i) => {
    const degreesPerItem = sizeInDegrees * i + "deg";
    item.style.setProperty("--rotatey", degreesPerItem);
    item.style.setProperty("--tz", tz + "px");
  });
};

// Animation smoothing
const smoothAnimation = (a, b, n) => {
  return n * (a - b) + b;
};

const horizontalDistance = (elementWidth, totalSize, gap) => {
  return elementWidth / 2 / Math.tan(Math.PI / totalSize) + gap; // Horizontal distance between items
};

// Calculates the container height using field of view and perspective distance
const calculateHeight = (z) => {
  const t = Math.atan((90 * Math.PI) / 180 / 2);
  const height = t * 2 * z;

  return height;
};

// Calculates the field of view of the generalSlider
const calculateFieldOfView = (sliderProperties) => {
  const perspective = window
    .getComputedStyle(carouselContainer)
    .perspective.split("px")[0];

  const totalSize =
    Math.sqrt(sliderProperties.w * sliderProperties.w) +
    Math.sqrt(sliderProperties.h * sliderProperties.h);
  const fov = 2 * Math.atan(totalSize / (2 * perspective)) * (180 / Math.PI);
  return fov;
};

// Gets the X position and evaluates whether the position is right or left
const getMouseXPosition = (x) => {
  currentMousePosition = x;

  moveTo =
    currentMousePosition < lastMousePosition
      ? moveTo - 2
      : moveTo + 2;

  lastMousePosition = currentMousePosition;
};

const updateGeneral = () => {
  lastMouseMovement = smoothAnimation(
    moveTo,
    lastMouseMovement,
    0.3
  );
  generalSlider.style.setProperty("--rotatey", lastMouseMovement + "deg");

  requestAnimationFrame(updateGeneral);
};

const onResize = () => {
  // Gets the size properties of the generalSlider
  const boundingCarousel = carouselContainer.getBoundingClientRect();

  const sliderProperties = {
    w: boundingCarousel.width,
    h: boundingCarousel.height,
  };

  return sliderProperties;
};

const initializeGeneralLogic = () => {
  // Event to detect if the mouse is over the generalSlider
  generalSlider.addEventListener("mousedown", () => {
    isMouseOver = true;
    generalSlider.style.cursor = "grabbing";
  });
  generalSlider.addEventListener("mouseup", () => {
    isMouseOver = false;
    generalSlider.style.cursor = "grab";
  });
  generalContentElement.addEventListener(
    "mouseleave",
    () => (isMouseOver = false)
  );

  generalSlider.addEventListener(
    "mousemove",
    (e) => isMouseOver && getMouseXPosition(e.clientX)
  );

  // Event to detect if the mouse is clicking on the generalSlider
  generalSlider.addEventListener("touchstart", () => {
    isMouseOver = true;
    generalSlider.style.cursor = "grabbing";
  });
  generalSlider.addEventListener("touchend", () => {
    isMouseOver = false;
    generalSlider.style.cursor = "grab";
  });
  generalContentElement.addEventListener(
    "touchleave",
    () => (isMouseOver = false)
  );

  generalSlider.addEventListener(
    "touchmove",
    (e) => isMouseOver && getMouseXPosition(e.changedTouches[0].clientX)
  );
};

initializeGeneralLogic();
startSlider();
updateGeneral();
