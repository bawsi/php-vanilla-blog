// Basic settings
const titleMaxLength = 100;
const titleMinLength = 10;
const bodyMinLength  = 50;

// Selecting elements we need
const form      = document.querySelector('form.article-form');
const title     = form.querySelector('input[name=title]');
const body      = form.querySelector('textarea[name=body]');
const errorsDiv = document.getElementById('article-errors');

// Adding submit event listener to form
form.addEventListener('submit', (e) => {
	// ckeditor was ony sending data to textarea on every 2nd submit. This force sends it on every submit
	for (var i in CKEDITOR.instances) {
		CKEDITOR.instances[i].updateElement();
	}

	// Validating article title and body
	const errors = validateArticle(title.value, body.value);

	// If there were any errors in validation, prevent form from submitting, and show errors
	if (errors.length) {
		e.preventDefault(); // prevent for from submitting

		// Making ul element and giving it some styling
		const ul       = document.createElement('ul');
		ul.style.color = 'red';

		// Putting each error from errors array into li element, and appending it to ul
		for (let i = 0; i < errors.length; i++) {
			let li         = document.createElement('li');
			li.textContent = errors[i];
			ul.appendChild(li);
		}

		// Reset errorsDiv, give it a title, and append ul to it
		errorsDiv.innerHTML = '<strong>Oops!</strong>';
		errorsDiv.append(ul);

		// scroll to top
		window.scrollTo(0, 0);


	}
});

// Function that validates articles title and body, and returns array of errors or false if no errors
function validateArticle(title, body) {
	const errors = [];
	if (title.length > titleMaxLength) {
		errors.push('Title is too long. Max ' + titleMaxLength + ' characters required.');
	}

	if (title.length < titleMinLength) {
		errors.push('Title is too short. Min ' + titleMinLength + ' characters required.');
		console.log(title);
	}

	if (body.length < bodyMinLength) {
		errors.push('Body is too short. Min ' + bodyMinLength + ' characters required.');
		console.log(body);
	}

	return errors;

}