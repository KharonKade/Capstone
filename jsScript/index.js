// Auto scroll effect for the news carousel when hovered
const newsCarousel = document.querySelector('.news-carousel');

// Scroll the carousel when hovering near the end
newsCarousel.addEventListener('mouseenter', () => {
    newsCarousel.scrollBy({
        left: 300, // Adjust the scroll distance as needed
        behavior: 'smooth'
    });
});

// Optionally, you can reverse the scroll if needed:
newsCarousel.addEventListener('mouseleave', () => {
    newsCarousel.scrollBy({
        left: -300, // Reverse scroll when mouse leaves
        behavior: 'smooth'
    });
});
