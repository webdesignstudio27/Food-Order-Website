$(document).ready(function () {
    // Hover effect for .col-md-3
    $('.col-md-3').hover(function () {
        // Add bounce animation and scale on hover
        $(this).css({
            'animation': 'bounce-once 0.5s ease',
            'transform': 'scale(1.05)',
            'box-shadow': '0 10px 20px rgba(0, 0, 0, 0.2), 0 12px 30px rgba(0, 0, 0, 0.2)'
        });

        // Remove animation after it completes
        setTimeout(() => {
            $(this).css('animation', '');
        }, 500); // Duration matches animation
    }, function () {
        // Reset scale and shadow when hover ends
        $(this).css({
            'transform': 'scale(1)',
            'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.1)'
        });
    });

    // Hover effect for .col-md-4
    $('.col-md-4').hover(function () {
        // Add bounce animation and scale on hover
        $(this).css({
            'animation': 'bounce-once 0.5s ease',
            'transform': 'scale(1.05)',
            'box-shadow': '0 10px 20px rgba(0, 0, 0, 0.2), 0 12px 30px rgba(0, 0, 0, 0.2)'
        });

        // Remove animation after it completes
        setTimeout(() => {
            $(this).css('animation', '');
        }, 500); // Duration matches animation
    }, function () {
        // Reset scale and shadow when hover ends
        $(this).css({
            'transform': 'scale(1)',
            'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.1)'
        });
    });

    // Append keyframes for the bounce animation dynamically
    $('head').append(`
        <style>
            @keyframes bounce-once {
                0% {
                    transform: translateY(0);
                }
                25% {
                    transform: translateY(-10px);
                }
                50% {
                    transform: translateY(0);
                }
                75% {
                    transform: translateY(-10px);
                }
                100% {
                    transform: translateY(0);
                }
            }
        </style>
    `);

     $('#sidebarnav li').hover(function () {
        // Show submenu when hovering over the parent
        $(this).children('.collapse').stop(true, true).slideDown(300);
    }, function () {
        // Hide submenu when leaving the parent
        $(this).children('.collapse').stop(true, true).slideUp(300);
    });


    // Add hover effect to links
    $('#sidebarnav li a').hover(
        function () {
            // On mouse enter
            $(this).css({
                'background-color': '#e0e0e0', // Slightly darker background
                'color': '#0056b3',           // Dark blue text color
                'transform': 'scale(1.02)',   // Slight zoom effect
                'transition': 'all 0.3s ease' // Smooth animation
            });
        },
        function () {
            // On mouse leave
            $(this).css({
                'background-color': '', // Reset to default
                'color': '',            // Reset to default
                'transform': 'scale(1)' // Reset zoom
            });
        }
    );

    // Optional: Highlight the active menu item
    $('#sidebarnav li a').click(function () {
        $('#sidebarnav li a').removeClass('active'); // Remove active class from all
        $(this).addClass('active'); // Add active class to clicked item
    });
});
