$(document).ready(function() {
    // Toggle search form
    $('#search-icon').on('click', function() {
        $('#search-form').toggleClass('active');
    });

    // Icon hover effect
    $('.icons i, .icons a').hover(
        function() {
            $(this).css({
                'color': '#fff',
                'background': 'var(--green)',
                'transform': 'rotate(360deg)'
            });
        }, function() {
            $(this).css({
                'color': 'var(--black)',
                'background': '#eee',
                'transform': 'rotate(0deg)'
            });
        }
    );

    // Smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(event) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: $($.attr(this, 'href')).offset().top
        }, 500);
    });

    // Mobile menu toggle
    $('#menu-bars').on('click', function() {
        $('.navbar').toggleClass('active');
    });

    // Loader fade out on window load
    $(window).on('load', function() {
        $('.loader-container').fadeOut(500);
    });

    // Button hover color change
    $('.btn-primary').hover(
        function() {
            $(this).css('background-color', '#27ae60');
        }, function() {
            $(this).css('background-color', '#666');
        }
    );
});
