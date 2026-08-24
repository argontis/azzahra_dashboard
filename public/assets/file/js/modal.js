/**
 * Universal Modern Modal Controller
 * Azzahra Computer Super-Apps
 */

window.openAppModal = function(modalTarget) {
    var $modal = $(modalTarget);
    if (!$modal.length) return;

    // Ensure all other modals are closed
    $('.modal.show').removeClass('show').css('display', 'none');

    // Show target modal
    $modal.addClass('show').css({
        'display': 'flex',
        'opacity': '1',
        'visibility': 'visible',
        'margin': '0',
        'top': '0',
        'left': '0'
    });

    $('body').addClass('modal-open').css('overflow', 'hidden');

    // Refresh feather icons inside modal if needed
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
};

window.closeAppModal = function(modalTarget) {
    var $modal = modalTarget ? $(modalTarget) : $('.modal.show');
    if (!$modal.length) return;

    $modal.removeClass('show').css({
        'display': 'none',
        'opacity': '0',
        'visibility': 'hidden'
    });

    $('body').removeClass('modal-open').css('overflow', '');
};

// Global click handler for [data-toggle="modal"]
$(document).on('click', '[data-toggle="modal"]', function(e) {
    e.preventDefault();
    var target = $(this).data('target') || $(this).attr('data-target') || $(this).attr('href');
    if (target && target.startsWith('#')) {
        window.openAppModal(target);
    }
});

// Global click handler for [data-dismiss="modal"]
$(document).on('click', '[data-dismiss="modal"]', function(e) {
    e.preventDefault();
    var $closestModal = $(this).closest('.modal');
    if ($closestModal.length) {
        window.closeAppModal($closestModal);
    } else {
        window.closeAppModal();
    }
});

// Close modal when clicking on the dark backdrop area outside modal content
$(document).on('click', '.modal', function(e) {
    if ($(e.target).hasClass('modal')) {
        window.closeAppModal($(this));
    }
});

// Close on ESC key
$(document).on('keydown', function(e) {
    if (e.key === 'Escape' || e.keyCode === 27) {
        window.closeAppModal();
    }
});

// Specific handler for Setoran
$(document).on('click', '.modal-setoran', function(e) {
    e.preventDefault();
    var kode        = $(this).data('kode');
    var bank        = $(this).data('bank');
    var jml_setoran = $(this).data('jml_setoran');

    $('#modal-kode').val(kode);
    $('#modal-bank').val(bank);
    $('#modal-jml-tranfer').val(jml_setoran);
    window.openAppModal('#setoran');
});