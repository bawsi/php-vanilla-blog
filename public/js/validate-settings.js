// Basic settings for validation
const passMinLen = 5;
const passMaxLen = 25;

// Selecting elements
const form          = document.getElementsByClassName('settings-form')[0];
const oldPass       = form.querySelector('input[name=old-password]');
const newPass       = form.querySelector('input[name=new-password]');
const newPassVerify = form.querySelector('input[name=new-password-verify]');
const errorsDiv     = document.getElementById('settings-errors');

// Adding on submit event listener to form
form.addEventListener('submit', (e) => {
	// Validating passwords, which returns array of errors or empty array
	const errors = validatePasswords(oldPass.value, newPass.value, newPassVerify.value);

	// If there are any errrors in array
	if (errors.length) {
		// Prevent form from sumitting
		e.preventDefault();

		// Make ul element, style it
		const ul       = document.createElement('ul');
		ul.style.color = 'red';

		// Add each error from errors array to li, then append that li to ul
		for (let i = 0; i < errors.length; i++) {
			const li       = document.createElement('li');
			li.textContent = errors[i];

			ul.appendChild(li);
		}

		// Reset errorsDiv, give it a 'title', and append ul to it
		errorsDiv.innerHTML = '<strong>Oops!</strong>';
		errorsDiv.appendChild(ul);

		// scroll to top
		window.scrollTo(0, 0);
	}
});

// Function that validates passwords
function validatePasswords(oldPw, newPw, newPwVerify) {
	const errors = [];

	if (oldPw.length < passMinLen) {
		errors.push('Old password is too short');
	}

	if (newPw.length > passMaxLen) {
		errors.push('New password is too long. Max ' + passMaxLen + ' characters allowed')

	} else if (newPw < passMinLen) {
		errors.push('New password is too short. Min ' + passMinLen + ' characters required')
	}

	if (newPw !== newPwVerify) {
		errors.push('Passwords do not match...');
	}

	return errors;

}