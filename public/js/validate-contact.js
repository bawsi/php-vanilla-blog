// Selecting elements
const form      = document.getElementsByClassName('contact-form')[0];
const name      = form.querySelector('input[name=name]');
const mail      = form.querySelector('input[name=email]');
const subject   = form.querySelector('input[name=subject]');
const body      = form.querySelector('textarea[name=body]');
const errorsDiv = document.getElementById('contact-errors');

// Adding event listener to form, on submit
form.addEventListener('submit', (e) => {
	// Validate form, returns array of errors or false
	const errors = validateContact(name.value, mail.value, subject.value, body.value);

	// If there were any errors, prevent form from submitting, and show the errors
	if (errors.length) {
		e.preventDefault(); // prevent form from submitting

		// Making ul element and adding styles to it
		const ul       = document.createElement('ul');
		ul.style.color = 'red';

		// Adding each error into a li element, and appending that to ul
		for (let i = 0; i < errors.length; i++) {
			const li       = document.createElement('li');
			li.textContent = errors[i];
			ul.appendChild(li);
		}

		// Reset errorsDiv, give it a title, and append ul to it
		errorsDiv.innerHTML = '<strong>Oops!</strong>';
		errorsDiv.appendChild(ul);

		// scroll to top
		window.scrollTo(0, 0);
	}

});

// Validate form information. Returns array of errors, or false
function validateContact(name, mail, subject, msg) {
	const errors = [];

	if (name.length < 2) {
		errors.push('Name is too short.');
	}

	if (name.length > 30) {
		errors.push('name is too long.');
	}

	if (!/^[a-zA-Z]*(\s){0,1}[a-zA-Z]*$/.test(name)) {
		errors.push('Name is in an invalid format. (Valid format: "John Doe", or "John")')
	}

	if (!/^([a-zA-Z1-9-_\.]+)@([a-zA-Z0-9-_]{2,15})\.([a-zA-Z]{2,10})$/.test(mail)) {
		errors.push('Email is not valid')
	}

	if (subject.length < 5) {
		errors.push('Subject needs to be at least 5 characters long');
	}

	if (msg.length < 10) {
		errors.push('Message needs to be at least 10 characters long')
	}

	return errors;


}