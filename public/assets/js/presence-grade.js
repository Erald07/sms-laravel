document.addEventListener('DOMContentLoaded', function () {
    const presenceInputs = document.querySelectorAll('.presence-input');

    presenceInputs.forEach(presenceInput => {
        presenceInput.addEventListener('change', function () {
            const studentId = this.dataset.studentId;
            const gradeInput = document.querySelector(`.grade-input[data-student-id="${studentId}"]`);
            const errorMessage = document.querySelector(`.error-message[data-student-id="${studentId}"]`);

            if (this.value === "1" || this.value === "0") {
                gradeInput.disabled = false;
                errorMessage.classList.add('hidden');
            } else {
                gradeInput.disabled = true;
                errorMessage.classList.remove('hidden');
            }
        });
    });
});
