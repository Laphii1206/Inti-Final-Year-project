<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthsShort = [
            @json(__('booking.m_jan')), @json(__('booking.m_feb')), @json(__('booking.m_mar')), @json(__('booking.m_apr')),
            @json(__('booking.m_may')), @json(__('booking.m_jun')), @json(__('booking.m_jul')), @json(__('booking.m_aug')),
            @json(__('booking.m_sep')), @json(__('booking.m_oct')), @json(__('booking.m_nov')), @json(__('booking.m_dec'))
        ];
        const daysShort = [
            @json(__('booking.d_sun')), @json(__('booking.d_mon')), @json(__('booking.d_tue')), @json(__('booking.d_wed')),
            @json(__('booking.d_thu')), @json(__('booking.d_fri')), @json(__('booking.d_sat'))
        ];

        document.querySelectorAll('.flatpickr-dob').forEach(function(input) {
            if (!input._flatpickr) {
                const fp = flatpickr(input, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d/m/Y",
                    maxDate: "today",
                    disableMobile: true,
                    monthSelectorType: "dropdown",
                    animate: true,
                    locale: {
                        weekdays: {
                            shorthand: daysShort,
                            longhand: daysShort
                        },
                        months: {
                            shorthand: monthsShort,
                            longhand: monthsShort
                        }
                    },
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.altInput) {
                            instance.altInput.style.cursor = "pointer";
                            instance.altInput.style.backgroundColor = "transparent";
                            instance.altInput.style.color = "#f4f4f5";
                            instance.altInput.style.border = "none";
                            instance.altInput.style.boxShadow = "none";
                            instance.altInput.style.fontWeight = "500";
                            instance.altInput.classList.add("py-2.5");

                            const group = instance.altInput.closest('.input-group') || instance.altInput.parentElement;
                            if (group) {
                                group.style.cursor = "pointer";
                                group.addEventListener('click', function(e) {
                                    if (e.target !== instance.altInput) {
                                        instance.open();
                                    }
                                });
                            }
                        }
                    }
                });
            }
        });
    });
</script>
