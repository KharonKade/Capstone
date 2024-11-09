const newsCarousel1 = document.getElementById('newsCarousel');
const newsCarousel2 = document.getElementById('newsCarousel2');
const scrollAmount = 250;  // Amount to scroll per click (width of one item + margin)

// Function to handle scrolling to the right (forward) in a circular manner for newsCarousel1
function scrollRight1() {
    const maxScroll = newsCarousel1.scrollWidth - newsCarousel1.clientWidth;
    const currentScroll = newsCarousel1.scrollLeft;

    // If we've reached the end, reset to the start (circular loop)
    if (currentScroll + scrollAmount >= maxScroll) {
        newsCarousel1.scrollTo({
            left: 0,  // Scroll back to the start
            behavior: 'smooth'
        });
    } else {
        newsCarousel1.scrollBy({
            left: scrollAmount,  // Scroll to the right by the defined scrollAmount
            behavior: 'smooth'
        });
    }
}

// Function to handle scrolling to the right (forward) in a circular manner for newsCarousel2
function scrollRight2() {
    const maxScroll = newsCarousel2.scrollWidth - newsCarousel2.clientWidth;
    const currentScroll = newsCarousel2.scrollLeft;

    // If we've reached the end, reset to the start (circular loop)
    if (currentScroll + scrollAmount >= maxScroll) {
        newsCarousel2.scrollTo({
            left: 0,  // Scroll back to the start
            behavior: 'smooth'
        });
    } else {
        newsCarousel2.scrollBy({
            left: scrollAmount,  // Scroll to the right by the defined scrollAmount
            behavior: 'smooth'
        });
    }
}

// Optional: Monitor the scroll position to debug for newsCarousel1
newsCarousel1.addEventListener('scroll', function() {
    console.log('Current Scroll Position of Carousel 1: ', newsCarousel1.scrollLeft);
});

// Optional: Monitor the scroll position to debug for newsCarousel2
newsCarousel2.addEventListener('scroll', function() {
    console.log('Current Scroll Position of Carousel 2: ', newsCarousel2.scrollLeft);
});
