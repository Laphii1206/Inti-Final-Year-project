<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Custom Flatpickr Big Tech / Apple Glassmorphic Theme */
    .flatpickr-calendar {
        background: #181920 !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        border-radius: 20px !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.85), 0 0 0 1px rgba(255, 255, 255, 0.08) !important;
        padding: 16px !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
        backdrop-filter: blur(16px) !important;
        animation: fpFadeInDown 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        width: 310px !important;
        max-width: calc(100vw - 20px) !important;
        box-sizing: border-box !important;
    }
    @keyframes fpFadeInDown {
        0% { opacity: 0; transform: translateY(-10px) scale(0.98); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .flatpickr-months {
        padding-bottom: 12px !important;
        margin-bottom: 8px !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
    }
    .flatpickr-months .flatpickr-month {
        background: transparent !important;
        color: #ffffff !important;
        height: 44px !important;
        overflow: visible !important;
    }
    .flatpickr-current-month {
        font-size: 1.15rem !important;
        font-weight: 800 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        padding: 0 !important;
        left: 0 !important;
        width: 100% !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months {
        background: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        border-radius: 10px !important;
        padding: 4px 10px !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 1rem !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        appearance: none !important;
        -webkit-appearance: none !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
        background: rgba(255, 255, 255, 0.15) !important;
        border-color: rgba(236, 31, 36, 0.5) !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months option,
    .flatpickr-calendar select option {
        background-color: #181920 !important;
        color: #ffffff !important;
        padding: 10px !important;
        font-weight: 600 !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months option:checked,
    .flatpickr-current-month .flatpickr-monthDropdown-months option:hover {
        background: #EC1F24 !important;
        background-color: #EC1F24 !important;
        color: #ffffff !important;
    }
    .flatpickr-current-month input.cur-year {
        background: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        border-radius: 10px !important;
        padding: 4px 10px !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 1rem !important;
        width: 76px !important;
        transition: all 0.2s !important;
    }
    .flatpickr-current-month input.cur-year:focus {
        background: rgba(255, 255, 255, 0.15) !important;
        border-color: #EC1F24 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(236, 31, 36, 0.25) !important;
    }
    .flatpickr-current-month .numInputWrapper span.arrowUp:after {
        border-bottom-color: rgba(255, 255, 255, 0.8) !important;
    }
    .flatpickr-current-month .numInputWrapper span.arrowDown:after {
        border-top-color: rgba(255, 255, 255, 0.8) !important;
    }
    .flatpickr-current-month .numInputWrapper span:hover {
        background: rgba(236, 31, 36, 0.3) !important;
    }
    .flatpickr-current-month .numInputWrapper span:hover.arrowUp:after {
        border-bottom-color: #ffffff !important;
    }
    .flatpickr-current-month .numInputWrapper span:hover.arrowDown:after {
        border-top-color: #ffffff !important;
    }
    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        padding: 10px !important;
        border-radius: 10px !important;
        transition: all 0.2s !important;
        top: 14px !important;
    }
    .flatpickr-months .flatpickr-prev-month:hover,
    .flatpickr-months .flatpickr-next-month:hover {
        background: rgba(236, 31, 36, 0.2) !important;
        color: #ffffff !important;
    }
    .flatpickr-months .flatpickr-prev-month svg,
    .flatpickr-months .flatpickr-next-month svg {
        fill: #ffffff !important;
        width: 14px !important;
        height: 14px !important;
    }
    .flatpickr-innerContainer,
    .flatpickr-rContainer,
    .flatpickr-days,
    .flatpickr-weekdays,
    .flatpickr-weekdaycontainer {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        box-sizing: border-box !important;
        overflow: visible !important;
    }
    .flatpickr-weekdays {
        margin-top: 4px !important;
        margin-bottom: 6px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: 28px !important;
    }
    .flatpickr-weekdaycontainer {
        display: grid !important;
        grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
        gap: 2px !important;
    }
    .flatpickr-weekday {
        color: rgba(255, 255, 255, 0.45) !important;
        font-weight: 700 !important;
        font-size: 0.78rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        text-align: center !important;
        line-height: 28px !important;
        margin: 0 !important;
        flex-basis: auto !important;
        width: 100% !important;
    }
    .flatpickr-days {
        display: flex !important;
        justify-content: center !important;
    }
    .dayContainer {
        display: grid !important;
        grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        gap: 4px 2px !important;
        padding: 4px 0 !important;
        justify-items: center !important;
        align-items: center !important;
    }
    .flatpickr-day {
        color: #e4e4e7 !important;
        border-radius: 10px !important;
        font-weight: 500 !important;
        height: 36px !important;
        width: 100% !important;
        max-width: 36px !important;
        line-height: 36px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 auto !important;
        flex-basis: auto !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    .flatpickr-day:hover {
        background: rgba(236, 31, 36, 0.25) !important;
        border-color: transparent !important;
        color: #ffffff !important;
        transform: scale(1.08) !important;
    }
    .flatpickr-day.selected {
        background: #EC1F24 !important;
        border-color: #EC1F24 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 15px rgba(236, 31, 36, 0.4) !important;
    }
    .flatpickr-day.today {
        border: 1px solid #EC1F24 !important;
        background: rgba(236, 31, 36, 0.1) !important;
        color: #ffffff !important;
    }
    .flatpickr-day.today:hover {
        background: rgba(236, 31, 36, 0.3) !important;
    }
    .flatpickr-day.flatpickr-disabled {
        color: rgba(255, 255, 255, 0.15) !important;
    }
    .flatpickr-day.prevMonthDay,
    .flatpickr-day.nextMonthDay {
        color: rgba(255, 255, 255, 0.25) !important;
    }

    /* Custom Input Box Enhancements for Birthday Picker */
    .flatpickr-dob-group {
        border: 1px solid rgba(255, 255, 255, 0.10) !important;
        border-radius: 10px !important;
        overflow: hidden !important;
        transition: all 0.2s ease !important;
        background-color: #1b1b1b !important;
    }
    .flatpickr-dob-group:focus-within,
    .flatpickr-dob-group:hover {
        border-color: #EC1F24 !important;
        box-shadow: 0 0 0 0.2rem rgba(236, 31, 36, 0.20) !important;
    }
    .flatpickr-dob-group .input-group-text {
        background-color: transparent !important;
        border: none !important;
        color: #EC1F24 !important;
        padding-left: 14px !important;
        padding-right: 10px !important;
        transition: transform 0.2s !important;
    }
    .flatpickr-dob-group:hover .input-group-text i {
        transform: scale(1.15) !important;
    }
    .flatpickr-dob-group input.flatpickr-dob,
    .flatpickr-dob-group input.flatpickr-input {
        background-color: transparent !important;
        border: none !important;
        color: #f4f4f5 !important;
        font-weight: 500 !important;
        padding-left: 4px !important;
        box-shadow: none !important;
        cursor: pointer !important;
    }
    .flatpickr-dob-group input.flatpickr-input::placeholder {
        color: #6b7280 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkCalendarBounds = function(calendar) {
        if (!calendar || !calendar.classList.contains('flatpickr-calendar') || !calendar.classList.contains('open')) return;
        const rect = calendar.getBoundingClientRect();
        const viewportWidth = window.innerWidth || document.documentElement.clientWidth;
        if (rect.right > viewportWidth - 10) {
            const shiftLeft = rect.right - viewportWidth + 16;
            const currentLeft = parseFloat(calendar.style.left || rect.left);
            calendar.style.left = Math.max(10, currentLeft - shiftLeft) + "px";
        }
        if (rect.left < 10) {
            calendar.style.left = "10px";
        }
    };

    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const target = mutation.target;
                if (target.classList && target.classList.contains('flatpickr-calendar') && target.classList.contains('open')) {
                    setTimeout(function() { checkCalendarBounds(target); }, 10);
                }
            }
        });
    });

    if (document.body) {
        observer.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['class'] });
    }
    window.addEventListener('resize', function() {
        document.querySelectorAll('.flatpickr-calendar.open').forEach(checkCalendarBounds);
    });
});
</script>
