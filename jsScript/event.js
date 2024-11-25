const eventCarousel1 = document.getElementById('eventCarousel');
const eventCarousel2 = document.getElementById('eventCarousel2');
const scrollAmount = 250;  // Amount to scroll per click (width of one item + margin)

// Function to handle scrolling to the right (forward) in a circular manner for newsCarousel1
function scrollRight1() {
    const maxScroll = eventCarousel1.scrollWidth - eventCarousel1.clientWidth;
    const currentScroll = eventCarousel1.scrollLeft;

    // If we've reached the end, reset to the start (circular loop)
    if (currentScroll + scrollAmount >= maxScroll) {
        eventCarousel1.scrollTo({
            left: 0,  // Scroll back to the start
            behavior: 'smooth'
        });
    } else {
        eventCarousel1.scrollBy({
            left: scrollAmount,  // Scroll to the right by the defined scrollAmount
            behavior: 'smooth'
        });
    }
}

// Function to handle scrolling to the right (forward) in a circular manner for newsCarousel2
function scrollRight2() {
    const maxScroll = eventCarousel2.scrollWidth - eventCarousel2.clientWidth;
    const currentScroll = eventCarousel2.scrollLeft;

    // If we've reached the end, reset to the start (circular loop)
    if (currentScroll + scrollAmount >= maxScroll) {
        eventCarousel2.scrollTo({
            left: 0,  // Scroll back to the start
            behavior: 'smooth'
        });
    } else {
        eventCarousel2.scrollBy({
            left: scrollAmount,  // Scroll to the right by the defined scrollAmount
            behavior: 'smooth'
        });
    }
}

// Optional: Monitor the scroll position to debug for newsCarousel1
eventCarousel1.addEventListener('scroll', function() {
    console.log('Current Scroll Position of Carousel 1: ', eventCarousel1.scrollLeft);
});

// Optional: Monitor the scroll position to debug for newsCarousel2
eventCarousel2.addEventListener('scroll', function() {
    console.log('Current Scroll Position of Carousel 2: ', eventCarousel2.scrollLeft);
});
