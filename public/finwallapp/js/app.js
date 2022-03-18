'use strict';

$(window).on('load', function () {
    var body = $('body');
    switch ($('body').attr('data-page')) {
        case "homepage":
            /* carousel */
            var swiper = new Swiper('.swiper-users', {
                slidesPerView: 'auto',
                spaceBetween: 15,
                pagination: 'false'
            });

            var swiper = new Swiper('.addsendcarousel', {
                slidesPerView: '4',
                spaceBetween: 15,
                pagination: {
                    el: '.swiper-pagination',
                },
            });

            $('#more-expand-btn').on('click', function () {
                $('#more-expand').addClass("active");
                $(this).addClass("active");
            });

            break;
        case "analytics":
            /* carousel */
            var swiper = new Swiper('.swiper-users', {
                slidesPerView: 'auto',
                spaceBetween: 30,
                pagination: 'false'
            });

            var swiper = new Swiper('.addsendcarousel', {
                slidesPerView: '4',
                spaceBetween: 15,
                pagination: {
                    el: '.swiper-pagination',
                },
            });
            var swiper = new Swiper('.swiper-cards', {
                slidesPerView: 'auto',
                spaceBetween: 30,
                pagination: {
                    el: '.swiper-pagination',
                },
            });

            $('#more-expand-btn').on('click', function () {
                $('#more-expand').addClass("active");
                $(this).addClass("active");
            });
            break;

        case "addmoney":
            /* carousel */
            var swiper7 = new Swiper('.swipercards', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                spaceBetween: 15,
                coverflowEffect: {
                    rotate: 30,
                    stretch: 0,
                    depth: 80,
                    modifier: 1,
                    slideShadows: true,
                }

            });
            break;
        case "changecurrency":
            /* chart */
            var areachart = document.getElementById('linechart').getContext('2d');
            var gradient1 = areachart.createLinearGradient(0, 0, 0, 300);
            gradient1.addColorStop(0, '#FF97B5');
            gradient1.addColorStop(0.5, 'rgba(251, 151, 181, 0)');

            var configareachart = {
                type: 'line',
                data: {
                    labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                    datasets: [{
                        label: 'My First dataset',
                        borderWidth: '1',
                        borderColor: 'rgba(255, 151, 181, 1)',
                        backgroundColor: gradient1,
                        data: [
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor()
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    elements: {
                        point: {
                            radius: '1',
                        }
                    },
                    title: {
                        display: false,
                        text: 'Chart.js Line Chart - Stacked Area'
                    },
                    tooltips: {
                        mode: 'index',
                    },
                    hover: {
                        mode: 'index'
                    },
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                display: true,
                                fontColor: "#aaaaaa",
                            },
                            display: true,
                            stacked: false,
                            scaleLabel: {
                                display: false,
                                labelString: 'Month'
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                display: true,
                                fontColor: "#aaaaaa",
                            },
                            display: true,
                            stacked: false,
                            scaleLabel: {
                                display: false,
                                labelString: 'Value'
                            }
                        }]
                    }
                }
            };

            window.salesareachart = new Chart(areachart, configareachart);

            break;
        case "checkout":
            /* swiper cards */
            var swiper7 = new Swiper('.swipercards', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                spaceBetween: 15,
                coverflowEffect: {
                    rotate: 30,
                    stretch: 0,
                    depth: 80,
                    modifier: 1,
                    slideShadows: true,
                }

            });
            break;
        case "referfriend":
            /* carousel */
            var swiper = new Swiper('.swiper-users', {
                slidesPerView: 'auto',
                spaceBetween: 15,
                pagination: 'false'
            });

            $('#coplink').on('click', function () {
                $('#referallink').select();
                $('#successmessage').removeClass('d-none').fadeIn();
                document.execCommand("copy");
            })
            break;
        case "landing":
            /* carousel */
            var swiper = new Swiper('.introduction', {
                autoplay: true,
                pagination: {
                    el: '.swiper-pagination',
                },
            });
            break;
        case "productdetails":
            /* gallery carousel*/
            var galleryThumbs = new Swiper('.gallery-thumbs', {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
            });
            var galleryTop = new Swiper('.gallery-top', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                thumbs: {
                    swiper: galleryThumbs
                }
            });

            break;
        case "products":
            /* carousel */
            var swiper3 = new Swiper('.categoriestab1', {
                slidesPerView: 'auto',
                spaceBetween: 15,
            });
            var swiper4 = new Swiper('.categories2tab1', {
                slidesPerView: 'auto',
                spaceBetween: 10,
            });

            break;
        case "sendmoney":
            /* carousel */
            var swiper = new Swiper('.swiper-users', {
                slidesPerView: 'auto',
                spaceBetween: 15,
                pagination: 'false'
            });

            /* swiper cards */
            var swiper7 = new Swiper('.swipercards', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                spaceBetween: 15,
                coverflowEffect: {
                    rotate: 30,
                    stretch: 0,
                    depth: 80,
                    modifier: 1,
                    slideShadows: true,
                }

            });
            break;
        case "recharge":
            /* carousel */
            var swiper = new Swiper('.swiper-users', {
                slidesPerView: 'auto',
                spaceBetween: 15,
                pagination: 'false'
            });

            /* swiper cards */
            var swiper7 = new Swiper('.swipercards', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                spaceBetween: 15,
                coverflowEffect: {
                    rotate: 30,
                    stretch: 0,
                    depth: 80,
                    modifier: 1,
                    slideShadows: true,
                }

            });
            break;
        case "search":
            var swiper4 = new Swiper('.categories2tab1', {
                slidesPerView: 'auto',
                spaceBetween: 10,
            });
            break;
        case "shop":
            /* carousel */
            var swiper1 = new Swiper('.offerslidetab1', {
                slidesPerView: 'auto',
                spaceBetween: 0,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
            var swiper2 = new Swiper('.offerslide2tab1', {
                slidesPerView: 'auto',
                spaceBetween: 0,
            });
            var swiper3 = new Swiper('.categoriestab1', {
                slidesPerView: 'auto',
                spaceBetween: 15,
            });
            var swiper4 = new Swiper('.categories2tab1', {
                slidesPerView: 'auto',
                spaceBetween: 10,
            });

            break;
        case "tooltips":
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
            break;
        case "thankyou":
            setTimeout(function () {
                window.location.replace("index.html");
            }, 3500)
            break;
        case "giftcards":
            /* carousel */
            var swiper = new Swiper('.swiper-users', {
                slidesPerView: 'auto',
                spaceBetween: 15,
                pagination: 'false'
            });
            break;
        case "tracks":
            /* Swiper */
            var swiper = new Swiper('.swiper-container', {
                slidesPerView: '1',
                spaceBetween: 30,
                pagination: 'false'
            });
            break;
        case "wallet":
            /* carousel */
            var swiper = new Swiper('.swiper-users', {
                slidesPerView: 'auto',
                spaceBetween: 15,
                pagination: 'false'
            });
            var swiper = new Swiper('.addsendcarousel', {
                slidesPerView: '4',
                spaceBetween: 15,
                pagination: {
                    el: '.swiper-pagination',
                },
            });
            break;
        case "withdraw":
            /* carousel */
            var swiper7 = new Swiper('.swipercards', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                spaceBetween: 15,
                coverflowEffect: {
                    rotate: 30,
                    stretch: 0,
                    depth: 80,
                    modifier: 1,
                    slideShadows: true,
                }

            });
            break;
    }


});
