const newsCarousel = document.getElementById('newsCarousel');
const scrollAmount = 250;  // Amount to scroll per click (width of one item + margin)

// Function to handle scrolling to the right (forward) in a circular manner
function scrollRight() {
    const maxScroll = newsCarousel.scrollWidth - newsCarousel.clientWidth;
    const currentScroll = newsCarousel.scrollLeft;

    // If we've reached the end, reset to the start (circular loop)
    if (currentScroll + scrollAmount >= maxScroll) {
        newsCarousel.scrollTo({
            left: 0,  // Scroll back to the start
            behavior: 'smooth'
        });
    } else {
        newsCarousel.scrollBy({
            left: scrollAmount,  // Scroll to the right by the defined scrollAmount
            behavior: 'smooth'
        });
    }
}

// Function to handle scrolling to the left (backward) in a circular manner
function scrollLeft() {
    const currentScroll = newsCarousel.scrollLeft;

    // If we're at the beginning, jump to the last item (circular loop)
    if (currentScroll <= 0) {
        const maxScroll = newsCarousel.scrollWidth - newsCarousel.clientWidth;
        newsCarousel.scrollTo({
            left: maxScroll,  // Scroll to the last item
            behavior: 'smooth'
        });
    } else {
        newsCarousel.scrollBy({
            left: -scrollAmount,  // Scroll to the left by the defined scrollAmount
            behavior: 'smooth'
        });
    }
}

// Optional: Monitor the scroll position to debug
newsCarousel.addEventListener('scroll', function() {
    console.log('Current Scroll Position: ', newsCarousel.scrollLeft);
});
