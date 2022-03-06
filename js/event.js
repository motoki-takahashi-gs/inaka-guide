class Calendar {

    constructor() {
        // calendar
        this.calendarModal = document.getElementById('calendar-modal');
        this.calendarBody = document.getElementById('calendar-body');
        this.closeMark = document.querySelector('#calendar-modal .close');
        this.previousMonthBtn = document.getElementById('previous-month');
        this.nextMonthBtn = document.getElementById('next-month');

        // dates related to calendar
        this.today = new Date();
        this.currentMonth = this.today.getMonth();
        this.currentYear = this.today.getFullYear();
        this.lastClickedCell = '';

        // first day (e.g. Monday or Wednesday, etc.) in current month
        this.firstDay = 0;

        // number of dates in current month
        this.daysInMonth = 0;

        // available dates for booking on calendar
        this.availableDatesArray = document.getElementsByClassName('available');

        // first date of month
        this.date = 1;

        // months
        this.months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        // days of week
        this.daysOfWeek = [
            'Sun',
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri',
            'Sat'
        ];

        // dates of event
        this.startYearMonthDate = document.getElementById('dates').textContent.split(' - ')[0];
        this.endYearMonthDate = document.getElementById('dates').textContent.split(' - ')[1];
        this.startYear = this.startYearMonthDate.substring(0, 4);
        this.startMonth = this.startYearMonthDate.substring(5, 7) - 1;
        this.startDate = this.startYearMonthDate.substring(8);
        this.endYear = this.endYearMonthDate.substring(0, 4);
        this.endMonth = this.endYearMonthDate.substring(5, 7) - 1;
        this.endDate = this.endYearMonthDate.substring(8);

        // Check dates button
        this.checkDatesBtn = document.getElementById('check-dates');

        // when Check dates at the bottom is clicked
        this.checkDatesBtn.addEventListener('click', () => this.toggleCalendar());

        // when the close mark of calendar is clicked
        this.closeMark.addEventListener('click', () => this.toggleCalendar());

        // when previous or next arrow button is clicked
        this.previousMonthBtn.addEventListener('click', () => this.goToPreviousMonth());
        this.nextMonthBtn.addEventListener('click', () => this.goToNextMonth());
    }

    toggleCalendar() {
        this.calendarModal.classList.toggle('modal-active');
    }

    showMonthYear(month, year) {
        document.getElementById('month-and-year').textContent = this.months[month] + ' ' + year;
    }

    showCalendar(month, year) {
        // first day (e.g. Monday or Wednesday, etc.) in current month
        this.firstDay = new Date(year, month).getDay();

        // number of dates in current month
        this.daysInMonth = 32 - new Date(year, month, 32).getDate();

        // initialize first date of month
        this.date = 1;

        // clear all previous cells
        this.calendarBody.innerHTML = '';

        // show the current month and year
        this.showMonthYear(month, year);

        // loop 6 times as a calendar has 6 weeks (rows) to display
        for (let i = 0; i < 6; i++) {

            // create 1 row
            const row = document.createElement('tr');

            // loop 7 times because a week has 7 days
            for (let j = 0; j < 7; j++) {

                // when dates have to be blank in the first week
                if (i == 0 && j < this.firstDay) {
                    const cell = document.createElement('td');
                    const cellText = document.createTextNode('');
                    cell.classList.add('beforeFirstDay');
                    cell.appendChild(cellText);
                    row.appendChild(cell);
                }

                // after reached the last date in the month
                else if (this.date > this.daysInMonth) {
                    break;
                }

                // dates to be shown in each cell
                else {
                    const cell = document.createElement('td');
                    const cellText = document.createTextNode(this.date);

                    // users can book only on dates between start date and end date of the event
                    if (
                        new Date(year, month, this.date) >= new Date(this.startYear, this.startMonth, this.startDate) &&
                        new Date(year, month, this.date) <= new Date(this.endYear, this.endMonth, this.endDate)
                    ) {
                        cell.classList.add('available');

                        // users can't book the tomorrow's event after today's 3 PM
                        if (
                            new Date(year, month, this.date, 15)
                            <= new Date(this.today.getFullYear(), this.today.getMonth(), this.today.getDate() + 1, this.today.getHours())
                        ) {
                            cell.classList.remove('available');
                            cell.classList.add('disabled');
                        }
                    }
                    // users can't book the event other than the dates above
                    else {
                        cell.classList.add('disabled');
                    }
                    cell.appendChild(cellText);
                    row.appendChild(cell);
                    this.date++;
                }
            }
            // add a week to the calendar body
            this.calendarBody.appendChild(row);
        }
    }

    showNextBtn() {
        document.getElementById('next-button').style.display = 'block';
    }

    removeStyleFromLastClickedCell() {
        if (this.lastClickedCell) {
            this.lastClickedCell.removeAttribute('style');
        }
    }

    getSelectedDateHyphened(clickedDate) {
        return this.currentYear + '-' + ('0' + (this.currentMonth + 1)).slice(-2) +
            '-' + ('0' + clickedDate).slice(-2);
    }

    setSelectedDateHyphenedToHiddenElm(clickedDate) {
        document.getElementById('selected-date-hyphened-hidden').value =
            this.getSelectedDateHyphened(clickedDate);
    }

    getSelectedDateNormal(clickedDay, clickedDate) {
        return this.daysOfWeek[clickedDay] + ', ' + this.months[this.currentMonth] +
            ' ' + clickedDate + ', ' + this.currentYear;
    }

    setSelectedDateNormalToHiddenElm(clickedDay, clickedDate) {
        document.getElementById('selected-date-normal-hidden').value =
            this.getSelectedDateNormal(clickedDay, clickedDate);
    }

    showSelectedDateNormal(clickedDay, clickedDate) {
        document.getElementById('selected-date-normal').textContent =
            this.getSelectedDateNormal(clickedDay, clickedDate);
    }

    addColorToSelectedCell(e) {
        e.target.style.backgroundColor = '#1e7900';
        e.target.style.color = '#fff';
    }

    setLastClickedCell(e) {
        this.lastClickedCell = e.target;
    }

    addEventListenerToAvailableDates() {
        for (let i = 0; i < this.availableDatesArray.length; i++) {
            this.availableDatesArray[i].addEventListener('click', (e) => {
                const clickedDate = e.target.textContent;
                const clickedDay = new Date(this.currentYear, this.currentMonth, clickedDate).getDay();
                this.showNextBtn();
                this.removeStyleFromLastClickedCell();
                this.setSelectedDateHyphenedToHiddenElm(clickedDate);
                this.setSelectedDateNormalToHiddenElm(clickedDay, clickedDate)
                this.showSelectedDateNormal(clickedDay, clickedDate);
                this.addColorToSelectedCell(e);
                this.setLastClickedCell(e);
            });
        }
    }

    goToPreviousMonth() {
        this.currentYear = this.currentMonth == 0 ? this.currentYear - 1 : this.currentYear;
        this.currentMonth = this.currentMonth == 0 ? 11 : this.currentMonth - 1;
        this.showCalendar(this.currentMonth, this.currentYear);
        this.addEventListenerToAvailableDates();
    }

    goToNextMonth() {
        this.currentYear = this.currentMonth == 11 ? this.currentYear + 1 : this.currentYear;
        this.currentMonth = (this.currentMonth + 1) % 12;
        this.showCalendar(this.currentMonth, this.currentYear);
        this.addEventListenerToAvailableDates();
    }
}

