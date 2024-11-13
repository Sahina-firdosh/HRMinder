function generateCalendar() {
    const calendarContent = document.getElementById('calendar_dates');
    const calendarHeader = document.getElementById('month_name');
    const dayNamesContainer = document.getElementById('day_names');
    
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    calendarContent.innerHTML = '';
    dayNamesContainer.innerHTML = '';

    calendarHeader.textContent = `${monthNames[currentMonth]} ${currentYear}`;

    // Display day names row
    dayNames.forEach(day => {
        const dayNameDiv = document.createElement('div');
        dayNameDiv.textContent = day;
        dayNamesContainer.appendChild(dayNameDiv);
    });

    const firstDay = new Date(currentYear, currentMonth, 1).getDay();

    for (let i = 0; i < firstDay; i++) {
        const blankDiv = document.createElement('div');
        blankDiv.textContent = '';
        calendarContent.appendChild(blankDiv);
    }

    // Add actual days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayDiv = document.createElement('div');
        dayDiv.textContent = day;

        if(day===currentDate.getDate()){
            dayDiv.id="curr_date";
        }
        calendarContent.appendChild(dayDiv);
    } 
}

generateCalendar();
