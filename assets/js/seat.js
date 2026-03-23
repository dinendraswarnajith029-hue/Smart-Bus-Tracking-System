document.addEventListener("DOMContentLoaded", () => {

    const seats = document.querySelectorAll(".seat-icon");
    const input = document.getElementById("selectedSeats");
    let selectedSeats = [];

    seats.forEach(seat => {

        // Block occupied seats
        if (seat.classList.contains("occupied")) return;

        seat.addEventListener("click", () => {

            const seatNo = seat.dataset.seat;

            if (seat.classList.contains("selected")) {
                seat.classList.remove("selected");
                seat.classList.add("available");
                selectedSeats = selectedSeats.filter(s => s !== seatNo);
            } else {
                seat.classList.remove("available");
                seat.classList.add("selected");
                selectedSeats.push(seatNo);
            }

            input.value = selectedSeats.join(",");
        });
    });
});