const calendar = new Calendar();
calendar.showCalendar(calendar.currentMonth, calendar.currentYear);
calendar.addEventListenerToAvailableDates();


class ReviewAndPayment {

    constructor() {

        this.reviewModal = document.getElementById('review-modal');
        this.numberOfGuestsElm = document.querySelector('select[name="number-of-guests"]');

        // go-to-review class is added only while logging in
        this.goToReview = document.getElementsByClassName('go-to-review')[0];

        // when redirected from login or signup page
        if (document.URL.indexOf('redirect=true') > -1) {

            // get the selected event info as an object from local storage
            this.selectedEventFromLocalStorage = JSON.parse(localStorage.getItem('selected_event_info'));

            // when the page is loaded
            window.addEventListener('load',
                () => this.showReviewAndPaymentAfterRedirected(this.selectedEventFromLocalStorage));
        }

        // when the next button is clicked only while logging in
        if (this.goToReview) {
            this.goToReview.addEventListener('click', () => this.showReviewAndPayment(
                document.getElementById('selected-date-normal-hidden').value,
                this.numberOfGuestsElm.value,
                this.calculateTotalAmount(this.numberOfGuestsElm.value)
            ));
        }

        // when the close mark at Review and payment is clicked
        document.querySelector('#review-modal .close').addEventListener('click',
            () => this.toggleReviewAndPayment());
    }

    toggleReviewAndPayment() {
        this.reviewModal.classList.toggle('modal-active');
    }

    calculateTotalAmount(numberOfGuests) {
        let totalAmount = 25000;
        if (numberOfGuests >= 2) {
            for (let i = 1; i < numberOfGuests; i++) {
                totalAmount += 5000;
            }
        }
        return totalAmount;
    }

    showSelectedDateNormal(selectedDate) {
        document.querySelector('.review-selected-date').lastElementChild.textContent = selectedDate;
    }

    showNumberOfGuests(numberOfGuests) {
        document.querySelector('.review-number-of-guests').lastElementChild.textContent = numberOfGuests;
    }

    showTotalAmount(totalAmount) {
        document.querySelector('.review-total-amount').lastElementChild.textContent =
            new Intl.NumberFormat('ja-JP', {
                style: 'currency', currency: 'JPY'
            }).format(totalAmount);
    }

    setTotalAmountToHiddenElm(totalAmount) {
        document.querySelector('input[name="total-amount"]').value = totalAmount;
    }

    showReviewAndPayment(selectedDateNormal, numberOfGuests, totalAmount) {
        this.toggleReviewAndPayment();
        this.showSelectedDateNormal(selectedDateNormal);
        this.showNumberOfGuests(numberOfGuests);
        this.showTotalAmount(totalAmount);
        this.setTotalAmountToHiddenElm(totalAmount);
    }

    setSelectedDateHyphenedToHiddenElm(selectedDateHyphened) {
        document.getElementById('selected-date-hyphened-hidden').value = selectedDateHyphened;
    }

    setSelectedDateNormalToHiddenElm(selectedDateNormal) {
        document.getElementById('selected-date-normal-hidden').value = selectedDateNormal;
    }

    setSelectedNumberOfGuests(selectedNumberOfGuests) {
        this.numberOfGuestsElm.value = selectedNumberOfGuests;
    }

    showReviewAndPaymentAfterRedirected(selectedEventObj) {
        this.showReviewAndPayment(
            selectedEventObj['dateDisplay'],
            selectedEventObj['numberOfGuests'],
            this.calculateTotalAmount(selectedEventObj['numberOfGuests'])
        );
        this.setSelectedDateHyphenedToHiddenElm(selectedEventObj['dateMysql']);
        this.setSelectedDateNormalToHiddenElm(selectedEventObj['dateDisplay']);
        this.setSelectedNumberOfGuests(selectedEventObj['numberOfGuests']);
    }
}

const reviewAndPayment = new ReviewAndPayment();