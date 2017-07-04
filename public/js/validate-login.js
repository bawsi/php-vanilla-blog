// Some basic options for validation
const usernameMaxLength = 10;
const usernameMinLength = 3;
const passwordMinLength = 4;
const passwordMaxLength = 25;

// Selecting some elements we need later on
const loginForm = document.getElementsByClassName('login-form')[0];
const username  = loginForm.querySelector('input[name=username]');
const password  = loginForm.querySelector('input[name=password]');
const errorsDiv = document.getElementById('errors');


// Event listener added to form, so when form submits, this executes
loginForm.addEventListener('submit', (e) => {
	const errors = validateUsernameAndPassword(username.value, password.value)

	// If there were any errors in validation, prevent form
	// from submitting, and show those errors to the user
	if (errors.length > 0) {
		e.preventDefault();

		// Creating ul element and giving it some basic attributes
		const ul = document.createElement('ul');
		ul.id = 'js_errors';
		ul.style.color = 'red';

		// put each error from errors array into li element, and append that to ul
		for (let i = 0; i < errors.length; i++) {
			const errorLi  = document.createElement('li');
			errorLi.textContent = errors[i];

			ul.appendChild(errorLi);
		}

		// Reset errorsDiv (in case there are already errors in there,
		// and then append ul created above to that errorsDiv
		errorsDiv.innerHTML = '';
		errorsDiv.innerHTML = '<strong>Error!</strong>';
		errorsDiv.appendChild(ul);

	}

});


// Function that validates username and password, and returns array of errors, or false
function validateUsernameAndPassword(username, password) {
	const reUsernameCheck = /^[a-zA-Z0-9_-]*$/;
	const errors = [];

	if (!reUsernameCheck.test(username)) {
		errors.push('Username contains illegal characters.')
	}

	if (username.length > usernameMaxLength) {
		errors.push('Username is too long. Max ' + usernameMaxLength + ' characters allowed.');
	}

	if (username.length < usernameMinLength) {
		errors.push('Username is too short. Min ' + usernameMinLength + ' characters allowed.');
	}

	if (password.length > passwordMaxLength) {
		errors.push('Password is too long. Max ' + passwordMaxLength + ' characters allowed');
	}

	if (password.length < passwordMinLength) {
		errors.push('Password is too short. Min ' + passwordMinLength + ' characters allowed');
	}

	return (errors) ? errors : false;

}
