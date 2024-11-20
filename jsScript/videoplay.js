const carousel = document.querySelector('.carousel');
const prevBtn = document.querySelector('.prev-btn');
const nextBtn = document.querySelector('.next-btn');
const modal = document.getElementById('videoModal');
const modalVideo = document.getElementById('modalVideo');
const closeModalBtn = document.getElementById('closeModalBtn');
const carouselItems = document.querySelectorAll('.carousel-item');

let currentIndex = 0;
const itemsPerView = 4; // Number of visible items at a time
const totalItems = carouselItems.length;

// Calculate the width of an item including the gap
const itemWidth = carouselItems[0].offsetWidth + 10;

// Update Carousel Position
const updateCarousel = () => {
  const translateX = -currentIndex * itemWidth;
  carousel.style.transform = `translateX(${translateX}px)`;
};

// Navigate to Previous
prevBtn.addEventListener('click', () => {
  if (currentIndex > 0) {
    currentIndex--;
  } else {
    currentIndex = Math.max(0, totalItems - itemsPerView); // Go to the last viewable set
  }
  updateCarousel();
});

// Navigate to Next
nextBtn.addEventListener('click', () => {
  if (currentIndex < totalItems - itemsPerView) {
    currentIndex++;
  } else {
    currentIndex = 0; // Wrap around to the first set
  }
  updateCarousel();
});

// Open Modal on Video Click
carouselItems.forEach((item) => {
  item.addEventListener('click', () => {
    modal.classList.add('active');
    const videoSrc = item.querySelector('video').getAttribute('src');
    modalVideo.setAttribute('src', videoSrc);
    modalVideo.muted = false; // Enable sound for the modal video
  });
});

// Close Modal
closeModalBtn.addEventListener('click', () => {
  modal.classList.remove('active');
  modalVideo.pause();
  modalVideo.removeAttribute('src'); // Remove the video source to stop playback
});
