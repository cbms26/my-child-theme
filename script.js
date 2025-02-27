// Select the previous and next custom arrow buttons by their class or ID
const prevArrow = document.querySelector('.custom-prev-arrow');
const nextArrow = document.querySelector('.custom-next-arrow');

// Get the Smart Slider container by its ID (replace 'smartslider-1' with your slider's ID)
const slider = document.querySelector('.jcubsb-members-slider');

// Ensure the slider exists before adding event listeners
if (slider) {
  // Bind the previous arrow to navigate to the previous slide
  prevArrow.addEventListener('click', () => {
    slider.querySelector('.navi-prev').click(); // Trigger the default previous slide action
  });

  // Bind the next arrow to navigate to the next slide
  nextArrow.addEventListener('click', () => {
    slider.querySelector('.navi-next').click(); // Trigger the default next slide action
  });
}

