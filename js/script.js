var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    centerSlide:true,
      grabCursor:true,
   
    loop: true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    mousewheel: {
        forceToAxis: true, // Enable vertical scroll on page and horizontal scroll in Swiper
      },
    keyboard: true,
    breakpoints: {
      300: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      500: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 40,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 40,
      },
      1200: {
        slidesPerView: 4,
        spaceBetween: 20,
      },
    },
  });


  // Member thinks
  var swiper = new Swiper(".mySwiper2", {
    slidesPerView: 1,
    centerSlide:true,
      grabCursor:true,
   
    loop: true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    mousewheel: {
        forceToAxis: true, // Enable vertical scroll on page and horizontal scroll in Swiper
      },
    keyboard: true,
    breakpoints: {
      300: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      600: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      800: {
        slidesPerView: 2,
        spaceBetween: 40,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 40,
      },
      1200: {
        slidesPerView: 4,
        spaceBetween: 20,
      },
    },
  });