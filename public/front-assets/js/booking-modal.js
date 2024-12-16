// Xử lý modal đặt bàn
var BookingModal = {
    init: function() {
        this.bindEvents();
    },

    bindEvents: function() {
        // Xử lý nút đóng
        $(document).on('click', '#closeModalBtn, #closeModalFooterBtn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            BookingModal.closeModal();
        });

        // Xử lý ESC
        $(document).on('keyup', function(e) {
            if (e.key === "Escape") {
                BookingModal.closeModal();
            }
        });

        // Xử lý click ngoài
        $(document).on('click', '#bookingDetailModal', function(e) {
            if ($(e.target).is('#bookingDetailModal')) {
                BookingModal.closeModal();
            }
        });
    },

    closeModal: function() {
        $('#bookingDetailModal').modal('hide');
    }
};

// Khởi tạo khi document ready
$(document).ready(function() {
    BookingModal.init();
}); 