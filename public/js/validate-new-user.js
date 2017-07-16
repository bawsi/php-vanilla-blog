// Some basic options for validation
const usernameMaxLength = 15;
const usernameMinLength = 3;
const passwordMinLength = 5;
const passwordMaxLength = 25;

// Selecting both the new user and edit user form
const userForms = document.getElementsByClassName('user-form');

// Looping over both forms, so I can add event listener to both
for (let i = 0; i < userForms.length; i++) {
	const userForm = userForms[i];

	// Event listener added to form, so when form submits, this executes
	userForm.addEventListener('submit', (e) => {
		// Selecting form fields and errors div
		const username  = userForm.querySelector('input[name=username]');
		const password  = userForm.querySelector('input[name=password]');
		const errorsDiv = userForm.parentNode.querySelector('.user-errors');

		// Validating data user submitted
		const errors = validateUser(username.value, password.value)

		// If there were any errors in validation, prevent form
		// from submitting, and show those errors to the user
		if (errors.length) {
			e.preventDefault();

			// Creating ul element and giving it some basic attributes
			const ul       = document.createElement('ul');
			ul.id          = 'js_errors';
			ul.style.color = 'red';

			// put each error from errors array into li element, and append that to ul
			for (let i = 0; i < errors.length; i++) {
				const errorLi       = document.createElement('li');
				errorLi.textContent = errors[i];

				ul.appendChild(errorLi);
			}

			// Reset errorsDiv (in case there are already errors in there,
			// and then append ul created above to that errorsDiv
			errorsDiv.innerHTML = '';
			errorsDiv.innerHTML = '<strong>Oops!</strong>';
			errorsDiv.appendChild(ul);

		}

	});


	// Function that validates username and password, and returns array of errors, or false
	function validateUser(username, password) {
		const reUsernameCheck = /^[a-zA-Z0-9_-]*$/;
		const errors          = [];

		if (!reUsernameCheck.test(username)) {
			errors.push('Username contains illegal characters.')
		}

		if (username.length > usernameMaxLength) {
			errors.push('Username is too long. Max ' + usernameMaxLength + ' characters required.');

		} else if (username.length < usernameMinLength) {
			errors.push('Username is too short. Min ' + usernameMinLength + ' characters required.');
		}

		if (password.length > passwordMaxLength) {
			errors.push('Password is too long. Max ' + passwordMaxLength + ' characters required');

		} else if (password.length < passwordMinLength) {
			errors.push('Password is too short. Min ' + passwordMinLength + ' characters required');
		}

		return errors;

	}
}